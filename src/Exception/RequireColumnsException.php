<?php

namespace Taskforce\Exception;

class RequireColumnsException extends BaseException
{
    protected $message = 'Исходный файл не содержит необходимых столбцов';
}
