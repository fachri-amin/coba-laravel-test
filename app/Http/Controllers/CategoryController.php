<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Utils\ApiResponse;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::all();
        return response()->json(ApiResponse::success($categories, 'Success get data category'));
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
            'name' => 'required|max:100',
        ];

        $validators = Validator::make($request->all(), $rules);

        if($validators->fails()){
            return response()->json([
                'errors' => $validators->errors()->all()
            ], 422);
        }

        $category = Category::create([
            'name' => $request->name
        ]);

        return response()->json(ApiResponse::success($category, 'Success Add Data Category'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        return response()->json(ApiResponse::success($category, 'Success Get Data Category'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $rules = [
            'name' => 'required|max:100'
        ];

        $validators = Validator::make($request->all(), $rules);

        if($validators->fails()){
            return response()->json([
                'errors' => $validators->errors()->all()
            ], 422);
        }

        $category->name = $request->name;
        $category->save();

        return response()->json(ApiResponse::success($category, 'Success Edit Data Category'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return response()->json(ApiResponse::success($category, 'Success Delete Data Category'));
    }
}
