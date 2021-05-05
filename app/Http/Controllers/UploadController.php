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
            $path = $req->file('file')->store('logs');
            $path = trim($path,"logs/");
            $text = "app\\logs\\";
            $text .= $path; 
            $file = fopen(storage_path($text), "r");
            $array=[];
            $i = 0;
            while(!feof($file)) {
                $array[$i] = fgets($file);
                $i++;
                
            }
            return $array[4];
            
        } catch (FileNotFoundException $e) {
            return "File not found";
        }
    }
}
