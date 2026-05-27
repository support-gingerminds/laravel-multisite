<?php

declare(strict_types=1);

namespace Gingerminds\LaravelMultisite\Http\Requests\Site;

use Gingerminds\LaravelCore\Http\Requests\FormRequestInterface;
use Illuminate\Foundation\Http\FormRequest;

class SiteRequest extends FormRequest implements FormRequestInterface
{
    /** @return  string[] */
    public function rules(): array
    {
        return [
            'code' => 'required|string|max:255',
            'url'  => 'required|url',
        ];
    }
}
