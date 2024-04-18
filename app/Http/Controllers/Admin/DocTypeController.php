<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DocTypeController extends Controller
{
    public function index()
    {
        return view('setting.doc_type.index');
    }
}
