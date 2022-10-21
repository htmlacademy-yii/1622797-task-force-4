<?php

namespace Taskforce\exception;

class FileFormatException extends BaseException
{
    protected $message = 'Не удалось открыть файл на чтение';
}
