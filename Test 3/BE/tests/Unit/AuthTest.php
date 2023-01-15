<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;

use Str;

class AuthTest extends TestCase
{
    public $link, $user;

    public function setUp(): void {
        parent::setUp();
        $this->link = '/api/auth';
    }

    public function testRegister()
    {
        $res = $this->post($this->link.'/register', [
            'name' => Str::random(5) .' '. Str::random(5),
            'email' => Str::random(5). '@gmail.com',
            'password' => 'asdasd123',
        ]);

        $res->assertStatus(200);
    }

    public function testLogin()
    {
        $user = User::orderBy('id', 'DESC')->first();
        $res = $this->post($this->link.'/login', [
            'email' => $user->email,
            'password' => 'asdasd123',
        ]);

        $res->assertStatus(200);
    }
}
