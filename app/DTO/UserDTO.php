<?php

namespace App\DTO;

use Illuminate\Foundation\Http\FormRequest;

/**
 * DTO for User's list
 */
readonly class UserDTO extends DTO
{
    public ?string $email;

    #[\Override]
    protected function fromRequest(FormRequest $request): void
    {
        parent::fromRequest($request);

        $this->email = $request->input('email');

    }

    #[\Override]
    protected function getSortNameDefault(): string
    {
        return 'name';
    }

    #[\Override]
    protected function getRouteName(): string
    {
        return 'api.v1.users.index';
    }

    #[\Override]
    protected function getDefaults(): array
    {
        return [
            ...parent::getDefaults(),
            'email' => null,
        ];
    }
}
