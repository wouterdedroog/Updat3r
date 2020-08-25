<?php

namespace App\Http\Controllers\ApiController;

use App\Http\Controllers\Controller;
use App\Project;
use App\Update;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

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
    public function show(Request $request, $project, $filter)
    {
        if ($filter == 'latest') {
            $filter = 1;
        } 

        $project = $request->project;

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
                'download' => url(sprintf('/api/v2/updates/download/%s/%s', $project->name, $update->version)),
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
    public function download(Request $request, $project, $version)
    {
        $project = $request->project;
        $update = Update::where([
            ['version', '=', $version],
            ['project_id', '=', $project->id],
        ])->first();
        if ($update == null) {
            return response()
                ->json(['status' => 400, 'message' => 'Invalid version provided!'], 400);
        }

        $path = 'updates/' . $project->name . '/' . $update->filename;
        if (file_exists(Storage::path($path))) {
            return Storage::download($path);
        } else {
            return response()
                ->json(['status' => 400, 'message' => 'Update file not found!'], 400);
        }
    }
}
