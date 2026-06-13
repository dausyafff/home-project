<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Http\Traits\ApiResponse;
use App\Models\Post;
use App\Services\PostService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     */
    public function __construct(private PostService $postService) {}
    public function index(Request $request)
    {
        $posts = $this->postService->getAll($request);
        return $this->success(PostResource::collection($posts), 'Data post berhasil diambil');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'excerpt'      => 'nullable|string',
            'body'         => 'required|string',
            'thumbnail'    => 'nullable|string',
            'status'       => 'in:draft,published',
            'published_at' => 'nullable|date',
        ]);

        $validated['slug'] = Str::slug($validated['title']);

        $post = $this->postService->create($validated);
        return $this->success(new PostResource($post), 'Post created successfully', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return $this->success(new PostResource($post), 'Post data retrieved successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title'        => 'sometimes|string|max:255',
            'excerpt'      => 'nullable|string',
            'body'         => 'sometimes|string',
            'thumbnail'    => 'nullable|string',
            'status'       => 'in:draft,published',
            'published_at' => 'nullable|date',
        ]);

        if (isset($validated['title'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        $this->postService->update($post, $validated);
        return $this->success(new PostResource($post), 'Post updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $this->postService->delete($post);
        return $this->success(null, 'Post deleted successfully');
    }
}
