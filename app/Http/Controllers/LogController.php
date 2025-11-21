<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class LogController extends Controller
{
    /**
     * Show ALL logs in the system
     * URL: /admin/logs
     */
    public function index(Request $request)
    {
        $action = $request->get('action'); // optional filter: created/updated/deleted

        $logs = ActivityLog::with('user')
            ->when($action, function ($q) use ($action) {
                return $q->where('action', $action);
            })
            ->latest()
            ->paginate(20); // pagination

        return view('logs.index', compact('logs'));
    }


    /**
     * Show logs for a particular model + record
     * Example: /logs/App%5CModels%5CBook/9781234
     */
    public function itemLogs($model, $id)
    {
        $model = urldecode($model); // namespace decoding (App\Models\Book)

        $logs = ActivityLog::where('model', $model)
            ->where('record_id', $id)
            ->with('user')
            ->latest()
            ->get();

        return view('logs.item', compact('logs'));
    }
}
