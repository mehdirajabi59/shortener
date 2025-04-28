<?php

namespace App\Exceptions;

class CodeNotFoundException extends ServiceException
{
    protected $code = 404;
}
