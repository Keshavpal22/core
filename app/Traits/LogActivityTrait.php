<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

trait LogActivityTrait
{
    public static function bootLogActivityTrait()
    {
        // CREATE EVENT
        static::created(function ($model) {
            $new = $model->getAttributes();

            // Remove unnecessary fields
            unset($new['created_at'], $new['updated_at'], $new['remember_token'], $new['password']);

            self::saveLog('created', $model, [], $new);
        });

        // UPDATE EVENT
        static::updating(function ($model) {
            $dirty = $model->getDirty();   // only changed fields
            $old = [];

            // Filter old data only for the changed fields
            foreach ($dirty as $key => $value) {
                $old[$key] = $model->getOriginal($key);
            }

            self::saveLog('updated', $model, $old, $dirty);
        });

        // DELETE EVENT
        static::deleted(function ($model) {
            $old = $model->getOriginal();
            unset($old['remember_token'], $old['password']);

            self::saveLog('deleted', $model, $old, []);
        });
    }

    private static function saveLog($action, $model, $old, $new)
    {
        ActivityLog::create([
            'user_id'   => Auth::id(),
            'model'     => get_class($model),
            'record_id' => $model->getKey(),  // supports custom PK like ISBN
            'action'    => $action,
            'old_values' => json_encode($old),
            'new_values' => json_encode($new),
        ]);
    }
}
