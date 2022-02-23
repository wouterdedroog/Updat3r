<?php

use App\Models\Project;
use App\Models\Update;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use function Pest\Faker\faker;


it('is possible to create an update', function () {
    $user = User::factory()
        ->has(Project::factory())
        ->create();

    $this->actingAs($user)->post(route('updates.store', ['project_id' => $user->projects()->first()->id]), [
        'version' => faker()->semver(false, false),
        'public' => faker()->boolean,
        'critical' => faker()->boolean,
        'updatefile' => UploadedFile::fake()->create('plugin.jar'),
    ])->assertSessionHasNoErrors()
        ->assertRedirect(route('projects.show', ['project' => $user->projects()->first()]));
});

it('is possible to edit an update', function () {
    $user = User::factory()
        ->has(Project::factory()->has(Update::factory()))
        ->create();

    $project = $user->projects()->first();
    $this->actingAs($user)->put(route('updates.update', ['update' => $project->updates()->first()]), [
        'changeversion' => faker()->semver(false, false),
        'changecritical' => faker()->boolean,
        'changepublic' => faker()->boolean
    ])->assertSessionHasNoErrors()
        ->assertRedirect(route('projects.show', ['project' => $user->projects()->first()]));
});

it('is possible to delete an update', function () {
    $user = User::factory()
        ->has(Project::factory()->has(Update::factory()))
        ->create();

    $project = $user->projects()->first();
    $this->actingAs($user)->delete(route('updates.destroy', ['update' => $project->updates()->first()]))
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('projects.show', ['project' => $user->projects()->first()]));
});
