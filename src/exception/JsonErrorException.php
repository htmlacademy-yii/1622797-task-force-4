<?php

namespace taskforce\exception;

class JsonErrorException extends BaseException
{
    protected $message = 'Получен неверный формат ответа';
}
