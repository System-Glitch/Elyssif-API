<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Request;

class BaseRequest extends FormRequest
{

    public function expectsJson()
    {
        return Request::is('api/*');
    }

    public function wantsJson()
    {
        return Request::is('api/*');
    }
}
