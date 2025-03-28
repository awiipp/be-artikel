<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Comment;
use Dotenv\Util\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(string $articles, string $comment)
    {
        $comment = Comment::where('article_id', $articles)->get();

        return response()->json([
            'data' => $comment
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, string $articles)
    {
        $article = Article::find($articles);

        if (!$article) {
            return response()->json([
                'message' => 'article not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'content' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'invalid field',
            ], 422);
        }

        $validated = $validator->validated();

        $validated['user_id'] = $request->user()->id;
        $validated['article_id'] = $article->id;

        Comment::create($validated);

        return response()->json([
            'message' => 'create comment success'
        ], 200);

        // FE jangan lupa append article id nya.
    }

    /**
     * Display the specified resource.
     */
    public function show(string $comment)
    {
        $comment = Comment::find($comment);

        if (!$comment) {
            return response()->json([
                'message' => 'comment not found'
            ], 404);
        }

        return response()->json([
            'data' => $comment
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $comment)
    {
        $comment = Comment::find($comment);

        if (!$comment) {
            return response()->json([
                'message' => 'comment not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'content' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'invalid field',
            ], 422);
        }

        $comment->update([
            'content' => $request->input('content')
        ]);

        return response()->json([
            'message' => 'update comment success'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $comment)
    {
        $comment = Comment::find($comment);

        if (!$comment) {
            return response()->json([
                'message' => 'comment not found'
            ], 404);
        }

        $comment->delete();

        return response()->json([
            'message' => 'delete comment success'
        ], 200);
    }
}
