<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Task extends Model implements HasMedia
{
    use InteractsWithMedia;
    public const STATUS_PENDING = 'pending';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_COMPLETED = 'completed';
    public const PRIORITY_LOW = 'low';
    public const PRIORITY_MEDIUM = 'medium';
    public const PRIORITY_HIGH = 'high';
    protected $fillable = [
        'name',
        'type',
        'detail',
        'description',
        'assigned_to',
        'project_id',
        'status',
        'assigned_at',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('attachments')
            ->acceptsMimeTypes([
                'image/jpeg',
                'image/png',
                'image/gif',
                'application/pdf',
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'video/mp4',
            ])
            ->useDisk('local');
        // or 's3' for cloud storage
    }

    protected static function booted()
    {
        static::deleting(function ($task) {
            $task->clearMediaCollection('attachments'); // delete files from disk + DB
        });
    }

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
    public function activities()
    {
        return $this->hasMany(UserTaskActivities::class);
    }


}
