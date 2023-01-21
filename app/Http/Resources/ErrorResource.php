<?php

namespace App\Http\Resources;

use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ErrorResource extends Response
{
    public function __construct($status = ResponseAlias::HTTP_BAD_REQUEST, $message = '', array $errors = [], array $headers = [])
    {
        $content['success'] = false;

        if ($message) {
            $content['message'] = $message;
        }

        if (!empty($errors)) {
            $content['errors'] = $errors;
        }

        parent::__construct($content, $status, $headers);
    }

    /**
     * @param ...$parameters
     * @return ErrorResource|static
     */
    public static function make(...$parameters): ErrorResource|static
    {
        return new static(...$parameters);
    }
}
