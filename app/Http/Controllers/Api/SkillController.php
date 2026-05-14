<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Skill;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $skills = Skill::orderBy("order")->get();
        return SkillResource::collection($skills);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'category'   => 'required|in:backend,frontend,devops,other',
            'level'      => 'required|integer|min:1|max:5',
            'icon'       => 'nullable|string',
            'order'      => 'integer',
            'is_visible' => 'boolean',
        ]);

        $skill = Skill::create($validated);
        return new SkillResource($skill);
    }

    /**
     * Display the specified resource.
     */
    public function show(Skill $skill)
    {
        return new SkillResource($skill);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Skill $skill)
    {
        $validated = $request->validate([
            'name'       => 'sometimes|string|max:255',
            'category'   => 'sometimes|in:backend,frontend,devops,other',
            'level'      => 'sometimes|integer|min:1|max:5',
            'icon'       => 'nullable|string',
            'order'      => 'integer',
            'is_visible' => 'boolean',
        ]);

        $skill->update($validated);
        return new SkillResource($skill);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Skill $skill)
    {
        $skill->delete();
        return response()->json(null, 204);
    }
}
