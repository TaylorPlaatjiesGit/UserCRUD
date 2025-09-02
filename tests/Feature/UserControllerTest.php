<?php

namespace Tests\Feature;

use App\Models\Interest;
use App\Models\Language;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_access_store_update_delete_routes(): void
    {
        $user = User::factory()->create();

        $this->post(route('users.store'))->assertRedirect('/login');
        $this->put(route('users.put', $user))->assertRedirect('/login');
        $this->delete(route('users.delete', $user))->assertRedirect('/login');
    }

    public function test_authenticated_user_can_store_a_user(): void
    {
        $this->actingAs(User::factory()->create());

        $interests = Interest::factory()->count(2)->create();
        $language = Language::factory()->create();

        $payload = [
            'email' => 'test@example.com',
            'name' => 'Test',
            'surname' => 'User',
            'id_number' => '1234567891011',
            'mobile_number' => '0783383338',
            'birth_date' => '1993/02/02',
            'language_id' => $language->id,
            'interests' => $interests->pluck('id')->toArray(),
        ];

        $response = $this->post(route('users.store'), $payload);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'User stored successfully.');

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'name' => 'Test',
            'surname' => 'User',
        ]);

        $user = User::where('email', 'test@example.com')->first();

        // Check interests synced
        $this->assertEqualsCanonicalizing($interests->pluck('id')->toArray(), $user->interests->pluck('id')->toArray());
    }

    public function test_storing_user_fails_with_invalid_data(): void
    {
        $this->actingAs(User::factory()->create());

        $payload = [
            'name' => '',
            'surname' => '',
            'email' => 'not-an-email',
            'interests' => 'should-be-array',
        ];

        $response = $this->post(route('users.store'), $payload);

        $response->assertSessionHasErrors(['name', 'surname', 'email', 'interests']);

        $this->assertDatabaseMissing('users', ['email' => 'not-an-email']);
    }

    public function test_authenticated_user_can_update_an_existing_user(): void
    {
        $this->actingAs(User::factory()->create());

        $userToUpdate = User::factory()->create();

        $interests = Interest::factory()->count(2)->create();
        $language = Language::factory()->create();

        $payload = [
            'name' => 'Updated',
            'surname' => 'Name',
            'email' => 'updated@example.com',
            'id_number' => '1234567891011',
            'mobile_number' => '0783383338',
            'birth_date' => '1993/02/02',
            'language_id' => $language->id,
            'interests' => $interests->pluck('id')->toArray(),
        ];

        $response = $this->put(route('users.put', $userToUpdate), $payload);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'User updated successfully.');

        $userToUpdate->refresh();

        $this->assertEquals('Updated', $userToUpdate->name);
        $this->assertEquals('Name', $userToUpdate->surname);
        $this->assertEquals('updated@example.com', $userToUpdate->email);
        $this->assertEqualsCanonicalizing($interests->pluck('id')->toArray(), $userToUpdate->interests->pluck('id')->toArray());
    }

    public function test_authenticated_user_can_delete_a_user(): void
    {
        $this->actingAs(User::factory()->create());

        $userToDelete = User::factory()->create();
        $interests = Interest::factory()->count(2)->create();
        Language::factory()->count(2)->create();

        $userToDelete->interests()->sync($interests->pluck('id')->toArray());

        $response = $this->delete(route('users.delete', $userToDelete));

        $response->assertRedirect();
        $response->assertSessionHas('success', 'User deleted successfully.');

        $this->assertDatabaseMissing('users', ['id' => $userToDelete->id]);
        $this->assertDatabaseMissing('interest_user', ['user_id' => $userToDelete->id]);
    }
}
