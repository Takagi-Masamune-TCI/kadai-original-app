<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StoreFavoriteController extends Controller
{   
    /**
     * [POST] /stores/{id}/favorite
     */
    public function store(Request $request, string $id)
    {
        $user = $request->user();
        $user->favoriteStore(intval($id));
        
        return back();
    }


    /**
     * [DELETE] /stores/{id}/unfavorite
     */
    public function destroy(Request $request, string $id)
    {
        $user = $request->user();
        $user->unfavoriteStore(intval($id));
        
        return back();
    }
}
