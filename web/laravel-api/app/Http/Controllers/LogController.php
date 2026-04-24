<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Illuminate\Http\Request;

class LogController extends Controller
{

    public function index()
    {
        $logs = Log::with(['student', 'locker'])
            ->latest()
            ->paginate(25);

        return view('admin.index', compact('logs'));
    }

    /**
     * Store locker access log.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required',
            'locker_id' => 'nullable',
            'rfid_uid' => 'required',
            'status' => 'required'
        ]);

        Log::create([
            'student_id' => $request->student_id,
            'locker_id' => $request->locker_id,
            'rfid_uid' => $request->rfid_uid,
            'status' => $request->status
        ]);

        return response()->json([
            'status' => 'saved'
        ]);
    }
}
