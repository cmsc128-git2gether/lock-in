<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    /** @use HasFactory<\Database\Factories\GroupFactory> */
    use HasFactory;

    protected $fillable = [
        'owner_id',
        'name'
    ];

    public function owner() {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function members() {
        return $this->belongsToMany(User::class, 'group_members')->withPivot('role')->withTimestamps(); 
    }

    public function tasks() {
        return $this->belongsToMany(Task::class, 'group_tasks')->withTimestamps();
    }
}
