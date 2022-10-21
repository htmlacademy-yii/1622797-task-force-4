<?php

namespace Taskforce\exception;

class RequireColumnsException extends BaseException
{
    protected $message = 'Исходный файл не содержит необходимых столбцов';
}
