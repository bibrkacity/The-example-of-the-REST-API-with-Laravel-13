<?php

declare(strict_types=1);

namespace App\Repositories;

use App\DTO\UserDTO;
use App\Interfaces\IUserRepository;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class UserRepository extends Repository implements IUserRepository
{
    /**
     * @throws \Exception
     */
    public function findByDTO(UserDTO $dto): array
    {
        $builder = $this->commonBuilder($dto);

        return $this->findByDTOToArray($builder, $dto->page, $dto->perPage);
    }

    public function countByDTO(UserDTO $dto): int
    {
        $builder = $this->commonBuilder($dto);

        return (int) $builder->count();
    }

    private function commonBuilder(UserDTO $dto): Builder
    {
        $builder = User::query();


        if ($dto->email !== null) {
            $builder->where('email', 'like', $dto->email);
        }

        if ($dto->query !== null) {
            $builder->where(function ($query) use ($dto) {
                $query
                    ->where('name', 'like', "%$dto->query%")
                    ->orWhere('email', 'like', "%$dto->query%");
            });
        }

        $builder->orderBy($dto->sortName, $dto->sortDir);

        return $builder;
    }
}
