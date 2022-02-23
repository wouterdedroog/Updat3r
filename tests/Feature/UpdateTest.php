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

    $version = faker()->semver(false, false);
    $this->actingAs($user)->post(route('projects.updates.store', ['project' => $user->projects()->first()]), [
        'version' => $version,
        'public' => faker()->boolean,
        'critical' => faker()->boolean,
        'updatefile' => UploadedFile::fake()->create('plugin.jar'),
    ])->assertSessionHasNoErrors()
        ->assertRedirect(route('projects.show', ['project' => $user->projects()->first()]));

    $this->assertDatabaseHas('updates', [
        'version' => $version,
        'project_id' => $user->projects()->first()->id,
    ]);
});

it('is possible to edit an update', function () {
    $user = User::factory()
        ->has(Project::factory()->has(Update::factory()))
        ->create();

    $project = $user->projects()->first();
    $version = faker()->semver(false, false);
    $this->actingAs($user)->put(route('projects.updates.update', [
        'project' => $project,
        'update' => $project->updates()->first()
    ]), [
        'changeversion' => $version,
        'changecritical' => faker()->boolean,
        'changepublic' => faker()->boolean
    ])->assertSessionHasNoErrors()
        ->assertRedirect(route('projects.show', ['project' => $user->projects()->first()]));

    $this->assertDatabaseHas('updates', ['project_id' => $project->id, 'version' => $version]);
});

it('is possible to delete an update', function () {
    $user = User::factory()
        ->has(Project::factory()->has(Update::factory()))
        ->create();

    $project = $user->projects()->first();
    $update = $project->updates()->first();
    $this->actingAs($user)->delete(route('projects.updates.destroy', ['project' => $project, 'update' => $update]))
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('projects.show', ['project' => $user->projects()->first()]));

    $this->assertDatabaseMissing('updates', $update->attributesToArray());
});
