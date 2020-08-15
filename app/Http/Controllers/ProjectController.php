<?php

namespace App\Http\Controllers;

use App\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
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
        $this->middleware('auth.project', ['only' => ['show', 'update', 'destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('dashboard.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // ([A-Z,a-z,\-, ])
        $this->validate($request, ['name' => 'required|unique:projects|min:6|regex:/^([0-9A-Za-z- ])+$/']);

        $project = Project::create([
            'name' => $request->input('name'),
            'user_id' => auth()->user()->id,
            'api_key' => Str::uuid(),
        ]);

        return redirect(route('projects.show', $project));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project)
    {
        return view('dashboard.show', [
            'project' => $project
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Project $project)
    {
        $this->validate($request, ['name' => 'required|unique:projects|min:6|regex:/^([0-9A-Za-z- ])+$/']);
        if (Project::where('name', '=', $request['name'])->count() > 0) {
            return view('dashboard.show', [
                'project' => $project
            ])->with('error', 'Another project with this name exists!');
        }

        if (Storage::exists('updates/' . $project->name))
            Storage::move('updates/' . $project->name, 'updates/' . $request['name']);

        $project->name = $request['name'];

        $project->save();
        return view('dashboard.show', [
            'project' => $project
        ])->with('success', 'Succesfully renamed project!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
        foreach ($project->updates as $update) {
            $update->delete();
        }
        $project->delete();
        return redirect(route('dashboard'));
    }
}
