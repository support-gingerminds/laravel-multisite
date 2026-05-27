<?php

declare(strict_types=1);

namespace Gingerminds\LaravelMultisite\Http\Requests\Language;

use Gingerminds\LaravelCore\Http\Requests\FormRequestInterface;
use Illuminate\Foundation\Http\FormRequest;

class LanguageRequest extends FormRequest implements FormRequestInterface
{
    /** @return  string[] */
    public function rules(): array
    {
        return [
            'iso'   => 'required|string|max:255',
            'label' => 'required|string|max:255',
        ];
    }
}
