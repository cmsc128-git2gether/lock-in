<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    /** @use HasFactory<\Database\Factories\TaskFactory> */
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'priority',
        'due_at',
        'is_done',
        'is_deleted',
    ];

    protected $casts = [
        'due_at' => 'datetime',
        'is_done' => 'boolean',
        'is_deleted' => 'boolean',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function tags() {
        return $this->belongsTo(Tag::class, 'task_tags');
    }
}
