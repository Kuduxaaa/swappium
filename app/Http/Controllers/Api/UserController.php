<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\ApiKey;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function updateInformation(Request $request)
    {
        $user = $request->user();

        if (!$user)
        {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        $name = $request->input('name') ?? $user->name;

        User::where('user_id', $user->id)->update([
            'name' => $name,
        ]);
    }

    public function getAPIs(Request $request)
    {
        $user = $request->user();

        if (!$user)
        {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        $keys = ApiKey::where('user_id', $user->id)->get();

        return response()->json([
            'success' => true,
            'data' => $keys
        ]);
    }

    public function generateApiKey(Request $request)
    {
        $user = $request->user();

        if (!$user)
        {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        $keyName = $request->input('keyName');
        $key = ApiKey::generateKey(16);

        ApiKey::create([
            'user_id' => $user->id,
            'key' => $key,
            'key_name' => $keyName,
            'enabled' => 0
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Your request has been sent and is being reviewed'
        ]);
    }

    public function deleteApiKey(Request $request) {
        $request->validate([
            'key' => 'required'
        ]);

        $user = $request->user();

        if (!$user)
        {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        $apiKey = $request->input('key');
        $key = ApiKey::where('user_id', $user->id)->where('key', $apiKey)->first();

        if (!$key)
        {
            return response()->json([
                'success' => false,
                'message' => 'Requested API key does not exist'
            ]);
        }

        $key->delete();
        return response()->json([
            'success' => true,
            'message' => 'Your API has been deleted!'
        ]);
    }
}
