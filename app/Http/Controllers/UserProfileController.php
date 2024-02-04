<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserProfileController extends Controller
{

    public function myProfile()
    {   // LoginPage
        // Сторінка з формою авторизації користувача

        $data = [
            'title' => 'Профіль',
            'metaDescription' => null,
            'navigation' => require_once 'navigation.php',
        ];

        return view('profile.my-profile', $data);
    }

}
