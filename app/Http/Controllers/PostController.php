<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Utils\ApiResponse;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\PostRequest;
use Auth;

class PostController extends Controller
{
    protected $post;

    public function __construct (Post $post) {
        $this->post = $post;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {        
        // if($request->query('category')){
        //     $category = $request->query('category');
        //     $post = $this->post->getPostByCategory($category);
            
        //     return response()->json(ApiResponse::success($post, 'Success get posts data with category'));
        // }

        $post = $this->post->withAllRelation()->paginate(10);

        return response()->json(ApiResponse::success($post, 'Success get posts data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostRequest $request)
    {
        $user = Auth::user();

        $validated_data = $request->validated();
        $validated_data['user_id'] = $user->id;
        $tags = $validated_data['tags'];
        unset($validated_data['tags']);

        $post = Post::create($validated_data);

        $post->tags()->attach($tags);

        return response()->json(ApiResponse::success($post, 'Success add post'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::withAllRelation()->findOrFail($id);
        // $post = Post::with('tags', 'category', 'user')
        //     ->whereHas('tags', function ($query) {
        //         $query->where('name', '=', 'Gen Z');
        //     })
        //     ->whereHas('category', function ($query) {
        //         $query->where('name', '=', 'sains');
        //     })->get();
        // dd($post->with('category', 'tags'));
        return response()->json(ApiResponse::success($post, 'Berhasil'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(PostRequest $request, Post $post)
    {
        $this->authorize('update', $post);
        $user = Auth::user();

        $post->title = $request->title;
        $post->body = $request->body;
        $post->category_id = $request->category_id;
        $post->save();

        $post->tags()->sync($request->tags);

        return response()->json(ApiResponse::success($post, 'Success edit data post'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $post->delete();

        return response()->json(ApiResponse::success($post, 'Success delete data post'));
    }
    
    public function getPostByCategory(Request $request, Category $category){
        $filter = $request->query('q');

        if($filter){
            $post = $category->posts()
                ->with('category', 'tags', 'user')
                ->where('title', 'LIKE', '%'.$filter.'%')
                ->orWhere('body', 'LIKE', '%'.$filter.'%')
                ->get();    

            return response()->json(ApiResponse::success($post, 'Success get data post by category with filter'));
        }

        $post = $category->posts()->get();

        return response()->json(ApiResponse::success($post, 'Success get data post by category'));
    }

    public function search (Request $request) {
        $post = $this->post->getPostWithFilter($request->query('q'));

        return response()->json(ApiResponse::success($post, 'Success get data'));
    }

    public function searchByTag (Request $request) {
        $post = $this->post->getPostByTag($request->query('tag'));

        return response()->json(ApiResponse::success($post, 'Success get data'));
    }
}
