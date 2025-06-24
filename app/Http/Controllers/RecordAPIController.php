<?php

namespace App\Http\Controllers;

use App\Models\Record;
use Illuminate\Support\Facades\Log;

class RecordAPIController extends Controller
{
    public function index()
    {
        Log::info("[requested: GET /api/records] ");

        return Record::with(["store", "props"])->get();
    }
    
    public function show(Record $record)
    {
        
        Log::info("[requested: GET /api/records/$record->id] ");

        return $record->load(["store", "props"]);
    }

    // public function store(Request $request)
    // {
        

    //     return Post::create($validatedData);
    // }



    // public function update(Request $request, Post $post)
    // {
    //     $validatedData = $request->validate([
    //         'title' => 'string|max:255',
    //         'content' => 'string',
    //     ]);

    //     $post->update($validatedData);
    //     return $post;
    // }

    // public function destroy(Post $post)
    // {
    //     $post->delete();
    //     return response()->noContent();
    // }
}