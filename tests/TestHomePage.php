<?php

class TestHomePage extends TestCase
{
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testBasicExample(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }
}
