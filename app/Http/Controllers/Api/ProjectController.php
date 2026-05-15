<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Project::latest()->get();
        return ProjectResource::collection($projects);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "title" => "required|string|max:255",
            "description" => "required|string",
            "tech_stack" => "nullable|array",
            "github_url" => "nullable|url",
            "live_url" => "nullable|url",
            "thumbnail" => "nullable|string|max:255",
            "status" => "required|in:active,archived",
            "is_featured" => "required|boolean",
        ]);

        $validated["slug"] = Str::slug($validated["title"]);

        $project = Project::create($validated);
        return response()->json(new ProjectResource($project), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        return new ProjectResource($project);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            "title" => "string|max:255",
            "description" => "string",
            "tech_stack" => "nullable|array",
            "github_url" => "nullable|url",
            "live_url" => "nullable|url",
            "thumbnail" => "nullable|string|max:255",
            "status" => "in:active,archived",
            "is_featured" => "boolean",
        ]);

        if (isset($validated["title"])) {
            $validated["slug"] = Str::slug($validated["title"]);
        }

        $project->update($validated);
        return new ProjectResource($project);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        $project->delete();
        return response()->json(["message" => "Project deleted successfully"], 200);
    }
}
