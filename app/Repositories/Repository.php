<?php

declare(strict_types=1);

namespace App\Repositories;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Throwable;

class Repository
{
    protected mixed $dto;

    /**
     * @throws Exception
     */
    protected function findByDTOToArray(Builder $builder, int $page, int $perPage): array
    {

        if ($perPage != 0) {
            $builder->skip(($page - 1) * $perPage)
                ->take($perPage);
        }

        try {
            $result = $builder->get();
        } catch (Throwable $e) {
            $message = $e->getMessage();
            $httpCode = ResponseAlias::HTTP_INTERNAL_SERVER_ERROR;
            throw new \Exception($message, $httpCode);
        }

        $data = [];
        foreach ($result as $object) {
            $data[] = $object->toArray();
        }

        return $data;
    }
}
