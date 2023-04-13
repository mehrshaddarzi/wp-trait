<?php

namespace WPTrait\Abstracts;

abstract class Result extends Params
{

    /**
     * Handle Return after execute function
     *
     * @var int|array|string|\WP_Error
     */
    protected int|array|string|\WP_Error $response;

    public function hasError(): bool
    {
        return is_wp_error($this->response);
    }

    public function getError(): string
    {
        if ($this->hasError()) {
            return $this->response->get_error_message();
        }

        return '';
    }

    public function getErrors(): array
    {
        if ($this->hasError()) {
            return $this->response->get_error_messages();
        }

        return [];
    }

    public function getErrorCode(): string
    {
        if ($this->hasError()) {
            return $this->response->get_error_code();
        }

        return '';
    }

    public function getErrorCodes(): array
    {
        if ($this->hasError()) {
            return $this->response->get_error_codes();
        }

        return [];
    }
}