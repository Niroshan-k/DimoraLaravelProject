<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests; // <-- Add this line
use Illuminate\Http\Request;
use App\Models\Blog;
use Illuminate\Support\Facades\Auth;

class BlogController extends Controller
{
    use AuthorizesRequests; // <-- Add this line

    public function index()
    {
        $blogs = Blog::orderBy('created_at', 'desc')->get();
        return view('blogs.index', compact('blogs'));
    }

    public function create()
    {
        return view('blogs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'images.*' => 'nullable|image|max:2048',
        ]);

        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $img) {
                $images[] = $img->store('blog_images', 'public');
            }
        }

        Blog::create([
            'seller_id' => Auth::id(),
            'seller_name' => Auth::user()->name, // <-- Add this line
            'title' => $request->title,
            'content' => $request->content,
            'images' => $images,
        ]);

        return redirect()->route('blogs.index')->with('success', 'Blog created!');
    }
    
    public function sellerBlogs($sellerId)
    {
        $blogs = Blog::where('seller_id', $sellerId)->orderBy('created_at', 'desc')->get();
        return view('blogs.index', compact('blogs'));
    }

    public function edit($id)
    {
        $blog = Blog::findOrFail($id);
        if ($blog->seller_id != Auth::id()) {
            abort(403, 'Unauthorized');
        }
        return view('blogs.edit', compact('blog'));
    }

    public function update(Request $request, $id)
    {
        $blog = Blog::findOrFail($id);
        if ($blog->seller_id != Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'images.*' => 'nullable|image|max:2048',
        ]);

        $images = $blog->images ?? [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $img) {
                $images[] = $img->store('blog_images', 'public');
            }
        }

        $blog->update([
            'title' => $request->title,
            'content' => $request->content,
            'images' => $images,
        ]);

        return redirect()->route('dashboard')->with('success', 'Blog updated!');
    }

    public function destroy($id)
    {
        $blog = Blog::findOrFail($id);
        if ($blog->seller_id != Auth::id()) {
            abort(403, 'Unauthorized');
        }
        $blog->delete();
        return redirect()->route('dashboard')->with('success', 'Blog deleted!');
    }
}
