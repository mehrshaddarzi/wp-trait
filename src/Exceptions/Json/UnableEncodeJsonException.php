<?php

namespace WPTrait\Exceptions\Json;

use WPTrait\Exceptions\TraitException;

class UnableEncodeJsonException extends TraitException
{
    public function __construct(string $message = "", int $code = 0, \Throwable $previous = null)
    {
        parent::__construct("Unable to encode JSON: {$message}", $code, $previous);
    }
}