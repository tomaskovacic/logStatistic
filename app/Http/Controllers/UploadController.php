<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PHPUnit\TextUI\XmlConfiguration\CodeCoverage\Report\Php;

class UploadController extends Controller
{

    // 
    function index(Request $req)
    {
        function get_line($file): \Generator
        {
            $lineCounter = 0;
            while (!feof($file)) {
                $array[$lineCounter] = fgets($file);
                yield $array[$lineCounter];
                $lineCounter++; //TODO: peginacija 
            }
        }

        try {
            $path = $req->file('file')->store('logs');
            $path = trim($path, "logs/");
            $endPath = "app\\logs\\";
            $endPath .= $path;
            $file = fopen(storage_path($endPath), "r");
            $array = [];
            $arrayOfResults = [];
            $date = [];
            $errorName = [];
            $errorDesc = [];

            $lineCounter = 0;
            $infoCounter = 0;
            $debugCounter = 0;
            $errorCounter = 0;

            $generator = get_line($file);
            foreach ($generator as $line) {
                $array[$lineCounter] = $line;
                $lineCounter++;
            }
            $counter1 = 0;
            $arrayWithoutDuplicates = array_unique($array, SORT_STRING);
            foreach ($arrayWithoutDuplicates as $line) {
                if (str_contains($line, "local.INFO")) {
                    $infoCounter++;
                }
                if (str_contains($line, "local.DEBUG")) {
                    $debugCounter++;
                }
                if (str_contains($line, "local.ERROR")) {
                    $errorCounter++;
                }
                if (str_contains($line, "local.ERROR") or str_contains($line, "local.DEBUG") or str_contains($line, "local.INFO")) {
                    $date[$counter1] = substr($line, 1, 19);
                    if (str_contains($line, '{"')) {
                        if (str_contains($line, "local.INFO")) {
                            $text = trim($line, substr($line, 0, 32));
                            $errorDesc[$counter1] = substr($text, 0, strpos($text, '{"'));
                        } else {
                            $text = trim($line, substr($line, 0, 32));
                            $errorDesc[$counter1] = substr($text, 0, strpos($text, '{"'));
                        }
                    } else {
                        $errorDesc[$counter1] = substr($line, 34);
                    }
                    if (str_contains($line, "local.INFO")) {
                        $errorName[$counter1] = substr($line, 28, 4);
                    } else {
                        $errorName[$counter1] = substr($line, 28, 5);
                    }
                    $counter1++;
                }
            }

            //Getting Stacktrace for specific id
            $rowId = 3;
            $tempIndex = -1;
            $stacktrace = [];
            $counterTrace = 0;
            foreach ($array as $key => $line) {
                if (str_contains($line, "local.ERROR") or str_contains($line, "local.DEBUG") or str_contains($line, "local.INFO")) {
                    if ($line == $arrayWithoutDuplicates[$rowId]) {
                        $tempIndex = $key;
                        $stacktrace[$counterTrace] =  $array[$tempIndex];
                        $key++;
                        while (!str_contains($line, "local.ERROR") or !str_contains($line, "local.DEBUG") or !str_contains($line, "local.INFO")) {
                            $stacktrace[$counterTrace] = $array[$tempIndex];
                        }
                    }
                }
            }

            $arrayOfResults[0] = $infoCounter;
            $arrayOfResults[1] = $debugCounter;
            $arrayOfResults[2] = $errorCounter;

            $arrayFinal = array($date, $errorName, $errorDesc);

            /*$counter2 = 3;
            foreach ($arrayWithoutDuplicates as $item) {
                $arrayOfResults[$counter2] = $item;
                $counter2++;
            }*/

            return view('logs', ['arrayFinal' => $arrayFinal], ['arrayOfResults' => $arrayOfResults]);
        } catch (FileNotFoundException $e) {
            return "File not found";
        }
    }

    /*public function getId($id)
{
    $id = get('id');
    dd($id);
}*/
}
