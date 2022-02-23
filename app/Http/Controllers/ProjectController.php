<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
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
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        $this->validate($request, ['name' => 'required|unique:projects|min:6|regex:/^([0-9A-Za-z- ])+$/']);

        $project = $request->user()->projects()->create([
            'name' => $request->input('name'),
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
     * @param Request $request
     * @param Project $project
     * @return View
     */
    public function update(Request $request, Project $project)
    {
        $data = $this->validate($request, ['name' => 'required|unique:projects|min:6|regex:/^([0-9A-Za-z- ])+$/']);

        if (Storage::exists('updates/' . $project->name)) {
            Storage::move('updates/' . $project->name, 'updates/' . $request['name']);
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
        $project->updates()->delete();
        $project->delete();
        return redirect(route('dashboard'));
    }
}
