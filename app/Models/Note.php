<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Note extends Model
{
    protected $fillable = ['body', 'notable_id', 'notable_type'];

    public function notable(): MorphTo
    {
        return $this->morphTo();
    }
}