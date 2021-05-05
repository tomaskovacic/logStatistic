<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class UploadController extends Controller
{

    // 
    function index(Request $req): array
    {
        try {
            $path = $req->file('file')->store('logs');
            $path = trim($path, "logs/");
            $end_path = "app\\logs\\";
            $end_path .= $path;
            $file = fopen(storage_path($end_path), "r");
            $array = [];
            $array_without_duplicates = [];
            $array_of_results = [];
            $line_counter = 0;
            $info_counter = 0;
            $debug_counter = 0;
            $error_counter = 0;
            while (!feof($file)) {
                $array[$line_counter] = fgets($file);
                if (str_contains($array[$line_counter], "local.INFO")) {
                    $info_counter++;
                }
                if (str_contains($array[$line_counter], "local.DEBUG")) {
                    $debug_counter++;
                }
                if (str_contains($array[$line_counter], "local.ERROR")) {
                    $error_counter++;
                }
                $line_counter++;
            }
            $array_of_results[0] = $info_counter;
            $array_of_results[1] = $debug_counter;
            $array_of_results[2] = $error_counter;

            
            $array_without_duplicates = array_unique($array, SORT_STRING );
            //dd(count($array_without_duplicates)); 
            //dd(count($array)); 
            $counter2=3;
            foreach($array_without_duplicates as $item) {
                $array_of_results[$counter2] = $item;
                $counter2++;
            }
            return $array_of_results;
        } catch (FileNotFoundException $e) {
            return "File not found";
        }
    }
}
