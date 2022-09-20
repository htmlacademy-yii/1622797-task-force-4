<?php

namespace Taskforce\Exception;

class RequireColumnsException extends AbstractException
{
    protected $message = 'Исходный файл не содержит необходимых столбцов';
}
