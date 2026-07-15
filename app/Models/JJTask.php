<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JJTask extends Model
{
    protected $table = 'jj_tasks';

    protected $fillable = [
        'name', 'category', 'priority', 'status', 'completed_at', 'notes',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    public function photos()
    {
        return $this->hasMany(JJTaskPhoto::class, 'task_id');
    }
}
