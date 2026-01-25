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
        $validated = $request->validate([
            'title' => ['required', 'min:5', 'max:10'],
            'address' => ['required'],
            'description' => ['required'],
            'images' => ['nullable', 'array'],
            'images.*' => [File::types(['png', 'jpg', 'webp', 'jpeg', 'gif'])->max(5120)],
            'tags' => ['nullable'],
        ]);

        $project = $request->user()->projects()->create([
            'title' => $validated['title'],
            'address' => $validated['address'],
            'description' => $validated['description'],
        ]);

        if (!empty($validated['tags'])) {
            foreach (explode(',', $validated['tags']) as $tag) {
                $project->tag($tag);
            }
        }

        if (!empty($validated['images'])) {
            foreach ($validated['images'] as $image) {
                $path = $image->store('photos', 'public');
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
        $validated = $request->validate([
            'title' => ['required', 'min:5', 'max:10'],
            'address' => ['required'],
            'description' => ['required'],
            'tags' => ['nullable'],
            'images' => ['nullable', 'array'],
            'images.*' => [File::types(['png', 'jpg', 'webp', 'jpeg', 'gif'])->max(5120)],
        ]);

        $project->update([
            'title' => $validated['title'],
            'address' => $validated['address'],
            'description' => $validated['description'],
        ]);

        if (!empty($validated['tags'])) {
            // Handle tags update, perhaps detach and attach new ones
            $project->tags()->detach();
            foreach (explode(',', $validated['tags']) as $tag) {
                $project->tag($tag);
            }
        }

        if (!empty($validated['images'])) {
            foreach ($validated['images'] as $image) {
                $path = $image->store('photos', 'public');
                $project->images()->create(['url' => $path]);
            }
        }

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

    public function filter(Request $request)
    {
        $status = $request->query('status');

        $query = $request->user()->projects()->with(['tags', 'user:id,name']);

        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        $projects = $query->get();

        return response()->json(['projects' => $projects], 200);
    }

    public function send_offer(User $user, Project $project)
    {
        // Authorization check
        Gate::authorize('notSameUser', $project);
        // Add logic here to handle the acceptance of the order
        $project->update(['status' => 'offer_received']);
        return response()->json(['message' => 'Offer sent successfully'], 200);
    }
    public function accept(Project $project)
    {

        // Authorization check
        Gate::authorize('modify', $project);
        // Add logic here to handle the acceptance of the order
        $project->update(['status' => 'accepted']);
        return response()->json(['message' => 'Order accepted successfully'], 200);
    }

    public function reject(Project $project)
    {
        // Authorization check
        Gate::authorize('modify', $project);
        // Add logic here to handle the rejection of the order
        if ($project->status !== 'offer_received') {
            return response()->json(['message' => 'Only projects with offer_received status can be rejected'], 422);
        }
        $project->update(['status' => 'rejected']);
        return response()->json(['message' => 'Order rejected successfully'], 200);
    }

    public function complete(Project $project)
    {
        // Authorization check
        Gate::authorize('modify', $project);
        // Add logic here to handle the completion of the order
        $project->update(['status' => 'complete']);
        return response()->json(['message' => 'Order completed successfully'], 200);
    }

    public function cancel(Project $project)
    {
        // Authorization check
        Gate::authorize('modify', $project);
        // Add logic here to handle the cancellation of the order
        $project->update(['status' => 'canceled']);
        return response()->json(['message' => 'Order canceled successfully'], 200);
    }
}
