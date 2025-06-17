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

            $data = [
                "user" => $user,
                "stores" => $stores
            ];
        }

        return view('dashboard', $data);
    }
}
