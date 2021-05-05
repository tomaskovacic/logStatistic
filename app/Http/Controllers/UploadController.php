<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    // 
    function index(Request $req): string
    {
        try {
            return Storage::disk('local')->get($req->file('file')->store('logs'));
        } catch (FileNotFoundException $e) {
            return "File not found";
        }
    }
}
