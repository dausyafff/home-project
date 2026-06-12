<?php

namespace App\Services;

use App\Models\Skill;
use Illuminate\Http\Request;

class SkillService
{
  public function getAll(Request $request)
  {
    $query = Skill::query();

    if ($request->filled('search')) {
      $query->where('name', 'like', "%{$request->search}%");
    }

    if ($request->filled('category')) {
      $query->where('category', $request->category);
    }

    if ($request->filled('visible')) {
      $query->where('is_visible', $request->boolean('visible'));
    }

    $query->orderBy('order', 'asc');

    $perPage = $request->get('per_page', 10);
    return $query->paginate($perPage);
  }

  public function create(array $data): Skill
  {
    return Skill::create($data);
  }

  public function update(Skill $skill, array $data): Skill
  {
    $skill->update($data);
    return $skill->fresh();
  }

  public function delete(Skill $skill): void
  {
    $skill->delete();
  }
}
