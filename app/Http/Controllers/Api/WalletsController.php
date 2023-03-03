<?php

namespace App\Http\Controllers\Api;

use App\Classes\WhitebitPrivate;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WalletsController extends Controller
{
    public function myWallets($type, Request $request)
    {
        $user = $request->user();

        return response()->json(
            $user->getWallets($type)
        );
    }
}
