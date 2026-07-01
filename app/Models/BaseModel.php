<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Base model for all models
 */
abstract class BaseModel extends Model
{
    /**
     * @var int
     */
    protected $perPage = 20; // No type because same in the parent class
}
