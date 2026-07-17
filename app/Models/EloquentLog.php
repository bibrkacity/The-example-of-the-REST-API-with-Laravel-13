<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['user_id', 'model', 'model_id', 'command', 'raw_sql', 'attributes_before', 'attributes_after'])]
class EloquentLog extends BaseModel
{
}
