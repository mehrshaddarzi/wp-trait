<?php

namespace WPTrait\Exceptions\Json;

use WPTrait\Exceptions\TraitException;

class UnableDecodeJsonException extends TraitException
{
    public function __construct(string $message = "", int $code = 0, \Throwable $previous = null)
    {
        parent::__construct("Unable to decode JSON: {$message}", $code, $previous);
    }
}