<?php

namespace App\Http\Controllers;

use App\Models\project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Arr;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rules\File;

class ProjectController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('auth:sanctum', except: ['index', 'show', 'search']),
            new Middleware('throttle:10,1', only: ['store', 'update', 'destroy']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Project::with(['user:id,name', 'tags'])
            ->whereIn('status', ['pending', 'offer_received'])
            ->get();

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
            'images' => ['nullable', 'array',  File::types(['png', 'jpg', 'webp'])],
            'tags' => ['nullable'],

        ]);

        $project = $request->user()->projects()->create(Arr::except($newProject, 'tags', 'images'));

        if (!empty($newProject['tags'])) {
            foreach (explode(',', $newProject['tags']) as $tag) {
                $project->tag($tag);
            }
        }

        if (!empty($newProject['images'])) {
            foreach ($newProject['images'] as $image) {
                $path = $image->store('projects', 'public');
                $project->images()->create(['url' => $path]);
            }
        }


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
        Gate::authorize('modify', $project);
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
        Gate::authorize('modify', $project);
        $project->delete();

        return ['message' => 'Project Deleted'];
    }

    public function search(Request $request)
    {
        $searchTerm = $request->query('q');

        if (!$searchTerm) {
            return response()->json(['projects' => []], 200);
        }

        $projects = project::with(['user', 'tags'])
            ->where(function ($q) use ($searchTerm) {
                $q->where('title', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('address', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('description', 'LIKE', '%' . $searchTerm . '%');
            })
            ->orWhereHas('tags', function ($q) use ($searchTerm) {
                $q->where('name', 'LIKE', '%' . $searchTerm . '%');
            })
            ->get();

        return response()->json(['projects' => $projects], 200);
    }
}
