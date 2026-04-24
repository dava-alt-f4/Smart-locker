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
        $rfid = RfidCard::where('rfid_uid', $request->rfid_uid)->first();

        // validate
        $request->validate([
            'rfid_uid' => 'required|string'
        ]);

        if (!$rfid) {
            return response()->json([
                'status' => 'invalid'
            ]);
        }

        return response()->json([
            'status' => 'valid',
            'student_id' => $rfid->student_id
        ]);
    }
}
