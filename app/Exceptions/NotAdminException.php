<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class NotAdminException extends Exception
{
    public function render(Request $request): Response
    {
        return response(["message" => "only for admins, bro"], 403);
    }
}
