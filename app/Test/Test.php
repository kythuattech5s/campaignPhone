<?php

namespace App\Test;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Test extends Controller
{
    public function index(Request $request)
    {
        if ($request->isMethod('POST')) {
            dd($request->all());
        }
        return view('tests.index');
    }
}
