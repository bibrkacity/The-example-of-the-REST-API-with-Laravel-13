<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Base model for all models
 */
abstract class BaseModel extends Model
{
    /** Specifying the default number of records per page
     * @var int
     */
    protected $perPage = 20; // No type because same in the parent class

    /**
     * Specifying which commands to log: insert, update, delete (lowercase)
     * @var array
     */
    protected array $loggable = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        if (count($this->loggable) > 0) {
            DB::enableQueryLog();
        }
    }

    #[\Override]
    public function save(array $options = [])  // No types because same in the parent class
    {
        $command = $this->exists ? 'update' : 'insert';

        $before = in_array($command, $this->loggable)
            ? var_export($this->attributesToArray(), true)
            : null;

        parent::save($options);

        if (in_array($command, $this->loggable)) {
            $this->saveLog($command, $before, var_export($this->attributesToArray(), true));
        }

    }

    #[\Override]
    public function delete() // No types because same in the parent class
    {
        $command = 'delete';
        $before = in_array($command, $this->loggable)
            ? var_export($this->attributesToArray(), true)
            : null;

        parent::delete();

        if (in_array('delete', $this->loggable)) {
            $this->saveLog($command, $before, 'deleted');
        }

    }

    protected function saveLog(string $command, string $before, string $after): void
    {
        $userId = auth('sanctum')->id();

        $keyId = $this->primaryKey;

        $lastQuery = collect(DB::getQueryLog())->last();
        if ($lastQuery) {
            $sql = $lastQuery['query'];
            $bindings = $lastQuery['bindings'];
            $rawSql = vsprintf(str_replace('?', "'%s'", $sql), $bindings);
        } else {
            $rawSql = '';
        }

        $log = new EloquentLog();

        $log->user_id = $userId;
        $log->model = str_replace('App\\Models\\', '', static::class);

        $log->model_id = $this->$keyId;
        $log->command = $command;
        $log->raw_sql = $rawSql;
        $log->attributes_before = $before;
        $log->attributes_after = $after;

        $log->save();
    }
}
