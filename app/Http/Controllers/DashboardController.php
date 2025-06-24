<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * GET /dashboard
     */
    public function index()
    {
        $data = [];
        if (\Auth::check()) {
            // ログイン済みユーザー
            $user = \Auth::user();
            $stores = $user->usingStores();
            $foreignStores = $user->foreignStoresFilter()->get();

            $data = [
                "user" => $user,
                "stores" => $stores,
                "foreignStores" => $foreignStores
            ];
        }

        return view('dashboard', $data);
    }
}
