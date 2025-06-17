<?php

namespace App\Http\Controllers;

use App\Models\Record;
use Illuminate\Http\Request;

class RecordFavoriteController extends Controller
{
    /**
     * [POST] /records/{id}/favorite
     */
    public function store(string $id)
    {
        $user = \Auth::user();
        $user->favoriteRecord(intval($id));
    }


    /**
     * [DELETE] /records/{id}/unfavorite
     */
    public function destroy(string $id)
    {
        $user = \Auth::user();
        $user->unfavoriteRecord(intval($id));
    }
}
