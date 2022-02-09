<?php

namespace App\Http\Controllers;

use App\Models\Update;
use App\Models\Project;
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
        $this->middleware('auth.update')->only(['store', 'update', 'destroy']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'project_id' => 'required', 'version' => 'required|regex:/^([0-9A-Za-z-\. ])+$/',
            'critical' => 'required', 'public' => 'required', 'updatefile' => 'required|file|max:2000'
        ]);

        $project = $request->project;
        if ($project->updates->pluck('version')->contains($request['version'])) {
            return redirect(route('projects.show', $project))->with('error', 'You already have a version with this name.');
        }

        $extension = $request->file('updatefile')->getClientOriginalExtension();

        $fileName = sprintf('%s.%s', $request['version'], $extension);
        $path = $request->file('updatefile')->storeAs('updates/' . $project->name, $fileName);

        Update::create([
            'project_id' => $request['project_id'],
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
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Update $update)
    {
        $this->validate($request, ['changeversion' => 'required|regex:/^([0-9A-Za-z-\. ])+$/', 'changecritical' => 'required', 'changepublic' => 'required']);

        $update->update([
            'version' => $request['changeversion'],
            'critical' => $request['changecritical'] == 'true' ? 1 : 0,
            'public' => $request['changepublic'] == 'true' ? 1 : 0,
        ]);

        return redirect(route('projects.show', [
            'project' => $update->project
        ]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(Update $update)
    {
        $update->delete();
        return redirect(route('projects.show', $update->project));
    }
}
