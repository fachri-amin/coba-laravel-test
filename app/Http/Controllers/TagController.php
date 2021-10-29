<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Utils\ApiResponse;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tags = Tag::all();

        return response()->json(ApiResponse::success($tags, 'Success get data tags'));
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
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|max:25',
        ];

        $validators = Validator::make($request->all(), $rules);

        if($validators->fails()){
            return response()->json([
                'errors' => $validators->errors()->all()
            ], 422);
        }

        $tag = Tag::create([
            'name' => $request->name
        ]);

        return response()->json(ApiResponse::success($tag, 'Success add data tag'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function show(Tag $tag)
    {
        return response()->json(ApiResponse::success($tag, 'Success Get Data tag'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function edit(Tag $tag)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tag $tag)
    {
        $rules = [
            'name' => 'required|max:25'
        ];

        $validators = Validator::make($request->all(), $rules);

        if($validators->fails()){
            return response()->json([
                'errors' => $validators->errors()->all()
            ], 422);
        }

        $tag->name = $request->name;
        $tag->save();

        return response()->json(ApiResponse::success($tag, 'Success edit data tag'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tag $tag)
    {
        $tag->delete();

        return response()->json(ApiResponse::success($tag, 'Success delete data tag'));
    }
}
