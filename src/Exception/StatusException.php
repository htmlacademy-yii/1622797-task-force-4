<?php

namespace Taskforce\Exception;

class StatusException extends AbstractException
{
    protected $message = 'Неверный статус задания';
}
