<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Str;

class AuthenticationTest extends TestCase
{
    /**
     * Tests whole authentication process.
     *
     * @return void
     */
    public function test_authentication()
    {
        $passwordSample = 'Testtest1';
        $user = User::factory()->make();

        $this->test_registration($user, $passwordSample);
        $this->test_failed_registration();
        $this->test_login($user->email, $passwordSample);
        $this->test_failed_login($user->email, $passwordSample);
    }

    /**
     * Tests a case when user registration should be successful
     * @param User $user
     * @param str $password
     * 
     * @return void
     */
    private function test_registration($user, $password)
    {
        $response = $this->putJson('/api/register', [
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'password' => $password,
            'repeated_password' => $password
        ]);
        $response->assertStatus(200);
        
        $this->assertDatabaseHas('users', [
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email
        ]);
    }

    /** Tests a case when user registration should fail the validation
     * @return void
     */
    private function test_failed_registration()
    {
        $response = $this->putJson('/api/register', [
            'first_name' => Str::random(31),
            'last_name' => Str::random(31),
            'email' => Str::random(10),
            'password' => Str::random(7),
            'repeated_password' => Str::random(7)
        ]);
        $response->assertStatus(422);
    }

    /**
     * Tests a case when user successfully logs in
     * @param str $email
     * @param str $password
     * 
     * @return void
     */
    private function test_login($email, $password)
    {
        $response = $this->postJson('/api/login', [
            'email' => $email,
            'password' => $password,
            'device' => 'phone'
        ]);
        
        $response->assertStatus(200);
        $response->assertJsonStructure(['token']);
    }

    /**
     * Test case of a failed user login
     * @param str $email
     * @param str $password
     * 
     * @return void
     */
    private function test_failed_login($email, $password)
    {
        $response = $this->postJson('/api/login', [
            'email' => $email . '1',
            'password' => $password . '1',
            'device' => 'phone'
        ]);
        
        $response->assertStatus(422);
    }
}
