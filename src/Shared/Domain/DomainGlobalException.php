<?php

declare(strict_types=1);

namespace Src\Shared\Domain;

use Exception;

abstract class DomainGlobalException extends Exception
{
    public function __construct()
    {
        parent::__construct($this->errorMessage());
    }

    abstract public function errorCode(): string;

    abstract protected function errorMessage(): string;
}
