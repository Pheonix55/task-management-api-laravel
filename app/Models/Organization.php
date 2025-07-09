<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    protected $fillable = [
        'name',
        'type',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'organization_id');
    }

    public function projects()
    {
        return $this->hasMany(Project::class, 'organization_id');
    }

}
