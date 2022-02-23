<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUpdateRequest;
use App\Http\Requests\UpdateUpdateRequest;
use App\Models\Update;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;

class UpdateController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth.project');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreUpdateRequest $request
     * @param Project $project
     * @return RedirectResponse
     */
    public function store(StoreUpdateRequest $request, Project $project)
    {
        $data = $request->validated();
        $extension = $request->file('updatefile')->getClientOriginalExtension();

        $fileName = sprintf('%s.%s', $data['version'], $extension);
        $request->file('updatefile')->storeAs('updates/' . $project->name, $fileName);

        $project->updates()->create(collect($data)->except(['updatefile'])->put('filename', $fileName)->toArray());

        return redirect(route('projects.show', $project));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateUpdateRequest $request
     * @param Project $project
     * @param Update $update
     * @return RedirectResponse
     */
    public function update(UpdateUpdateRequest $request, Project $project, Update $update)
    {
        $update->update($request->validated());

        return redirect(route('projects.show', [
            'project' => $project
        ]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Project $project
     * @param Update $update
     * @return RedirectResponse
     */
    public function destroy(Project $project, Update $update)
    {
        $update->delete();
        return redirect(route('projects.show', $project));
    }
}
