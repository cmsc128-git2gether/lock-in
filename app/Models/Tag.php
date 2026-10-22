<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    /** @use HasFactory<\Database\Factories\TagFactory> */
    use HasFactory;
    
    protected $fillable = ['name', 'color', 'icon', 'user_id'];

    public function tasks() {
        return $this->hasMany(Task::class);
    }

    public function scopeMine($query)
    {
        return $query->where('user_id', auth()->id());
    }
}

