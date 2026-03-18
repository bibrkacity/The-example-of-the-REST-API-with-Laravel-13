<?php

namespace App\DTO;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

/**
 *  Base class for DTO-classes
 *  DTO - Data Transfer Object, a class that contains data that is transferred
 *        between the method index () of the controller and the repository.
 */
abstract readonly class DTO
{
    public const int PER_PAGE = 20;

    /**
     * Page number for pagination. Default 1
     * @var int
     */
    public int $page;

    /**
     * Number of items per page. Default self::PER_PAGE
     * @var int
     */
    public int $perPage;

    /**
     * Search query string. Default null
     * @var string|null
     */
    public ?string $query;

    /**
     * Sorting column name. Default $this->getSortNameDefault()
     * @var string
     */
    public string $sortName;

    /**
     * Sorting direction. Default 'asc'
     * @var string
     */
    public string $sortDir;

    abstract protected function getSortNameDefault(): string;

    public function __construct(Request $request)
    {
        $this->fromRequest($request);
    }

    public function toArray(): array
    {
        $array = [];
        foreach ($this as $name => $value) {
            if (is_object($value)) {
                $array[$name] = $this->objectToArray($value);
            } else {
                $array[$name] = $value;
            }
        }

        return $array;
    }

    protected function objectToArray($obj): array
    {
        $result = [];

        foreach ($obj as $name => $value) {
            if (! is_object($value)) {
                $result[$name] = $value;
            }
        }

        return $result;
    }

    protected function fromRequest(Request $request): void
    {
        $validator = Validator::make($request->all(), $this->getRules($request));

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $this->page = $request->input('page', 1);
        $this->perPage = $request->input('per_page', self::PER_PAGE);
        $this->query = $request->input('query');
        $this->sortName = $request->input('sort_name', $this->getSortNameDefault());
        $this->sortDir = $request->input('sort_dir', 'asc');

    }

    protected function getRules(Request $request): array
    {

        return [
            'page' => 'int|min:1',
            'per_page' => 'int|min:0',
            'sort_dir' => 'in:asc,desc',
            'sort_name' => 'string',
            'query' => 'string',
        ];
    }
}
