<?php

$swdNamespaceAutoload = function ($class) {
    $classParts = explode('\\', $class);
    if ($classParts[0] == 'Bga') {
        $classParts = array_slice($classParts, 3);
        $file = '../modules/php/' . implode(DIRECTORY_SEPARATOR, $classParts) . '.php';
        if (file_exists($file)) {
            require_once $file;
        } else {
            var_dump('Cannot find file : ' . $file);
        }
    } else {
        require_once '../_ide_helper.php';
    }
};
spl_autoload_register($swdNamespaceAutoload, true, true);

function extractData($fieldName, $managerName, $folder, $listVariableName, $instanceFunction, $additionalIds = [])
{
    include "./../modules/php/$folder/list.php";

    file_put_contents(FILENAME, "  '$fieldName': {\n", FILE_APPEND);
    foreach (array_merge($$listVariableName, $additionalIds) as $id) {
        $class = "Bga\\Games\\trickerionlegendsofillusion\\Managers\\$managerName";
        $object = $class::$instanceFunction($id);
        file_put_contents(FILENAME, "  " . $id . ": " . json_encode($object->getStaticData()) . ",\n", FILE_APPEND);
    }
    file_put_contents(FILENAME, "  },\n", FILE_APPEND);
}

function extractManualData($fieldName, $managerName, $dataList, $nameField)
{
    file_put_contents(FILENAME, "  '$fieldName': {\n", FILE_APPEND);
    foreach ($dataList as $data) {
        $class = "Bga\\Games\\trickerionlegendsofillusion\\Managers\\$managerName";
        $object = $class::cast($data);
        file_put_contents(FILENAME, "  " . $data[$nameField] . ": " . json_encode($object->getStaticData()) . ",\n", FILE_APPEND);
    }
    file_put_contents(FILENAME, "  },\n", FILE_APPEND);
}

const FILENAME = '../modules/js/staticData.js';
file_put_contents(FILENAME, "export const staticData = {\n");
extractData('assignments', 'Assignments', 'Assignments', 'assignmentTypes', 'getAssignmentInstance');
extractData('magicians', 'Magicians', 'Magicians', 'magicianTypes', 'getMagicianInstance');
extractData('performances', 'Performances', 'Performances', 'performanceTypes', 'getPerformanceInstance');
extractData('prophecies', 'Prophecies', 'Prophecies', 'prophecyTypes', 'getProphecyInstance');
extractData('tricks', 'Tricks', 'Tricks', 'trickTypes', 'getTrickInstance');
extractManualData('characters', 'Characters', [
    ["character_type" => "magician"],
    ["character_type" => "apprentice"],
    ["character_type" => "assistant"],
    ["character_type" => "manager"],
    ["character_type" => "engineer"],
], "character_type");
extractManualData('components', 'Components', [
    ["component_type" => "wood"],
    ["component_type" => "glass"],
    ["component_type" => "metal"],
    ["component_type" => "fabric"],
    ["component_type" => "rope"],
    ["component_type" => "petroleum"],
    ["component_type" => "saw"],
    ["component_type" => "animal"],
    ["component_type" => "padlock"],
    ["component_type" => "mirror"],
    ["component_type" => "disguise"],
    ["component_type" => "cog"],
], "component_type");
file_put_contents(FILENAME, "}\n", FILE_APPEND);
