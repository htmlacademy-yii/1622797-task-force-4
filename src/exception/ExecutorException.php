<?php

namespace taskforce\exception;

class ExecutorException extends BaseException
{
    protected $message = 'Для данного статуса обязательно нужен исполнитель';
}
