<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Laravel\Sanctum\Sanctum;

abstract class TestCase extends BaseTestCase
{
    protected function actingAsAdmin()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        Sanctum::actingAs($admin);
        return $admin;
    }

    protected function actingAsUser()
    {
        $user = User::factory()->create();
        $user->assignRole('user');
        Sanctum::actingAs($user);
        return $user;
    }
}
