<?php

namespace Taskforce\Exception;

class StatusException extends TaskException
{
    protected $message = 'Неверный статус задания';
}
