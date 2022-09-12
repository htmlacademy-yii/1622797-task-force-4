<?php

use Taskforce\Models\Task;
use Taskforce\Actions\CancelAction;
use Taskforce\Actions\CompleteAction;
use Taskforce\Actions\RefuseAction;
use Taskforce\Actions\RespondAction;

require_once __DIR__ . '/vendor/autoload.php';

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
assert($strategy1->getNextStatus(CancelAction::class) === Task::STATUS_CANCELLED);
assert($strategy1->getNextStatus(RespondAction::class) === Task::STATUS_AT_WORK);
assert($strategy1->getNextStatus(RefuseAction::class) === Task::STATUS_FAILED);
assert($strategy1->getNextStatus(CompleteAction::class) === Task::STATUS_DONE);

$strategy2 = new Task('new', 2, 3);
assert($strategy2->getAvailableActions(Task::STATUS_NEW, 3) === [CompleteAction::class, CancelAction::class]);
assert($strategy2->getAvailableActions(Task::STATUS_NEW, 2) === [RespondAction::class]);

$strategy3 = new Task('new', 2, 3);
assert($strategy3->getAvailableActions(Task::STATUS_AT_WORK, 3) === [CompleteAction::class]);
assert($strategy3->getAvailableActions(Task::STATUS_AT_WORK, 2) === [RefuseAction::class]);

$newTask = new Task('new', 2, 3);
$mapAction = $newTask->getActionMap();
$mapStatus = $newTask->getStatusMap();

echo "Метод для возврата «карты» действий";
echo "<br>";
var_dump($mapAction);
echo "<br>","<br>";
echo "Метод для возврата «карты» статусов";
echo "<br>";
var_dump($mapStatus);
