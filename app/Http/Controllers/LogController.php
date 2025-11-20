<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class LogController extends Controller
{
    /**
     * Show ALL logs of the system
     * URL: /admin/logs
     */
    public function index()
    {
        $logs = ActivityLog::with('user')
            ->latest()
            ->paginate(20);   // pagination added

        return view('logs.index', compact('logs'));
    }


    /**
     * Show logs for a specific item (Book, User, etc)
     * URL: /logs/{model}/{id}
     */
    public function itemLogs($model, $id)
    {
        $model = urldecode($model);  // required for namespaces

        $logs = ActivityLog::where('model', $model)
            ->where('record_id', $id)
            ->with('user')
            ->latest()
            ->get();

        return view('logs.item', compact('logs'));
    }
}
