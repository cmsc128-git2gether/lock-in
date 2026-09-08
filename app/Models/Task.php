<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    /** @use HasFactory<\Database\Factories\TaskFactory> */
    use HasFactory, SoftDeletes;
    
    public const priorities = ['Unlabeled', 'Low', 'Medium', 'High'];

    protected $fillable = [
        'title',
        'priority',
        'due_at',
        'is_done',
        'tag_id',
    ];

    protected $casts = [
        'due_at' => 'datetime',
        'is_done' => 'boolean',
        'deleted_at' => 'datetime',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function tag() {
        return $this->belongsTo(Tag::class);
    }
}
