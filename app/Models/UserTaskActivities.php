<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserTaskActivities extends Model
{
    protected $fillable = [
        'task_id',
        'user_id',
        'action',
    ];

    public const ACTION_CREATED = 'created';
    public const ACTION_ASSIGNED = 'assigned';
    public const ACTION_COMPLETED = 'completed';
    public const ACTION_UPDATED = 'updated';
    public const ACTION_COMMENTED = 'commented';

    public const ACTIONS = [
        self::ACTION_CREATED,
        self::ACTION_ASSIGNED,
        self::ACTION_COMPLETED,
        self::ACTION_UPDATED,
        self::ACTION_COMMENTED,
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
