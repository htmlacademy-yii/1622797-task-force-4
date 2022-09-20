<?php

namespace Taskforce\Exception;

class FileFormatException extends AbstractException
{
    protected $message = 'Не удалось открыть файл на чтение';
}
