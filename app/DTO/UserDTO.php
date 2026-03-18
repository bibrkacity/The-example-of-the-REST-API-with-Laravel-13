<?php

namespace App\DTO;

use Illuminate\Http\Request;

readonly class UserDTO extends DTO
{
    public ?string $email;

    protected function fromRequest(Request $request): void
    {
        parent::fromRequest($request);

        $this->email = $request->input('email');

    }

    protected function getRules(Request $request): array
    {

        $rules = parent::getRules($request);
        $rules['email'] = 'email';

        return $rules;
    }

    protected function getSortNameDefault(): string
    {
        return 'name';
    }
}
