<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\TryCatch;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $category = Category::orderBy('name', 'asc')->get();
        dd($category);
        return response()->json([
            'categories' => CategoryResource::collection($category)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $this->validate($request, [
                'name' => 'required',
                'slug' => 'required|unique:categories,slug'
            ]);
            $data = Category::create([
                'name' => $request->name,
                'slug' => $request->slug
            ]);
            return response()->json([
                'message' => 'Category created successful'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'invalid',
                'message' => $th->getMessage()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $slug)
    {
        $data = Category::where('slug', $slug);
        if($data->first() == null) {
            return response()->json([
                'message' => 'Category not found'
            ], 404);
        }
        $data->delete();
        return response()->json([
            'message' => 'Category deleted successful'
        ]);
    }
}
