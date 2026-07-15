#!/usr/bin/env node
/**
 * Build vendor JS/CSS bundles from npm packages.
 * Replaces the old Bower + Gulp 3 + laravel-elixir pipeline.
 *
 * Output:
 *   public/js/vendor_admin.js   — admin panel JS bundle
 *   public/js/vendor_public.js  — public-facing JS bundle
 *   public/css/vendor_admin.css — admin panel CSS bundle
 *   public/css/vendor_public.css— public-facing CSS bundle
 *
 * Generated files are intentionally excluded from CodeQL analysis
 * (see .github/workflows/codeql.yml paths-ignore) because they are
 * concatenated third-party libraries. First-party code in
 * public/js/admin.js and public/js/public.js is analyzed directly.
 */

'use strict';

const fs = require('fs');
const path = require('path');

const nm = path.join(__dirname, '..', 'node_modules');

function read(relPath) {
    const full = path.join(nm, relPath);
    if (!fs.existsSync(full)) {
        console.warn(`WARNING: missing ${full}`);
        return `/* MISSING: ${relPath} */`;
    }
    return fs.readFileSync(full, 'utf-8');
}

function concat(files) {
    return files.map(read).join('\n');
}

function write(outPath, content) {
    const full = path.join(__dirname, '..', outPath);
    fs.mkdirSync(path.dirname(full), { recursive: true });
    fs.writeFileSync(full, content, 'utf-8');
    const kb = (Buffer.byteLength(content, 'utf-8') / 1024).toFixed(1);
    console.log(`  wrote ${outPath} (${kb} KB)`);
}

// ── vendor_admin.js ──────────────────────────────────────────────────────────
const adminJs = [
    'jquery-ui/dist/jquery-ui.js',
    'bootstrap/dist/js/bootstrap.js',
    'datatables.net/js/jquery.dataTables.js',
    'datatables.net-bs/js/dataTables.bootstrap.js',
    'jquery-validation/dist/jquery.validate.js',
    'moment/moment.js',
    'pickadate/lib/picker.js',
    'pickadate/lib/picker.time.js',
    'pickadate/lib/picker.date.js',
    'toastr/toastr.js',
    'icheck/icheck.js',
    'sortablejs/Sortable.js',
    'sweetalert/dist/sweetalert.min.js',
    'dropzone/dist/dropzone.js',
    'summernote/dist/summernote.js',
    'sortablejs/Sortable.min.js',
    'timeago/jquery.timeago.js',
];

// ── vendor_public.js ─────────────────────────────────────────────────────────
const publicJs = [
    'bootstrap/dist/js/bootstrap.js',
    'icheck/icheck.js',
    'sweetalert/dist/sweetalert.min.js',
    'jquery-validation/dist/jquery.validate.js',
    'moment/moment.js',
    'toastr/toastr.js',
    'dropzone/dist/dropzone.js',
    'pickadate/lib/picker.js',
    'pickadate/lib/picker.time.js',
    'pickadate/lib/picker.date.js',
    'summernote/dist/summernote.js',
    'timeago/jquery.timeago.js',
    'sortablejs/Sortable.min.js',
];

// ── vendor_admin.css ─────────────────────────────────────────────────────────
// Note: sweetalert@2.1.2 (original sweetalert v1) embeds its CSS in the JS.
const adminCss = [
    'bootstrap/dist/css/bootstrap.min.css',
    'font-awesome/css/font-awesome.min.css',
    'datatables.net-bs/css/dataTables.bootstrap.css',
    'pickadate/lib/themes/classic.css',
    'pickadate/lib/themes/classic.time.css',
    'pickadate/lib/themes/classic.date.css',
    'toastr/build/toastr.min.css',
    'dropzone/dist/min/dropzone.min.css',
    'summernote/dist/summernote.css',
];

// ── vendor_public.css ────────────────────────────────────────────────────────
const publicCss = [
    'bootstrap/dist/css/bootstrap.min.css',
    'summernote/dist/summernote.css',
    'toastr/build/toastr.min.css',
    'dropzone/dist/min/dropzone.min.css',
    'pickadate/lib/themes/classic.css',
    'pickadate/lib/themes/classic.time.css',
    'pickadate/lib/themes/classic.date.css',
    'font-awesome/css/font-awesome.min.css',
];

console.log('Building vendor bundles from npm packages...');
write('public/js/vendor_admin.js',   concat(adminJs));
write('public/js/vendor_public.js',  concat(publicJs));
write('public/css/vendor_admin.css', concat(adminCss));
write('public/css/vendor_public.css',concat(publicCss));
console.log('Done.');
