<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ContributorController extends Controller
{
    public function validateEmail(Request $request)
    {
        $email = $request->query('email');
        $contributor = User::where('email', $email)->first();

        if ($contributor) {
            return response()->json(['exists' => true, 'contributor' => $contributor]);
        } else {
            return response()->json(['exists' => false]);
        }
    }
}
