<?php

namespace App\Services;

use App\Repositories\PostRepository;
use Illuminate\Support\Facades\Auth;

class PostService
{
    protected $repo;

    public function __construct(PostRepository $repo)
    {
        $this->repo = $repo;
    }

    public function createPost(array $data)
    {
        $data['user_id'] = Auth::id();
        return $this->repo->create($data);
    }

    public function updatePost($post, array $data)
    {
        return $this->repo->update($post, $data);
    }

    public function deletePost($post)
    {
        return $this->repo->delete($post);
    }
}
