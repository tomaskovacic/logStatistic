<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Generator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{

    public function uploadFile(Request $req)
    {
        $req->file('file')->store('logs');

        //return $req->file('file')->getClientOriginalName();

        return redirect('/logs');
    }

    public function getFilenames()
    {
        $storagePath  = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix() . "logs";
        $tempFiles = File::allFiles($storagePath);
        $files = [];
        $i = 0;
        foreach ($tempFiles as $file) {
            $path = explode('\\logs\\', $file);
            $files[$i] = $path[1];
            $i++;
        }
        return json_encode($files);
    }

    public function getData(string $value)
    {
        function get_line($file): Generator
        {
            $lineCounter = 0;
            while (!feof($file)) {
                $array[$lineCounter] = fgets($file);
                yield $array[$lineCounter];
                $lineCounter++;
            }
        }

        try {
            $endPath = "app\\logs\\";
            $endPath .= $value;
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

            $arrayOfResults[0] = $infoCounter;
            $arrayOfResults[1] = $debugCounter;
            $arrayOfResults[2] = $errorCounter;

            $arrayFinal = array($date, $errorName, $errorDesc);

            /*$counter2 = 3;
            foreach ($arrayWithoutDuplicates as $item) {
                $arrayOfResults[$counter2] = $item;
                $counter2++;
            }*/
            //json_encode($arrayFinal);
            
            //"recordsTotal": 57,
            //"recordsFiltered": 57,

            for ( $i = 0; $i < count($arrayFinal[0]); $i++) {
                $final[] = array('date' => $arrayFinal[0][$i], 'errorName' => $arrayFinal[1][$i], 'errorDesc' =>$arrayFinal[2][$i]);
            }
            $newArray = array(
                'draw'=> 10,
                'recordsTotal'=> 5000,
                'recordsFiltered'=> 5000,
                'data' =>$final
            );



            return json_encode($newArray);
        } catch (FileNotFoundException $e) {
            return "File not found";
        }
    }

    // 
    /* public function index(Request $req)
    {
        function get_line($file):Generator
        {
            $lineCounter = 0;
            while (!feof($file)) {
                $array[$lineCounter] = fgets($file);
                yield $array[$lineCounter];
                $lineCounter++;
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
<<<<<<< HEAD
    //json_encode($arrayFinal);*/
    /*return view('logs', ['arrayFinal' => $arrayFinal], ['arrayOfResults' => $arrayOfResults]);

        } catch (FileNotFoundException $e) {
            return "File not found";
        }
    }*/
=======

            return view('logs', ['arrayFinal' => $arrayFinal], ['arrayOfResults' => $arrayOfResults]);
        } catch (FileNotFoundException $e) {
            return "File not found";
        }
    }
>>>>>>> 6425474f3aaf7b4d93ff0ff3479309330403e6f3

    /*public function getId($id)
{
    $id = get('id');
    dd($id);
}*/
}
