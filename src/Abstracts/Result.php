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
}