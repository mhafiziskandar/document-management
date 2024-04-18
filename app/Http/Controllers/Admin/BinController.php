<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BinController extends Controller
{
    public function index()
    {
        return view('bin.index');
    }

    public function project()
    {
        return view('bin.project');
    }
}
