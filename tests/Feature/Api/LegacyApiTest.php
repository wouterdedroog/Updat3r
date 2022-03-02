<?php

use App\Models\Project;
use App\Models\Update;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use function Pest\Faker\faker;

it('is possible to get a list of updates for a project', function () {
    $user = User::factory()
        ->has(Project::factory()
            ->has(Update::factory(10)->public())
        )->create();
    $project = $user->projects()->first();

    $this->get(sprintf('/api/v1/updates/?project=%s&key=%s', $project->name, $project->legacy_api_key))
        ->assertSuccessful()
        ->assertJsonCount(10, 'updates')
        ->assertJsonStructure([
            'updates' => [
                '*' => [
                    'version',
                    'download',
                    'releaseDate',
                    'critical',
                ],
            ],
        ]);
});

it('isn\'t possible to see updates that aren\'t public', function () {
    $user = User::factory()
        ->has(Project::factory()
            ->has(Update::factory(10))
        )->create();
    $project = $user->projects()->first();

    $this->get(sprintf('/api/v1/updates/?project=%s&key=%s', $project->name, $project->legacy_api_key))
        ->assertSuccessful()
        ->assertJsonCount($project->updates()->where('public', true)->count(), 'updates');
});

it('isn\'t possible to get a list of updates with an invalid API key', function () {
    $user = User::factory()
        ->has(Project::factory()
            ->has(Update::factory(10)->public())
        )->create();
    $project = $user->projects()->first();

    $this->get(sprintf('/api/v1/updates/?project=%s&key=%s', $project->name, 'abc'))
        ->assertStatus(400)
        ->assertJsonFragment([
            'status' => '400',
            'message' => 'Invalid API key provided!',
        ]);
});

it('is possible to download an update', function () {
    $user = User::factory()
        ->has(Project::factory()
            ->has(Update::factory(10)->public())
        )->create();
    $project = $user->projects()->first();
    $update = $project->updates()->first();

    // Upload a dummy file to test downloading
    $fileName = sprintf('%s.%s', $update->version, faker()->fileExtension());
    UploadedFile::fake()->create($fileName)->storeAs('updates/' . $project->name, $fileName);
    $update->update(['filename' => $fileName]);

    $this->get(sprintf('/api/v1/updates/download/?project=%s&key=%s&version=%s', $project->name, $project->legacy_api_key, $update->version))
        ->assertSuccessful()
        ->assertDownload($update->fresh()->filename);
});

test('an error is thrown when the update file isn\'t found', function () {
    $user = User::factory()
        ->has(Project::factory()
            ->has(Update::factory()->public())
        )->create();
    $project = $user->projects()->first();
    $update = $project->updates()->first();

    $this->get(sprintf('/api/v1/updates/download/?project=%s&key=%s&version=%s', $project->name, $project->legacy_api_key, $update->version))
        ->assertStatus(400)
        ->assertJson([
            'status' => '400',
            'message' => 'Update file not found!',
        ]);
});

it('isn\'t possible to download a version that doesn\'t exist', function () {
    $user = User::factory()
        ->has(Project::factory())
        ->create();
    $project = $user->projects()->first();

    $this->get(sprintf('/api/v1/updates/download/?project=%s&key=%s&version=%s', $project->name, $project->legacy_api_key, faker()->semver(false, false)))
        ->assertStatus(400)
        ->assertJsonStructure(['status', 'message']);
});
