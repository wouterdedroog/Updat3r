<?php

namespace App\Http\Controllers\ApiController;

use App\Http\Controllers\Controller;
use App\Project;
use App\Update;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('api.authorized');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($project, $filter)
    {
        if ($filter == 'latest') {
            $filter = 1;
        } else if (!is_numeric($filter)) {
            return response()
                ->json(['status' => 400, 'message' => 'Invalid filter provided!'], 400);
        }

        $project = Project::where('project_name', '=', $project)->first();

        $retrievedUpdates = Update::where([
            ['project_id', '=', $project->id],
            ['public', '=', 1]
        ])->orderBy('created_at', 'desc')
            ->take($filter)
            ->get();


        $updates = [];
        foreach ($retrievedUpdates as $update) {
            if ($update->public != 1) {
                continue;
            }
            $updates[] = [
                'version' => $update->version,
                'download' => url("/api/v2/updates/download/{$project->project_name}/{$update->version}"),
                'releaseDate' => $update->created_at->toDateTimeString(),
                'critical' => $update->critical == 1,
            ];
        }

        return response()
            ->json(['status' => 200, 'updates' => $updates], 200, [], JSON_UNESCAPED_SLASHES);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function download($project, $version)
    {
        $project = Project::where('project_name', '=', $project)->first();
        $update = Update::where([
            ['version', '=', $version],
            ['project_id', '=', $project->id],
        ])->first();
        if ($update == null) {
            return response()
                ->json(['status' => 400, 'message' => 'Invalid version provided!'], 400);
        }

        $path = Storage::path('updates/' . $project->project_name . '/' . $update->filename);
        if (file_exists($path)) {
            return Storage::download('updates/' . $project->project_name . '/' . $update->filename);
        } else {
            return $path;
        }
    }
}
