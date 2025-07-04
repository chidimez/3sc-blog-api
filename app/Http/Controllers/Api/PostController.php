<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\IndexPostRequest;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;

class PostController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/posts",
     *     summary="List published posts with pagination and filters",
     *     tags={"Posts"},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number",
     *         @OA\Schema(type="integer", default=1)
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Number of posts per page",
     *         @OA\Schema(type="integer", default=10)
     *     ),
     *     @OA\Parameter(
     *         name="author",
     *         in="query",
     *         description="Filter posts by author name",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="from",
     *         in="query",
     *         description="Filter posts published from this date (YYYY-MM-DD)",
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="to",
     *         in="query",
     *         description="Filter posts published up to this date (YYYY-MM-DD)",
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Paginated list of posts",
     *         @OA\JsonContent(
     *             @OA\Property(property="current_page", type="integer"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Post")
     *             ),
     *             @OA\Property(property="last_page", type="integer"),
     *             @OA\Property(property="per_page", type="integer"),
     *             @OA\Property(property="total", type="integer")
     *         )
     *     )
     * )
     */

    public function index(IndexPostRequest $request)
    {
        $query = Post::whereNotNull('published_at');

        if ($request->filled('author')) {
            $query->where('author', $request->input('author'));
        }

        if ($request->filled('from')) {
            $query->whereDate('published_at', '>=', $request->input('from'));
        }

        if ($request->filled('to')) {
            $query->whereDate('published_at', '<=', $request->input('to'));
        }

        $perPage = $request->input('per_page', 10);

        $posts = $query->latest('published_at')->paginate($perPage);

       return response()->json(
        PostResource::collection($posts)->response()->getData(true)
    );
    }


    /**
     * @OA\Post(
     *     path="/api/posts",
     *     summary="Create a new post",
     *     tags={"Posts"},
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title","content","author"},
     *             @OA\Property(property="title", type="string", example="New Post"),
     *             @OA\Property(property="content", type="string", example="Post content here..."),
     *             @OA\Property(property="author", type="string", example="Chidi"),
     *             @OA\Property(property="scheduled_at", type="string", format="date-time", example="2025-07-02T18:00:00Z")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Post created"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(StorePostRequest $request)
    {
        $data = $request->validated();
        $post = Post::create($data);

        return new PostResource($post);
    }

    /**
     * @OA\Get(
     *     path="/api/posts/{id}",
     *     summary="Get a single post",
     *     tags={"Posts"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the post",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Post details"),
     *     @OA\Response(response=404, description="Post not found")
     * )
     */
    public function show($id)
    {
        $post = Post::whereNotNull('published_at')->findOrFail($id);
        return new PostResource($post);
    }

    /**
     * @OA\Put(
     *     path="/api/posts/{id}",
     *     summary="Update an existing post",
     *     tags={"Posts"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the post",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string", example="Updated Post"),
     *             @OA\Property(property="content", type="string", example="Updated content..."),
     *             @OA\Property(property="author", type="string", example="Chidi"),
     *             @OA\Property(property="scheduled_at", type="string", format="date-time")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Post updated"),
     *     @OA\Response(response=404, description="Post not found")
     * )
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        $data = $request->validated();
        $post->update($data);

        return new PostResource($post);
    }

    /**
     * @OA\Delete(
     *     path="/api/posts/{id}",
     *     summary="Delete a post",
     *     tags={"Posts"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the post",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="Post deleted"),
     *     @OA\Response(response=404, description="Post not found")
     * )
     */
    public function destroy(Post $post)
    {
        $post->delete();
        return response()->json(null, 204);
    }
}
