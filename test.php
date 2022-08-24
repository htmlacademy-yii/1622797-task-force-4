<?php

require_once 'src/Task.php';

assert_options(ASSERT_ACTIVE, 1);
assert_options(ASSERT_WARNING, 0);

function myAssertHandler(string $file, int $line, mixed $code, string|null $desc = null): void
{
    echo "Неудачная проверка утверждения в $file:$line: $code";
    if ($desc) {
        echo ": $desc";
    }
    echo "<br>";
}

assert_options(ASSERT_CALLBACK, 'myAssertHandler');

$strategy1 = new Task('new', 2, 3);
assert($strategy1->getNextStatus(Task::ACTION_CANCEL, Task::STATUS_NEW, 1) === Task::STATUS_CANCELLED);
assert($strategy1->getNextStatus(Task::ACTION_RESPOND, Task::STATUS_NEW, 2) === Task::STATUS_AT_WORK);
assert($strategy1->getNextStatus(Task::ACTION_REFUSE, Task::STATUS_AT_WORK, 2) === Task::STATUS_FAILED);
assert($strategy1->getNextStatus(Task::ACTION_COMPLETE, Task::STATUS_AT_WORK, 1) === Task::STATUS_DONE);

$strategy2 = new Task('new',2, 3);
assert($strategy2->getAvailableActions(Task::STATUS_NEW, 1) === [Task::ACTION_COMPLETE, Task::ACTION_CANCEL]);
assert($strategy2->getAvailableActions(Task::STATUS_NEW, 2) === [Task::ACTION_RESPOND]);

$strategy3 = new Task('new',2, 3);
assert($strategy3->getAvailableActions(Task::STATUS_AT_WORK, 1) === [Task::ACTION_COMPLETE]);
assert($strategy3->getAvailableActions(Task::STATUS_AT_WORK, 2) === [Task::ACTION_REFUSE]);

$newTask = new Task('new',2, 3);
$mapAction = $newTask->getActionMap();
$mapStatus = $newTask->getStatusMap();

echo "метод для возврата «карты» действий";
echo "<br>";
var_dump($mapAction);
echo "<br>","<br>";
echo "метод для возврата «карты» статусов";
echo "<br>";
var_dump($mapStatus);
