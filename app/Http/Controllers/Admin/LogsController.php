<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Logs\IndexLogs;
use App\Models\Log;
use Brackets\AdminListing\Facades\AdminListing;

class LogsController extends Controller
{
    public function index(IndexLogs $request)
    {
        // create and AdminListing instance for a specific model and
        $data = AdminListing::create(Log::class)->processRequestAndGet(
        // pass the request with params
            $request,

            // set columns to query
            ['id', 'action', 'causer_id', 'loggable_id', 'loggable_type', 'created_at'],

            // set columns to searchIn
            ['id', 'action', 'causer_id', 'loggable_id', 'loggable_type', 'created_at'],

            function ($query) use ($request) {
                $query->with(['causer', 'loggable']);
            },
        );

        $data->load(['loggable' => function ($query) {
            $query->withTrashed();
        }]);

        $data->load(['causer' => function ($query) {
            $query->withTrashed();
        }]);

        if ($request->ajax()) {
            if ($request->has('bulk')) {
                return [
                    'bulkItems' => $data->pluck('id')
                ];
            }
            return ['data' => $data];
        }
        return view('admin.logs.index', ['data' => $data]);
    }
}
