<?php

namespace App\Http\Controllers\ApiController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Project;
use App\Update;
use Illuminate\Support\Facades\Storage;

class LegacyProjectController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('api.legacyauthorized');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $filter = $request->show == null ? 9999 : $request->show;
        if ($filter == 'latest') {
            $filter = 1;
        } else if (!is_numeric($filter)) {
            return response()
                ->json(['status' => 400, 'message' => 'Invalid filter provided!'], 400);
        }

        $project = Project::where('name', '=', $request->project)->first();

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
                'download' => url("/api/v2/updates/download/{$project->name}/{$update->version}"),
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
    public function download(Request $request)
    {
        if ($request->version == null) {
            return response()
                ->json(['status' => 400, 'message' => 'No version given!'], 400);
        }
        $project = Project::where('name', '=', $request->project)->first();
        $update = Update::where([
            ['version', '=', $request->version],
            ['project_id', '=', $project->id],
        ])->first();
        if ($update == null) {
            return response()
                ->json(['status' => 400, 'message' => 'Invalid version provided!'], 400);
        }

        $path = Storage::path('updates/' . $project->name . '/' . $update->filename);
        if (file_exists($path)) {
            return Storage::download('updates/' . $project->name . '/' . $update->filename);
        } else {
            return response()
                ->json(['status' => 400, 'message' => 'Update file not found!'], 400);
        }
    }
}
