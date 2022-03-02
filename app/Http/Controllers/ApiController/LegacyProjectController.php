<?php

namespace App\Http\Controllers\ApiController;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

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
     * @param Request $request
     * @return JsonResponse
     */
    public function show(Request $request)
    {
        $data = $request->validate(['show' => ['regex:/^([0-9]+|latest)$/']]);

        $filter = Arr::has($data, 'show') == null ? 9999 : $data['show'];
        if ($filter == 'latest') {
            $filter = 1;
        }

        $project = $request->project;

        $updates = $project->updates()
            ->where('public', true)
            ->orderBy('created_at', 'desc')
            ->limit($filter)
            ->get();

        $updates = $updates->map(function ($update) use ($project, $request) {
            return [
                'version' => $update->version,
                'download' => url(sprintf('/api/v1/updates/download/?project=%s&key=%s&version=%s', $project->name, $request->key, $update->version)),
                'releaseDate' => $update->created_at->toDateTimeString(),
                'critical' => $update->critical == 1,
            ];
        });

        return response()->json(['status' => 200, 'updates' => $updates], 200, [], JSON_UNESCAPED_SLASHES);
    }

    /**
     * Download the specified resource.
     *
     * @param Request $request
     * @return JsonResponse|StreamedResponse
     */
    public function download(Request $request)
    {
        if ($request->version == null) {
            return response()
                ->json(['status' => 400, 'message' => 'No version given!'], 400);
        }
        $project = $request->project;
        $update = $project->updates()->where('version', '=', $request->version)
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
