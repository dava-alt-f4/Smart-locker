<?php

namespace App\Http\Controllers;

use App\Models\RfidCard;
use Illuminate\Http\Request;

class RfidCardController extends Controller
{
    /**
     * RFID check
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function check(Request $request)
    {
        $request->validate([
            'rfid_uid' => 'required',
            'locker_id' => 'required|exists:lockers,id'
        ]);

        $rfid = RfidCard::where('rfid_uid', $request->rfid_uid)->first();

        if (!$rfid) {
            return response()->json(['status' => 'invalid']);
        }

        $student = $rfid->student;
        
        if (!$student || !$student->locker) {
            return response()->json(['status' => 'no_locker']);
        }

        if ($student->locker->id != $request->locker_id) {
            return response()->json([
                'status' => 'wrong_locker'
            ]);
        }

        return response()->json([
            'status' => 'valid',
            'student_id' => $student->id
        ]);
    }
}
