<?php

use App\Models\Project;
use App\Models\User;
use function Pest\Faker\faker;


it('is possible to create a project', function () {
    $user = User::factory()->create();

    $projectName = faker()->regexify('[A-Za-z0-9]{8}');
    $this->actingAs($user)->post(route('projects.store'), [
        'name' => $projectName,
    ])->assertSessionHasNoErrors()
        ->assertRedirect(route('projects.show', ['project' => 1]));

    $this->assertDatabaseHas('projects', ['name' => $projectName, 'user_id' => $user->id]);
});

it('isn\'t possible to create a project when the name has less than 6 characters', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->post(route('projects.store'), [
        'name' => faker()->regexify('[A-Za-z0-9]{4}'),
    ])->assertSessionHasErrors('name');
});

it('isn\'t possible to create a project with a name that has already been taken', function () {
    Project::factory(3)
        ->for(User::factory())->create();

    $project = Project::first();
    $this->actingAs($project->user)->post(route('projects.store'), [
        'name' => $project->name,
    ])->assertSessionHasErrors('name', 'The name has already been taken.');
});

it('isn\'t possible to view someone else\'s a project', function () {
    $users = User::factory(5)
        ->has(Project::factory(5), 'projects')
        ->create();

    $user = $users->first();
    $project = Project::where('user_id', '!=', $user->id)->first();

    $this->actingAs($user)->delete(route('projects.show', ['project' => $project]))
        ->assertNotFound();
});

it('is possible to rename a project', function () {
    $project = Project::factory()
        ->for(User::factory())->create();

    $projectName = faker()->regexify('[A-Za-z0-9]{8}');
    $this->actingAs($project->user)->put(route('projects.update', ['project' => $project]), [
        'name' => $projectName,
    ])->assertSuccessful();

    $this->assertDatabaseHas('projects', ['id' => $project->id, 'name' => $projectName]);
});

it('isn\'t possible to rename a project to an already existing project', function () {
    Project::factory(3)
        ->for(User::factory())->create();

    $project = Project::first();
    $this->actingAs($project->user)->put(route('projects.update', ['project' => $project]), [
        'name' => Project::all()->last()->name,
    ])->assertSessionHasErrors('name', 'The name has already been taken.');
});

it('is possible to delete a project', function () {
    $project = Project::factory()
        ->for(User::factory())->create();

    $this->actingAs($project->user)->delete(route('projects.destroy', ['project' => $project]))
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('dashboard'));

    $this->assertDatabaseMissing('projects', $project->attributesToArray());
});
