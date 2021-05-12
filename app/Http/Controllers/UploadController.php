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

    public function read(string $value)
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
            $lineCounter = 0;

            $generator = get_line($file);
            foreach ($generator as $line) {
                $array[$lineCounter] = $line;
                $lineCounter++;
            }

            return array_unique($array, SORT_STRING);

        } catch (FileNotFoundException $e) {
            return "File not found";
        }
    }

    public function getNumber(string $value)
    {
        $infoCounter = 0;
        $debugCounter = 0;
        $errorCounter = 0;

        $arrayWithoutDuplicates = self::read($value);
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
        }

        $arrayOfResults[0] = $infoCounter;
        $arrayOfResults[1] = $debugCounter;
        $arrayOfResults[2] = $errorCounter;


        return json_encode($arrayOfResults);

    }

    public function getData(Request $req, string $value)
    {
        $start = intval($req->get('start'));
        $date = [];
        $errorName = [];
        $errorDesc = [];
        $limit = $start + 9;
        $temp = [];
        $final = [];

        $counter1 = 0;
        $arrayWithoutDuplicates = self::read($value);
        foreach ($arrayWithoutDuplicates as $line) {
            if (str_contains($line, "local.ERROR") or str_contains($line, "local.DEBUG") or str_contains($line, "local.INFO")) {
                $date[$counter1] = substr($line, 1, 19);
                if (str_contains($line, '{"')) {
                    if (str_contains($line, "local.INFO")) {
                        $text = trim($line, substr($line, 0, 32));
                        $errorDesc[$counter1] = substr($text, 0, strpos($text, '{"'));
                    } else {
                        [$firstString, $secondString] = explode('{', $line, 2);
                        [$string1, $string2, $string3, $string4] = explode(':', $firstString, 4);
                        $errorDesc[$counter1] = $string4;
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

        $arrayFinal = array($date, $errorName, $errorDesc);

        for ( $i = 0; $i < count($arrayFinal[0]); $i++) {
            $temp['data'][] = array('date' => $arrayFinal[0][$i], 'errorName' => $arrayFinal[1][$i], 'errorDesc' =>$arrayFinal[2][$i]);
        }

        foreach ($temp['data'] as $k => $line) {
            if ($k < $start)
                continue;

            if ($k > $limit)
                break;

            $final['data'][] = $line;
        }
        /*$newArray = array(
            'draw' => 10,
            'recordsTotal' => 5000,
            'recordsFiltered' => 5000,
            'data' => $final
        );*/

        return json_encode($final);

    }

    public function getErrors(string $value)
    {
        [$path, $int] = explode('@', $value);
        $index = (int)$int;

        function get_line2($file): Generator
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
            $endPath .= $path;
            $file = fopen(storage_path($endPath), "r");
            $array = [];
            $lineCounter = 0;
            $generator = get_line2($file);
            foreach ($generator as $line) {
                $array[$lineCounter] = $line;
                $lineCounter++;
            }
            $arrayWithoutDuplicates = array_unique($array, SORT_STRING);

            $array2 = [];
            $stacktrace = [];
            $counterTrace = 1;
            $tempCounter = 0;
            foreach ($arrayWithoutDuplicates as $line) {
                if (str_contains($line, "local.ERROR") or str_contains($line, "local.DEBUG") or str_contains($line, "local.INFO")) {
                    $array2[$tempCounter] = $line;
                    $tempCounter++;
                }
            }
            $indexOFNext = 0;
            foreach ($array as $key => $line) {
                if ($line == $array2[$index]) {
                    $stacktrace[0] = $line;
                    $indexOFNext = $key + 1;
                }
                if ($key == $indexOFNext) {
                    if (!str_contains($line, "local.ERROR") and !str_contains($line, "local.DEBUG") and !str_contains($line, "local.INFO")) {
                        $stacktrace[$counterTrace] = $line;
                        $counterTrace++;
                        $indexOFNext++;
                    }
                }
            }

            foreach ($stacktrace as $stack) {
                $errors[] = array('error' => $stack);
            }

            /*$errors = array(
                array('error' => $stacktrace[$index]),
                array('error' => $index),
            );*/

            $arrayOfErrors = array(
                'data' => $errors
            );

            return json_encode($arrayOfErrors);
        } catch (FileNotFoundException $e) {
            return "File not found";
        }
    }
}
