<?php

namespace App\Http\Controllers\SuperStar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\SuperstarPost;

class SuperstarPostController extends Controller
{
    public function index(Request $request)
    {
        $superstar = $request->user();
        
        // Pagination parameters
        $page = $request->get('page', 1);
        $perPage = $request->get('per_page', 15);
        
        // Filter parameters
        $mediaType = $request->get('media_type');
        $isPg = $request->get('is_pg');
        $isDisturbing = $request->get('is_disturbing');
        
        $query = SuperstarPost::where('user_id', $superstar->id);
        
        // Apply filters
        if ($mediaType) {
            $query->where('media_type', $mediaType);
        }
        if ($isPg !== null) {
            $query->where('is_pg', filter_var($isPg, FILTER_VALIDATE_BOOLEAN));
        }
        if ($isDisturbing !== null) {
            $query->where('is_disturbing', filter_var($isDisturbing, FILTER_VALIDATE_BOOLEAN));
        }
        
        $posts = $query->orderBy('created_at', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);
            
        return response()->json([
            'posts' => $posts->items(),
            'pagination' => [
                'current_page' => $posts->currentPage(),
                'last_page' => $posts->lastPage(),
                'per_page' => $posts->perPage(),
                'total' => $posts->total(),
                'from' => $posts->firstItem(),
                'to' => $posts->lastItem(),
                'has_more_pages' => $posts->hasMorePages()
            ]
        ]);
    }
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'media_type' => 'required|in:image,video',
            'resource_type' => 'required|in:upload,url',
            'resource_url_path' => 'required|string|max:500',
            'description' => 'nullable|string',
            'is_pg' => 'boolean',
            'is_disturbing' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $superstar = $request->user();
        
        $post = SuperstarPost::create([
            'user_id' => $superstar->id,
            'media_type' => $request->media_type,
            'resource_type' => $request->resource_type,
            'resource_url_path' => $request->resource_url_path,
            'description' => $request->description,
            'is_pg' => $request->boolean('is_pg', false),
            'is_disturbing' => $request->boolean('is_disturbing', false)
        ]);
        
        return response()->json([
            'message' => 'Post created successfully',
            'post' => $post->load('superstar')
        ], 201);
    }
    
    public function show(Request $request, $id)
    {
        $superstar = $request->user();
        
        $post = SuperstarPost::where('user_id', $superstar->id)
            ->where('id', $id)
            ->with('superstar')
            ->first();
            
        if (!$post) {
            return response()->json([
                'message' => 'Post not found'
            ], 404);
        }
        
        return response()->json(['post' => $post]);
    }
    
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'media_type' => 'in:image,video',
            'resource_type' => 'in:upload,url',
            'resource_url_path' => 'string|max:500',
            'description' => 'nullable|string',
            'is_pg' => 'boolean',
            'is_disturbing' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $superstar = $request->user();
        
        $post = SuperstarPost::where('user_id', $superstar->id)
            ->where('id', $id)
            ->first();
            
        if (!$post) {
            return response()->json([
                'message' => 'Post not found'
            ], 404);
        }
        
        $updateData = [];
        
        if ($request->has('media_type')) {
            $updateData['media_type'] = $request->media_type;
        }
        if ($request->has('resource_type')) {
            $updateData['resource_type'] = $request->resource_type;
        }
        if ($request->has('resource_url_path')) {
            $updateData['resource_url_path'] = $request->resource_url_path;
        }
        if ($request->has('description')) {
            $updateData['description'] = $request->description;
        }
        if ($request->has('is_pg')) {
            $updateData['is_pg'] = $request->boolean('is_pg');
        }
        if ($request->has('is_disturbing')) {
            $updateData['is_disturbing'] = $request->boolean('is_disturbing');
        }
        
        $post->update($updateData);
        
        return response()->json([
            'message' => 'Post updated successfully',
            'post' => $post->load('superstar')
        ]);
    }
    
    public function destroy(Request $request, $id)
    {
        $superstar = $request->user();
        
        $post = SuperstarPost::where('user_id', $superstar->id)
            ->where('id', $id)
            ->first();
            
        if (!$post) {
            return response()->json([
                'message' => 'Post not found'
            ], 404);
        }
        
        // Delete file if it's an upload
        if ($post->resource_type === 'upload' && Storage::disk('public')->exists($post->resource_url_path)) {
            Storage::disk('public')->delete($post->resource_url_path);
        }
        
        $post->delete();
        
        return response()->json([
            'message' => 'Post deleted successfully'
        ]);
    }
}
