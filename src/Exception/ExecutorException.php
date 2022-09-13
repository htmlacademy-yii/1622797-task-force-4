<?php

namespace Taskforce\Exception;

class ExecutorException extends TaskException
{
    protected $message = 'Для данного статуса обязательно нужен исполнитель';
}
