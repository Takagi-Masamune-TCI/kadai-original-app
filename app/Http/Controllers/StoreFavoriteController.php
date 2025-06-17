<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StoreFavoriteController extends Controller
{   
    /**
     * [POST] /stores/{id}/favorite
     */
    public function store(string $id)
    {
        $user = \Auth::user();
        $user->favoriteStore(intval($id));
    }


    /**
     * [DELETE] /stores/{id}/unfavorite
     */
    public function destroy(string $id)
    {
        $user = \Auth::user();
        $user->unfavoriteStore(intval($id));
    }
}
