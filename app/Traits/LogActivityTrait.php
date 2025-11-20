<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

trait LogActivityTrait
{
    public static function bootLogActivityTrait()
    {
        static::created(function ($model) {
            self::saveLog('created', $model, [], $model->getAttributes());
        });

        static::updating(function ($model) {
            $old = $model->getOriginal();
            $new = $model->getDirty();
            self::saveLog('updated', $model, $old, $new);
        });

        static::deleted(function ($model) {
            self::saveLog('deleted', $model, $model->getOriginal(), []);
        });
    }

    private static function saveLog($action, $model, $old, $new)
    {
        ActivityLog::create([
            'user_id'   => Auth::id(),
            'model'     => get_class($model),
            'record_id' => $model->getKey(),  // supports isbn primary key
            'action'    => $action,
            'old_values' => json_encode($old),
            'new_values' => json_encode($new),
        ]);
    }
}
