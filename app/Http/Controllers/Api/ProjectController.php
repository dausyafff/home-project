<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectResource;
use App\Http\Traits\ApiResponse;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Services\ProjectService;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     */
    public function __construct(private ProjectService $projectService) {}

    public function index(Request $request)
    {
        $projects = $this->projectService->getAll($request);

        return $this->success(
            ProjectResource::collection($projects)->response()->getData(true),
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
            "thumbnail" => "nullable|image|mimes:jpg,jpeg,png,webp|max:2048",
            "status" => "required|in:active,archived",
            "is_featured" => "required|boolean",
        ]);

        $validated["slug"] = Str::slug($validated["title"]);

        if ($request->hasFile("thumbnail")) {
            $path = $request->file("thumbnail")->store("projects", "public");
            $validated["thumbnail"] = $path;
        }

        $project = $this->projectService->create($validated);

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
            "thumbnail" => "nullable|image|mimes:jpg,jpeg,png,webp|max:2048",
            "status" => "in:active,archived",
            "is_featured" => "boolean",
        ]);

        if (isset($validated["title"])) {
            $validated["slug"] = Str::slug($validated["title"]);
        }
        if ($request->hasFile('thumbnail')) {
            // Hapus thumbnail lama supaya tidak menumpuk file tak terpakai
            if ($project->thumbnail) {
                Storage::disk('public')->delete($project->thumbnail);
            }
            $path = $request->file('thumbnail')->store('projects', 'public');
            $validated['thumbnail'] = $path;
        }

        $this->projectService->update($project, $validated);

        return $this->success(new ProjectResource($project), 'Project updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        if ($project->thumbnail) {
            Storage::disk('public')->delete($project->thumbnail);
        }

        $this->projectService->delete($project);
        return $this->success(null, 'Project deleted successfully');
    }
}
