<?php

namespace App\Http\Controllers;

use App\Update;
use App\Project;
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
        $this->middleware('auth.update', ['only' => ['store', 'update', 'destroy']]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, ['project_id' => 'required', 'version' => 'required|regex:/^([0-9A-Za-z-\. ])+$/', 'critical' => 'required', 'public' => 'required', 'updatefile' => 'required|file|max:2000']);

        $project = Project::find($request->get('project_id'));
        foreach ($project->updates as $update) {
            if ($update->version == $request->get('version')) {
                return redirect(route('projects.show', Project::find($request->get('project_id'))))->with('error', 'You already have a version with this name.');
            }
        }

        $extension = $request->file('updatefile')->getClientOriginalExtension();

        $path = $request->file('updatefile')->storeAs('updates/'.$project->project_name, $request->get('version').'.'.$extension);

        $update = new Update;
        
        $update->project_id = $request->get('project_id');
        $update->version = $request->get('version');
        $update->critical = $request->get('critical') == 'true' ? 1 : 0;
        $update->public = $request->get('public') == 'true' ? 1 : 0;
        $update->filename = $request->get('version').'.'.$extension;
        $update->save();

        return redirect(route('projects.show', Project::find($request->get('project_id'))));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Update $update)
    {
        $this->validate($request, ['changeversion' => 'required|regex:/^([0-9A-Za-z-\. ])+$/', 'changecritical' => 'required', 'changepublic' => 'required']);
        
        $update->version = $request->get('changeversion');
        $update->critical = $request->get('changecritical') == 'true' ? 1 : 0;
        $update->public = $request->get('changepublic') == 'true' ? 1 : 0;

        $update->save();
        return view('dashboard.show', [
            'project' => $update->project
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(Update $update)
    {
        $update->delete();
        return redirect(route('projects.show', $update->project));
    }
}
