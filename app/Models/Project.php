<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'name',
        'description',
        'tech_stack',
        'details',
        'organization_id',
        'created_by'
    ];
    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'project_id');
    }

    
}
