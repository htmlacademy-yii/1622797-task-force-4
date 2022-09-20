<?php

namespace Taskforce\Exception;

class FileFormatException extends BaseException
{
    protected $message = 'Не удалось открыть файл на чтение';
}
