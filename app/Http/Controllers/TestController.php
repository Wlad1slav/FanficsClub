<?php

namespace App\Http\Controllers;

use App\Mail\TestMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class TestController extends Controller
{

    function testMail()
    {
        if (Auth::user()->id == 1)
            Mail::to('vlad.fokin2004.vf@gmail.com')
                ->send(new TestMail());

//        return redirect()->route('HomePage');
    }

}
