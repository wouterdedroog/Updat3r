<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectRequest;
use App\Models\Project;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
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
        $this->middleware('auth.project')->only(['show', 'update', 'destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index()
    {
        return view('dashboard.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create()
    {
        return view('dashboard.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ProjectRequest $request
     * @return RedirectResponse
     */
    public function store(ProjectRequest $request)
    {
        $data = $request->validated();

        $project = $request->user()->projects()->create([
            'name' => $data['name'],
            'api_key' => Str::uuid(),
        ]);

        return redirect(route('projects.show', $project));
    }

    /**
     * Display the specified resource.
     *
     * @param Project $project
     * @return View
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
     * @param ProjectRequest $request
     * @param Project $project
     * @return View
     */
    public function update(ProjectRequest $request, Project $project)
    {
        $data = $request->validated();

        if (Storage::exists('updates/' . $project->name)) {
            Storage::move('updates/' . $project->name, 'updates/' . $data['name']);
        }

        $project->update($data);

        return view('dashboard.show', [
            'project' => $project
        ])->with('success', 'Successfully renamed project!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Project $project
     * @return RedirectResponse
     */
    public function destroy(Project $project)
    {
        Storage::deleteDirectory('updates/' . $project->name);
        $project->updates()->delete();
        $project->delete();
        return redirect(route('dashboard'));
    }
}
