<?php

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use function Pest\Faker\faker;

it('is possible to change your name and email', function () {
    $user = User::factory()->create();

    $userData = ['name' => faker()->name(), 'email' => faker()->email()];
    $this->actingAs($user)->put(route('users.update', ['user' => $user]), $userData)
        ->assertRedirect(route('users.show', ['user' => $user]))
        ->assertSessionHasNoErrors();

    expect($user->fresh()->attributesToArray())->toMatchArray($userData);
});

it('is impossible to change your and email to an already existing email', function () {
    $users = User::factory(2)->create();
    $firstUser = $users->first();
    $secondUser = $users->last();

    $userData = ['name' => $firstUser->name, 'email' => $secondUser->email];
    $this->actingAs($firstUser)->put(route('users.update', ['user' => $firstUser]), $userData)
        ->assertSessionHasErrors(['email' => 'The email has already been taken.']);
});

it('is possible to change your password', function () {
    $user = User::factory()->create();
    $user->update(['password' => Hash::make('password')]);

    $userData = [
        'name' => $user->name,
        'email' => $user->email,
        'current_password' => 'password',
        'password' => 'Welkom01!',
        'password_confirmation' => 'Welkom01!'
    ];
    $this->actingAs($user)->put(route('users.update', ['user' => $user]), $userData)
        ->assertRedirect(route('users.show', ['user' => $user]))
        ->assertSessionHasNoErrors();

    expect(Hash::check('Welkom01!', $user->fresh()->password))->toBeTrue();
});

it('is possible to delete your account', function () {
    $user = User::factory()
        ->create();

    $this->actingAs($user)->delete(route('users.destroy', ['user' => $user]))
        ->assertRedirect(route('login'))
        ->assertSessionHasNoErrors();

    expect(User::find($user->id))->toBeNull();
});

test('deleting your account will delete all data', function () {
    $user = User::factory()
        ->has(Project::factory())
        ->create();
    $project = $user->projects()->first();

    // Release an update under this project
    $this->actingAs($user)
        ->post(route('projects.updates.store', ['project' => $project]), [
            'version' => faker()->semver(false, false),
            'critical' => faker()->boolean() ? '1' : '0',
            'public' => faker()->boolean() ? '1' : '0',
            'updatefile' => UploadedFile::fake()->create('plugin.jar'),
        ])->assertSessionHasNoErrors();
    expect(Storage::exists('updates/' . $project->name))->toBeTrue();

    $this->actingAs($user)->delete(route('users.destroy', ['user' => $user]))
        ->assertRedirect(route('login'))
        ->assertSessionHasNoErrors();

    expect(User::find($user->id))->toBeNull();
    expect(Project::find($project->id))->toBeNull();
    expect(Storage::exists('updates/' . $project->name))->toBeFalse();
});
