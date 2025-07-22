<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    // tests/TestCase.php
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->artisan('migrate');
        $this->app->register(\App\Providers\TestServiceProvider::class);
    }

}
