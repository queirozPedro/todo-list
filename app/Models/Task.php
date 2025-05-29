<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['title', 'description', 'status', 'date'];
    protected $casts = [
        'date' => 'datetime',
    ];

    // Helper para saber se está concluída
    public function getIsCompletedAttribute()
    {
        return $this->status === 'completed';
    }
}
