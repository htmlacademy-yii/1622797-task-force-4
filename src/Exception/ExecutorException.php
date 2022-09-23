<?php

namespace Taskforce\Exception;

class ExecutorException extends BaseException
{
    protected $message = 'Для данного статуса обязательно нужен исполнитель';
}
