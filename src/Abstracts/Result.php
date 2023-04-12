<?php

namespace WPTrait\Abstracts;

abstract class Result extends Params
{

    /**
     * Handle Return after execute function
     *
     * @var int|array|\WP_Error
     */
    protected int|array|\WP_Error $response;

    protected function hasError(): bool
    {
        return is_wp_error($this->response);
    }

    protected function getError(): bool
    {
        if ($this->hasError()) {
            return $this->response->get_error_message();
        }

        return '';
    }
}