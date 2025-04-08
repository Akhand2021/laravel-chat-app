<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Category;
use App\Services\PostService;
use App\Services\CategoryService;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class PostController extends Controller
{
    protected $service;
    protected $categoryService;

    public function __construct(PostService $service, CategoryService $categoryService)
    {
        $this->middleware('auth');
        $this->service = $service;
        $this->categoryService = $categoryService;
    }

    public function index()
    {
        $posts = Auth::user()->hasRole('admin')
            ? Post::latest()->get()
            : Auth::user()->posts()->latest()->get();

        return view('blog.index', compact('posts'));
    }

    public function show(Post $post)
    {
        $this->authorize('view', $post);
        return view('blog.show', compact('post'));
    }

    public function create()
    {
        $this->authorize('create', Post::class);
        $categories = $this->categoryService->getAllCategories();
        return view('blog.create', compact('categories'));
    }

    public function store(StorePostRequest $request)
    {
        $this->authorize('create', Post::class);
        $this->service->createPost($request->validated());
        return redirect()->route('posts.index')->with('success', 'Post created successfully.');
    }

    public function edit(Post $post)
    {
        $this->authorize('update', $post);
        $categories = $this->categoryService->getAllCategories();
        return view('blog.edit', compact('post', 'categories'));
    }

    public function update(UpdatePostRequest $request, Post $post)
    {
        $this->authorize('update', $post);
        $this->service->updatePost($post, $request->validated());
        return redirect()->route('posts.index')->with('success', 'Post updated successfully.');
    }

    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);
        $this->service->deletePost($post);
        return redirect()->route('posts.index')->with('success', 'Post deleted successfully.');
    }

    public function uploadImage(Request $request)
    {
        if ($request->hasFile('upload')) {
            $originName = $request->file('upload')->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('upload')->getClientOriginalExtension();
            $fileName = $fileName . '_' . time() . '.' . $extension;
        
            $request->file('upload')->move(public_path('storage/uploads'), $fileName);
   
            $url = asset('storage/uploads/' . $fileName);
            return response()->json(['url' => $url]);
        }
        
        return response()->json(['error' => 'No file uploaded'], 400);
    }
}
