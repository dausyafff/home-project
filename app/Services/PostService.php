<?php

namespace App\Services;

use App\Models\Post;
use Illuminate\Http\Request;

class PostService
{
  public function getAll(Request $request)
  {
    $query = Post::query();

    if ($request->filled('search')) {
      $search = $request->search;
      $query->where(function ($q) use ($search) {
        $q->where('title', 'like', "%{$search}%")
          ->orWhere('excerpt', 'like', "%{$search}%");
      });
    }

    if ($request->filled('status')) {
      $query->where('status', $request->status);
    }

    $sort  = $request->get('sort', 'created_at');
    $order = $request->get('order', 'desc');

    $allowedSorts = ['title', 'created_at', 'published_at', 'status'];
    if (in_array($sort, $allowedSorts)) {
      $query->orderBy($sort, $order === 'asc' ? 'asc' : 'desc');
    }

    $perPage = $request->get('per_page', 6);
    return $query->paginate($perPage);
  }

  public function create(array $data): Post
  {
    return Post::create($data);
  }

  public function update(Post $post, array $data): Post
  {
    $post->update($data);
    return $post->fresh();
  }

  public function delete(Post $post): void
  {
    $post->delete();
  }
}
