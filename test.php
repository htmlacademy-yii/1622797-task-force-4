<?php

use Taskforce\Models\Task;

require_once __DIR__ . '/vendor/autoload.php';

$newTask = new Task('new', 1, null);
$mapAction = $newTask->getActionMap();
$mapStatus = $newTask->getStatusMap();

echo "Метод для возврата «карты» действий";
echo "<br>";
var_dump($mapAction);
echo "<br>","<br>";
echo "Метод для возврата «карты» статусов";
echo "<br>";
var_dump($mapStatus);
