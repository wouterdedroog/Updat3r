<?php

namespace App\Http\Controllers;

use App\Models\Update;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UpdateController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth.project')->only(['store', 'update', 'destroy']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param Project $project
     * @return RedirectResponse
     */
    public function store(Request $request, Project $project)
    {
        $this->validate($request, [
            'version' => 'required|regex:/^([0-9A-Za-z-\. ])+$/',
            'critical' => 'required',
            'public' => 'required',
            'updatefile' => 'required|file|max:2000'
        ]);

        if ($project->updates->pluck('version')->contains($request['version'])) {
            return redirect(route('projects.show', $project))->with('error', 'You already have a version with this name.');
        }

        $extension = $request->file('updatefile')->getClientOriginalExtension();

        $fileName = sprintf('%s.%s', $request['version'], $extension);
        $request->file('updatefile')->storeAs('updates/' . $project->name, $fileName);

        $project->updates()->create([
            'version' => $request['version'],
            'critical' => $request['critical'] == 'true' ? 1 : 0,
            'public' => $request['public'] == 'true' ? 1 : 0,
            'filename' => $request['version'] . '.' . $extension,
        ]);

        return redirect(route('projects.show', $project));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Project $project
     * @param Update $update
     * @return RedirectResponse
     */
    public function update(Request $request, Project $project, Update $update)
    {
        $this->validate($request, [
            'changeversion' => 'required|regex:/^([0-9A-Za-z-\. ])+$/',
            'changecritical' => 'required',
            'changepublic' => 'required']
        );

        $update->update([
            'version' => $request['changeversion'],
            'critical' => $request['changecritical'] == 'true' ? 1 : 0,
            'public' => $request['changepublic'] == 'true' ? 1 : 0,
        ]);

        return redirect(route('projects.show', [
            'project' => $project
        ]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Update $update
     * @return RedirectResponse
     */
    public function destroy(Project $project, Update $update)
    {
        $update->delete();
        return redirect(route('projects.show', $project));
    }
}
