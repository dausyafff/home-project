<?php

namespace App\Services;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectService
{
  public function getAll(Request $request)
  {
    $query = Project::query();

    // Search — kalau ada parameter ?search=xxx
    if ($request->filled('search')) {
      $search = $request->search;
      $query->where(function ($q) use ($search) {
        $q->where('title', 'like', "%{$search}%")
          ->orWhere('description', 'like', "%{$search}%");
      });
    }

    // Filter by status — ?status=active
    if ($request->filled('status')) {
      $query->where('status', $request->status);
    }

    // Filter featured — ?featured=true
    if ($request->boolean('featured')) {
      $query->where('is_featured', true);
    }

    // Sorting — ?sort=title&order=asc
    $sort  = $request->get('sort', 'created_at');
    $order = $request->get('order', 'desc');

    // Whitelist kolom yang boleh disort — keamanan
    $allowedSorts = ['title', 'created_at', 'status'];
    if (in_array($sort, $allowedSorts)) {
      $query->orderBy($sort, $order === 'asc' ? 'asc' : 'desc');
    }

    // Pagination — ?per_page=10
    $perPage = $request->get('per_page', 6);
    return $query->paginate($perPage);
  }

  public function create(array $data): Project
  {
    return Project::create($data);
  }

  public function update(Project $project, array $data): Project
  {
    $project->update($data);
    return $project->fresh(); // ambil data terbaru dari database
  }

  public function delete(Project $project): void
  {
    $project->delete();
  }
}
