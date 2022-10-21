<?php

namespace taskforce\exception;

class FileFormatException extends BaseException
{
    protected $message = 'Не удалось открыть файл на чтение';
}
