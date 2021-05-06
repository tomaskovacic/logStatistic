<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class UploadController extends Controller
{

    // 
    function index(Request $req)
    {
        try {
            $path = $req->file('file')->store('logs');
            $path = trim($path, "logs/");
            $endPath = "app\\logs\\";
            $endPath .= $path;
            $file = fopen(storage_path($endPath), "r");
            $array = [];
            $arrayWithoutDuplicates = [];
            $arrayOfResults = [];
            $lineCounter = 0;
            $infoCounter = 0;
            $debugCounter = 0;
            $errorCounter = 0;
            while (!feof($file)) {
                $array[$lineCounter] = fgets($file);
                if (str_contains($array[$lineCounter], "local.INFO")) {
                    $infoCounter++;
                }
                if (str_contains($array[$lineCounter], "local.DEBUG")) {
                    $debugCounter++;
                }
                if (str_contains($array[$lineCounter], "local.ERROR")) {
                    $errorCounter++;
                }
                $lineCounter++;
            }
            $arrayOfResults[0] = $infoCounter;
            $arrayOfResults[1] = $debugCounter;
            $arrayOfResults[2] = $errorCounter;

            
            $arrayWithoutDuplicates = array_unique($array, SORT_STRING );
            //dd(count($arrayWithoutDuplicates)); 
            //dd(count($array)); 
            $counter2=3;
            foreach($arrayWithoutDuplicates as $item) {
                $arrayOfResults[$counter2] = $item;
                $counter2++;
            }
            return view('logs',['arrayOfResults'=>$arrayOfResults]);
        } catch (FileNotFoundException $e) {
            return "File not found";
        }
    }
}
