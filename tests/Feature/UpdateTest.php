<?php

use App\Models\Project;
use App\Models\Update;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use function Pest\Faker\faker;


it('is possible to create an update', function () {
    $user = User::factory()
        ->has(Project::factory())
        ->create();
    $project = $user->projects()->first();

    $updateData = [
        'version' => faker()->semver(false, false),
        'critical' => faker()->boolean ? '1' : '0',
        'public' => faker()->boolean ? '1' : '0',
        'updatefile' => UploadedFile::fake()->create('plugin.jar'),
    ];

    $this->actingAs($user)->post(route('projects.updates.store', ['project' => $project]), $updateData)
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('projects.show', ['project' => $user->projects()->first()]));

    $this->assertDatabaseHas('updates', collect($updateData)
        ->except(['updatefile'])
        ->put('project_id', $project->id)
        ->toArray());

    $this->assertFileExists(storage_path('app/updates/' . $project->name . '/' . $updateData['version'] . '.jar'));
});

it('is possible to edit an update', function () {
    $user = User::factory()
        ->has(Project::factory()->has(Update::factory()))
        ->create();

    $project = $user->projects()->first();
    $updateData = [
        'version' => faker()->semver(false, false),
        'critical' => faker()->boolean ? '1' : '0',
        'public' => faker()->boolean ? '1' : '0'
    ];

    $this->actingAs($user)->put(route('projects.updates.update', [
        'project' => $project,
        'update' => $project->updates()->first()
    ]), $updateData)
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('projects.show', ['project' => $user->projects()->first()]));

    $this->assertDatabaseHas('updates', collect($updateData)->put('project_id', $project->id)->toArray());
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

it('is impossible to create an updates with an already existing version', function () {
    $user = User::factory()
        ->has(Project::factory()->has(Update::factory()))
        ->create();

    $project = $user->projects()->first();

    $this->actingAs($user)->post(route('projects.updates.store', [
        'project' => $project
    ]), [
        'version' => $project->updates->first()->version,
        'critical' => '1',
        'public' => '1',
        'updatefile' => UploadedFile::fake()->create('plugin.jar'),
    ])->assertSessionHasErrors(['version' => 'The version has already been taken.']);
});

it('is impossible to change an updates to an already existing version', function () {
    $user = User::factory()
        ->has(Project::factory()->has(Update::factory(2)))
        ->create();

    $project = $user->projects()->first();

    $this->actingAs($user)->put(route('projects.updates.update', [
        'project' => $project,
        'update' => $project->updates->first()
    ]), [
        'version' => $project->updates->last()->version,
        'critical' => '1',
        'public' => '1'
    ])->assertSessionHasErrors(['version' => 'The version has already been taken.']);
});

it('is possible to have duplicate versions between different projects when you create an update', function () {
    $user = User::factory()
        ->has(Project::factory(2)->has(Update::factory()))
        ->create();

    $firstProject = $user->projects->first();
    $secondProject = $user->projects->last();

    $this->actingAs($user)->post(route('projects.updates.store', [
        'project' => $firstProject
    ]), [
        'version' => $secondProject->updates->first()->version,
        'critical' => '1',
        'public' => '1',
        'updatefile' => UploadedFile::fake()->create('plugin.jar'),
    ])->assertSessionHasNoErrors();
});

it('is possible to have duplicate versions between different projects when you change an update', function () {
    $user = User::factory()
        ->has(Project::factory(2)->has(Update::factory()))
        ->create();

    $firstProject = $user->projects->first();
    $secondProject = $user->projects->last();

    $this->actingAs($user)->put(route('projects.updates.update', [
        'project' => $firstProject,
        'update' => $firstProject->updates->first()
    ]), [
        'version' => $secondProject->updates->first()->version,
        'critical' => '1',
        'public' => '1'
    ])->assertSessionHasNoErrors();
});

