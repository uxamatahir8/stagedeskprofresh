<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ValidationController extends Controller
{
    //

    public function checkEmailUnique(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $exists = User::where('email', $request->email)->exists();

        return response()->json([
            'isUnique' => $exists,
        ]);
    }
}
