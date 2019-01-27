<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use DatabaseMigrations;

    public function testLogin()
    {
        $user = factory(User::class)->create([
            'password' => bcrypt('111111'),
            'email_verified_at' => now()->toDateTimeString(),
        ]);

        $this->get(route('login'))
            ->assertOk();

        $r = $this->post(route('login'), [
            'email' => $user->email,
            'password' => '111111',
            'screen' => '1980x1000',
        ])->assertRedirect('/');

        $this->assertAuthenticatedAs($user);

        $this->assertDatabaseHas('logins',[
            'screen' => '1980x1000',
            'user_id' => $user->id,
            'ip' => '127.0.0.1',
            ] );
        
    }

    public function testUserCanRegister()
    {
        Notification::fake();

        $this->get(route('register'))
            ->assertOk();

        $this->post(route('register'), [
            'name' => 'MyName',
            'email' => 'example@example.com',
            'password' => '1234567',
            'password_confirmation' => '1234567'
        ])
            ->assertRedirect(route('register.success'))
            ->assertSee(route('main'));


        $currentUser = User::find(1);
        Notification::assertSentTo($currentUser, VerifyEmail::class);
    }

    public function testUserCanNotRegisterWithEqualEmail()
    {
        $user = factory(User::class)->create([
            'email' => 'example@example.com'
        ]);

        $this->get(route('register'))
            ->assertOk();

        $response = $this->post(route('register'), [
            'name' => 'MyName',
            'email' => 'example@example.com',
            'password' => '1234567',
            'password_confirmation' => '1234567'
        ]);

        $response->assertRedirect(route('register'))
            ->assertSessionHasErrors([
                'email' => 'The email has already been taken.'
            ]);

    }

    public function testUserCanNotRegisterWithEqualName()
    {
        $user = factory(User::class)->create([
            'name' => 'Example'
        ]);

        $this->get(route('register'))
            ->assertOk();

        $response = $this->post(route('register'), [
            'name' => 'Example',
            'email' => 'example@example.com',
            'password' => '1234567',
            'password_confirmation' => '1234567'
        ]);

        $response->assertRedirect(route('register'))
            ->assertSessionHasErrors([
                'name' => 'The name has already been taken.'
            ]);

    }

    public function testAfterRegisterUserNoLoginAutomatically()
    {
        $this->get(route('register'))
            ->assertOk();

        $this->post(route('register'), [
            'name' => 'MyName',
            'email' => 'example@example.com',
            'password' => '1234567',
            'password_confirmation' => '1234567'
        ])->assertRedirect(route('register.success'))
            ->assertSee(route('main'));

        $this->get(route('login'))->assertOk();
        $this->get(route('profile.about-me'))->assertRedirect(route('login'));

    }
}
