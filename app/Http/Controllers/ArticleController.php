<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Article::with(['user'])->get();

        return response()->json([
            'message' => 'get article success',
            'data' => $data
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'content' => 'required',
            'category' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'invalid field',
                'errors' => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();

        $validated['user_id'] = $request->user()->id;

        $data = Article::create($validated);

        return response()->json([
            'message' => 'create form succes',
            'data' => $data
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = Article::with(['user'])->find($id);

        if (!$data) {
            return response()->json([
                'message' => 'article not found'
            ], 404);
        }

        return response()->json([
            'message' => 'get article succes',
            'data' => $data
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $article = Article::find($id);

        if (!$article) {
            return response()->json([
                'message' => 'article not found'
            ], 404);
        }

        if (Auth::user()->id !== $article->user_id) {
            return response()->json([
                'message' => 'forbidden'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'content' => 'required',
            'category' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'invalid field',
                'errors' => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();

        $data = $article->update($validated);

        return response()->json([
            'message' => 'update article success',
            'data' => $data
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $article = Article::find($id);

        if (!$article) {
            return response()->json([
                'message' => 'article not found'
            ], 404);
        }

        if (Auth::user()->id !== $article->user_id) {
            return response()->json([
                'message' => 'forbidden'
            ], 403);
        }

        $article->delete();

        return response()->json([
            'message' => 'delete article success'
        ], 200);
    }

    public function myArticle()
    {
        $articles = Article::with(['user'])->where('user_id', Auth::user()->id)->get();

        return response()->json([
            'message' => 'get user articles success',
            'data' => $articles
        ]);
    }
}
