<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JJTaskPhoto extends Model
{
    protected $table = 'jj_task_photos';

    protected $fillable = ['task_id', 'type', 'filename'];

    public function task()
    {
        return $this->belongsTo(JJTask::class, 'task_id');
    }
}
