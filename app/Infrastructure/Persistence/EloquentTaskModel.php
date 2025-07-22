<?php

namespace App\Infrastructure\Persistence;

use Illuminate\Database\Eloquent\Model;

class EloquentTaskModel extends Model
{
    protected $table = 'tasks';

    protected $fillable = [
        'title',
        'description',
        'is_done',
        'user_id',
        'created_at',
    ];

    public $timestamps = false;
}
