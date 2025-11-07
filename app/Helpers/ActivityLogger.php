<?php

namespace App\Helpers;

use App\Models\ActivityLog;

class ActivityLogger
{
    /**
     * Log an activity
     */
    public static function log(
        $action,
        $subjectType = null,
        $subjectId = null,
        $properties = null,
        $description = null
    ) {
        return ActivityLog::logActivity(
            auth()->id(),
            $action,
            $description,
            $subjectType,
            $subjectId,
            $properties
        );
    }

    /**
     * Log create action
     */
    public static function logCreate($model, $description = null)
    {
        $title = $model->title ?? $model->name ?? 'record';
        $finalDescription = $description ?? "Created {$title}";

        return self::log(
            'created',
            get_class($model),
            $model->id,
            null,
            $finalDescription
        );
    }

    /**
     * Log update action
     */
    public static function logUpdate($model, $oldValues = null, $description = null)
    {
        $title = $model->title ?? $model->name ?? 'record';
        $finalDescription = $description ?? "Updated {$title}";

        $properties = [
            'old' => $oldValues ?? [],
            'new' => $model->only($oldValues ? array_keys($oldValues) : []),
        ];

        return self::log(
            'updated',
            get_class($model),
            $model->id,
            $properties,
            $finalDescription
        );
    }

    /**
     * Log delete action
     */
    public static function logDelete($model, $description = null)
    {
        $title = $model->title ?? $model->name ?? 'record';
        $finalDescription = $description ?? "Deleted {$title}";

        $properties = [
            'data' => $model->toArray(),
        ];

        return self::log(
            'deleted',
            get_class($model),
            $model->id,
            $properties,
            $finalDescription
        );
    }

    /**
     * Log approval action
     */
    public static function logApprove($model, $reason = null, $description = null)
    {
        $title = $model->title ?? $model->name ?? 'record';
        $finalDescription = $description ?? "Approved {$title}";

        $properties = [
            'reason' => $reason,
        ];

        return self::log(
            'approved',
            get_class($model),
            $model->id,
            $properties,
            $finalDescription
        );
    }

    /**
     * Log rejection action
     */
    public static function logReject($model, $reason = null, $description = null)
    {
        $title = $model->title ?? $model->name ?? 'record';
        $finalDescription = $description ?? "Rejected {$title}";

        $properties = [
            'reason' => $reason,
        ];

        return self::log(
            'rejected',
            get_class($model),
            $model->id,
            $properties,
            $finalDescription
        );
    }
}
