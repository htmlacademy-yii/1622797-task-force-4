<?php

namespace taskforce\exception;

class TaskActionException extends BaseException
{
    protected $message = 'Исполнитель для этого задания уже выбран';
}
