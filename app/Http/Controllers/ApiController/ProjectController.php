<?php

namespace App\Http\Controllers\ApiController;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Update;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

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
     * @param Request $request
     * @param Project $project
     * @param $filter
     * @return JsonResponse
     */
    public function show(Request $request, Project $project, $filter)
    {
        if ($filter == 'latest') {
            $filter = 1;
        }

        $updates = $project->updates()
            ->where('public', true)
            ->orderBy('created_at', 'desc')
            ->limit($filter)
            ->get()
            ->map(function ($update) use ($project, $request) {
                return [
                    'version' => $update->version,
                    'download' => url(sprintf('/api/v2/updates/download/%s/%s', $project->name, $update->version)),
                    'releaseDate' => $update->created_at->toDateTimeString(),
                    'critical' => $update->critical == 1,
                ];
            });

        return response()->json(['status' => 200, 'updates' => $updates], 200, [], JSON_UNESCAPED_SLASHES);
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param Project $project
     * @param $version
     * @return JsonResponse|StreamedResponse
     */
    public function download(Request $request, Project $project, $version)
    {
        $project = $request->project;
        $update = $project->updates()
            ->where('version', $version)
            ->where('public', true)
            ->first();

        if ($update == null) {
            return response()
                ->json(['status' => 400, 'message' => 'Invalid version provided!'], 400);
        }

        $path = 'updates/' . $project->name . '/' . $update->filename;
        if (!Storage::exists($path)) {
            return response()
                ->json(['status' => 400, 'message' => 'Update file not found!'], 400);
        }
        return Storage::download($path);
    }
}
