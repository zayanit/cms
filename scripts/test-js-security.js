#!/usr/bin/env node
'use strict';

const fs = require('fs');
const path = require('path');
const vm = require('vm');

const payloads = [
    '<script>alert(1)</script>',
    '<img src=x onerror=alert(1)>',
    '<svg onload=alert(1)>',
    '"><img src=x onerror=alert(1)>',
];

for (const file of ['public/js/admin.js', 'public/js/public.js']) {
    const source = fs.readFileSync(path.join(__dirname, '..', file), 'utf8');
    assertNoUnsafeNotificationPatterns(source, file);

    const context = createBrowserContext();
    vm.runInNewContext(source, context, { filename: file });

    for (const payload of payloads) {
        context.toastr.calls.length = 0;

        context.app.message({
            status: 422,
            statusText: 'Unprocessable Content',
            responseText: JSON.stringify({ message: [payload] }),
        });

        const [type, message, title] = context.toastr.calls[0];
        assertEqual(type, 'warning', `${file} should show validation warnings`);
        assertSafeHtml(message, payload, `${file} should escape validation payloads`);
        assertEqual(title, 'Unprocessable Content', `${file} should preserve titles`);
        assertIncludes(message, '<br>', `${file} should keep line breaks between validation messages`);
    }

    context.toastr.calls.length = 0;
    context.app.message({
        status: 201,
        statusText: 'Created',
        responseText: JSON.stringify({ message: 'Saved <strong>successfully</strong>' }),
    });

    const [type, message] = context.toastr.calls[0];
    assertEqual(type, 'success', `${file} should show created responses as success`);
    assertSafeHtml(message, 'Saved <strong>successfully</strong>', `${file} should escape success messages`);
}

console.log('JavaScript security regression checks passed.');

function createBrowserContext() {
    const noopChain = createNoopChain();

    function $(arg) {
        if (arg === '<div/>') {
            return createEscapingElement();
        }

        return noopChain;
    }

    $.each = function each(collection, callback) {
        if (Array.isArray(collection)) {
            collection.forEach((value, index) => callback(index, value));
            return;
        }

        Object.keys(collection).forEach((key) => callback(key, collection[key]));
    };

    const toastr = createToastr();

    return {
        $,
        jQuery: Object.assign($, {
            validator: { setDefaults() {} },
            parseJSON: JSON.parse,
        }),
        document: {},
        console,
        FormData: function FormData() {
            this.append = function append() {};
        },
        swal() {},
        toastr,
    };
}

function createNoopChain() {
    const chain = {};
    const methods = [
        'ajaxComplete', 'ajaxError', 'ajaxSuccess', 'ajaxSetup', 'attr', 'code', 'data',
        'DataTable', 'iCheck', 'load', 'on', 'pickadate', 'pickatime', 'prop',
        'serializeArray', 'summernote', 'timeago', 'valid',
    ];

    for (const method of methods) {
        chain[method] = function noop() {
            return method === 'valid' ? true : chain;
        };
    }

    return chain;
}

function createEscapingElement() {
    let value = '';

    return {
        text(nextValue) {
            value = nextValue;
            return this;
        },
        html() {
            return escapeHtml(value);
        },
    };
}

function createToastr() {
    const calls = [];
    const toastr = { calls, options: {} };

    for (const type of ['success', 'warning', 'info', 'error']) {
        toastr[type] = function record(message, title) {
            calls.push([type, message, title]);
        };
    }

    return toastr;
}

function assertNoUnsafeNotificationPatterns(source, file) {
    const unsafePatterns = [
        'msgText    = response.message;',
        'msgText    += val + "<br>";',
    ];

    for (const pattern of unsafePatterns) {
        if (source.includes(pattern)) {
            throw new Error(`${file} still contains unsafe notification pattern: ${pattern}`);
        }
    }
}

function assertSafeHtml(actual, original, message) {
    if (actual.includes(original)) {
        throw new Error(`${message}: raw payload was inserted`);
    }

    if (/[<](script|img|svg|strong)\b/i.test(actual) || /<[^>]+\son\w+=/i.test(actual)) {
        throw new Error(`${message}: unsafe HTML remained in ${actual}`);
    }
}

function assertIncludes(actual, expected, message) {
    if (!actual.includes(expected)) {
        throw new Error(`${message}: expected ${expected} in ${actual}`);
    }
}

function assertEqual(actual, expected, message) {
    if (actual !== expected) {
        throw new Error(`${message}: expected ${expected}, got ${actual}`);
    }
}

function escapeHtml(value) {
    return String(value)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#39;');
}
