<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:155',
            'body' => 'required|string',
            'slug' => 'required|string|max:155|unique:posts',
            'is_published' => 'required|boolean',
            'published_at' => 'nullable|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'meta_title' => 'nullable|string|max:70',
            'meta_description' => 'nullable|string|max:155',
            'meta_keywords' => 'nullable|string|max:155',
            'author' => 'nullable|string|max:155',
            'source' => 'nullable|string|max:155',
            'category' => 'nullable|string|max:155',
            'tags' => 'nullable|string|max:155',
            'status' => 'required|string|in:draft,published,archived',
            'visibility' => 'required|string|in:public,private,unlisted',
            'user_id' => 'required|exists:users,id',
        ]);

        $post = Post::create($request->all());

        return response()->json([
            'message' => 'Post created successfully',
            'post' => $post,
        ]);
    }
    public function update(Request $request, $id, Post $post,)
    {
        try {
            $user = Auth::user();
            $post = Post::find($id);
            Log::info('Update attempt details', [
                'authenticated_user_id' => $user ? $user->id : null,
                'post_user_id' => $post->user_id,
                'post_id' => $post->id,
                'request_data' => $request->all(),
                'auth_check' => Auth::check(),
                'token' => $request->bearerToken()
            ]);

            $this->authorize('update', $post);
            if (!$user) {
                Log::error('User not authenticated');
                return response()->json(['error' => 'Unauthorized - User not authenticated'], 401);
            }
            // dd($user->id, $request->user_id);
            if ($user->id !== $request->user_id) {
                Log::error('User does not own the post', [
                    'user_id' => $user->id,
                    'post_user_id' => $post->user_id,
                    'post' => $post->toArray()
                ]);
                return response()->json(['error' => 'Forbidden - You do not own this post'], 403);
            }

            $post->update($request->all());

            return response()->json([
                'message' => 'Post updated successfully',
                'post' => $post,
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating post', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'An error occurred while updating the post'], 500);
        }
    }
}
