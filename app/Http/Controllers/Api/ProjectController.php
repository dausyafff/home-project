<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectResource;
use App\Http\Traits\ApiResponse;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Project::latest()->get();
        return $this->success(
            ProjectResource::collection($projects),
            'Data project berhasil diambil'
        );
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

        return $this->success(new ProjectResource($project), 'Project created successfully', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        return $this->success(new ProjectResource($project), 'Project data retrieved successfully');
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

        return $this->success(new ProjectResource($project), 'Project updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        $project->delete();
        return $this->success(null, 'Project deleted successfully');
    }
}
