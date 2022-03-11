<?php

use App\Models\Project;
use App\Models\Update;
use App\Models\User;

test('the v1 show api route url is correct')
    ->tap(fn() => User::factory()->create())
    ->tap(fn() => Project::create(['name' => 'test-project', 'api_key' => Str::uuid(), 'user_id' => User::first()->id]))
    ->expect(fn() => route('api.v1.updates.show', ['project' => 'test-project']))
    ->toBe('http://localhost/api/v1/updates?project=test-project');

test('the v1 download api route url is correct')
    ->tap(fn() => User::factory()->create())
    ->tap(fn() => Project::create(['name' => 'test-project', 'api_key' => Str::uuid(), 'user_id' => User::first()->id]))
    ->tap(fn() => Update::create([
        'project_id' => Project::first()->id,
        'version' => '1.0',
        'public' => 1,
        'critical' => 0,
        'filename' => '1.0.jar'
    ]))
    ->expect(fn() => route('api.v1.updates.download', ['project' => 'test-project', 'version' => '1.0']))
    ->toBe('http://localhost/api/v1/updates/download?project=test-project&version=1.0');

test('the v2 show api route url is correct')
    ->tap(fn() => User::factory()->create())
    ->tap(fn() => Project::create(['name' => 'test-project', 'api_key' => Str::uuid(), 'user_id' => User::first()->id]))
    ->expect(fn() => route('api.v2.updates.show', ['project' => 'test-project', 'filter' => 'latest']))
    ->toBe('http://localhost/api/v2/updates/test-project/latest');

test('the v2 download api route url is correct')
    ->tap(fn() => User::factory()->create())
    ->tap(fn() => Project::create(['name' => 'test-project', 'api_key' => Str::uuid(), 'user_id' => User::first()->id]))
    ->tap(fn() => Update::create([
        'project_id' => Project::first()->id,
        'version' => '1.0',
        'public' => 1,
        'critical' => 0,
        'filename' => '1.0.jar'
    ]))
    ->expect(fn() => route('api.v2.updates.download', ['project' => 'test-project', 'version' => '1.0']))
    ->toBe('http://localhost/api/v2/updates/download/test-project/1.0');
