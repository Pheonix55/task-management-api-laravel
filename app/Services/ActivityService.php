<?php
namespace App\Services;

use App\Models\UserTaskActivities;
class ActivityService
{
    public function logTaskCompleteActivity(array $data)
    {
        return UserTaskActivities::create($data);
    }
}