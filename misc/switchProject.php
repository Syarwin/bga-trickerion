<?php

$nameFrom = file_exists('../trickerionlegendsofillusion.css') ? 'trickerionlegendsofillusion' : 'TrickerionJurica';
$nameTo = $nameFrom == 'trickerionlegendsofillusion' ? 'TrickerionJurica' : 'trickerionlegendsofillusion';
echo("Switching project from: $nameFrom");
echo("\n\rSwitching project to: $nameTo");

$lowerCaseFrom = strtolower($nameFrom);
$lowerCaseTo = strtolower($nameTo);

echo("\n\rRenaming $lowerCaseFrom.css to $lowerCaseTo.css");
rename("../$lowerCaseFrom.css", "../$lowerCaseTo.css");

function replace($stringFrom, $stringTo, $recursive = false) {
    return function ($dir) use ($stringFrom, $stringTo, $recursive) {
        $files = scandir($dir);
        foreach ($files as $file) {
            if ($file == '.' || $file == '..') continue;
            
            $filePath = "$dir/$file";
            if (is_dir($filePath)) {
                if ($recursive) {
                    replace($stringFrom, $stringTo, true)($filePath);
                }
            } else {
                echo("\n\rProcessing file: $filePath");
                $str = file_get_contents($filePath);
                $str=str_replace($stringFrom, $stringTo, $str);
                file_put_contents($filePath, $str);
            }
        }
    };
}

replace("Bga\Games\\$nameFrom", "Bga\Games\\$nameTo", true)('../modules/php');
replace("Bga\Games\\\\$nameFrom", "Bga\Games\\\\$nameTo", true)('../modules/php/Managers');
replace("\"remotePath\": \"/$lowerCaseFrom/", "\"remotePath\": \"/$lowerCaseTo/", true)('../.vscode');