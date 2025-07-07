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
        $this->hasMany(User::class, 'organization_id');
    }

    public function projects()
    {
        $this->hasMany(Project::class, 'organization_id');
    }

}
