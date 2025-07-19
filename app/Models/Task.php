<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'name',
        'type',
        'detail',
        'description',
        'assigned_to',
        'project_id',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

}
