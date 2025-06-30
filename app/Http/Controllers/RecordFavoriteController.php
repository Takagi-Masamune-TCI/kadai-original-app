<?php

namespace App\Http\Controllers;

use App\Models\Record;
use Illuminate\Http\Request;

class RecordFavoriteController extends Controller
{
    /**
     * [POST] /records/{id}/favorite
     */
    public function store(Request $request, string $id)
    {
        $user = $request->user();
        $user->favoriteRecord(intval($id));

        return back();
    }


    /**
     * [DELETE] /records/{id}/unfavorite
     */
    public function destroy(Request $request, string $id)
    {
        $user = $request->user();
        $user->unfavoriteRecord(intval($id));

        return back();
    }
}
