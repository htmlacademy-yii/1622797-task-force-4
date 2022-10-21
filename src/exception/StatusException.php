<?php

namespace taskforce\exception;

class StatusException extends BaseException
{
    protected $message = 'Неверный статус задания';
}
