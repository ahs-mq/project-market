<?php

namespace App\Http\Controllers;

use App\Models\project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;


class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = project::all();

        return ['projects' => $projects];
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $newProject = $request->validate([
            'title' => ['required', 'min:5', 'max:10'],
            'address' => ['required'],
            'description' => ['required'],
            'tags' => ['nullable']
        ]);

        $project = Project::create(Arr::except($newProject, ['tags']));

        // $project = $request->user()->projects()->create(Arr::except($newProject, 'tags'));

        // if ($newProject['tags']) {
        //     foreach (explode(',', $newProject['tags']) as $tag) {
        //         $project->tag($tag);
        //     }
        // }


        return ['project' => $project];
    }

    /**
     * Display the specified resource.
     */
    public function show(project $project)
    {
        return ['project' => $project];
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, project $project)
    {
        $req = $request->validate([
            'title' => ['required', 'min:5', 'max:10'],
            'address' => ['required'],
            'description' => ['required'],
            'tags' => ['nullable']
        ]);

        $project->update($req);

        return ['project' => $project];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(project $project)
    {
        $project->delete();

        return ['message' => 'Project Deleted'];
    }
}
