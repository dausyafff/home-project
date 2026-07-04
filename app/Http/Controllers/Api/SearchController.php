<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponse;
use App\Models\Post;
use App\Models\Project;
use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SearchController extends Controller
{
    use ApiResponse;

    public function __invoke(Request $request)
    {
        // __invoke = method yang dipanggil otomatis saat controller dipakai
        // Cocok untuk controller yang hanya punya 1 fungsi

        $query = $request->get('q', '');

        // Kalau query kosong atau kurang dari 2 karakter — jangan query database
        // Ini mencegah query yang tidak berguna dan membebani database
        if (strlen($query) < 2) {
            return $this->success([
                'projects' => [],
                'skills'   => [],
                'posts'    => [],
            ], 'Ketik minimal 2 karakter');
        }
        // ── Search Projects ───────────────────────────────
        $projects = Project::where('status', 'active')
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%");
            })
            ->limit(5) // maksimal 5 hasil per kategori — cukup untuk search dropdown
            ->get()
            ->map(fn($p) => [
                'id'          => $p->id,
                'type'        => 'project',
                'title'       => $p->title,
                'subtitle'    => $p->description,
                'slug'        => $p->slug,
                'thumbnail'   => $p->thumbnail
                    ? Storage::disk('public')->url($p->thumbnail)
                    : null,
                'url'         => "/projects/{$p->slug}",
                'tech_stack'  => $p->tech_stack,
                'github_url'  => $p->github_url,
                'live_url'    => $p->live_url,
            ]);
        // ── Search Skills ─────────────────────────────────
        $skills = Skill::where('is_visible', true)
            ->where('name', 'like', "%{$query}%")
            ->limit(5)
            ->get()
            ->map(fn($s) => [
                'id'       => $s->id,
                'type'     => 'skill',
                'title'    => $s->name,
                'subtitle' => ucfirst($s->category) . ' · Level ' . $s->level . '/5',
                'url'      => '/projects', // arahkan ke halaman projects
            ]);

        // ── Search Posts ──────────────────────────────────
        $posts = Post::where('status', 'published')
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                    ->orWhere('excerpt', 'like', "%{$query}%");
            })
            ->limit(5)
            ->get()
            ->map(fn($p) => [
                'id'       => $p->id,
                'type'     => 'post',
                'title'    => $p->title,
                'subtitle' => $p->excerpt,
                'slug'     => $p->slug,
                'url'      => "/posts/{$p->slug}",
            ]);
        $totalFound = count($projects) + count($skills) + count($posts);

        return $this->success([
            'query'    => $query,
            'total'    => $totalFound,
            'projects' => $projects,
            'skills'   => $skills,
            'posts'    => $posts,
        ], $totalFound > 0 ? "{$totalFound} hasil ditemukan" : 'Tidak ada hasil');
    }
}
