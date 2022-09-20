<?php

namespace Taskforce\Exception;

class ExecutorException extends AbstractException
{
    protected $message = 'Для данного статуса обязательно нужен исполнитель';
}
