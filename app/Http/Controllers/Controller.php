<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Http\Traits\RespondsWithHttpStatus;

class Controller extends BaseController
{
    use AuthorizesRequests;
    use ValidatesRequests;
    use RespondsWithHttpStatus;

    /**
     * @OA\Info(
     *      title="Tên của API",
     *      version="3.0.0",
     *      description="Mô tả API của bạn"
     * )
     */
}
