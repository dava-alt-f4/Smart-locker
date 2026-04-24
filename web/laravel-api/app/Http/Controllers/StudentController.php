<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Student data
     */
    public function index()
    {
        $students = Student::all();
        return response()->json([
            'status' => 'success',
            'data' => $students
        ]);
    }

    /**
     * Verify face recognition result.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyFace(Request $request)
    {
        $student = Student::find($request->student_id);

        $request->validate([
            'student_id' => 'required|exists:students,id',
            'match' => 'required|boolean'
        ]);

        if (!$student) {
            return response()->json([
                'status' => 'failed'
            ]);
        }

        if ($request->match == true) {

            if (!$student->locker) {
                return response()->json([
                    'status' => 'no_locker'
                ]);
            }

            $locker = $student->locker;

            if ($locker->student_id !== $student->id) {
                return response()->json([
                    'status' => 'unauthorized'
                ]);
            }

            return response()->json([
                'status' => 'verified',
                'locker_id' => $locker->id
            ]);
        }

        return response()->json([
            'status' => 'face_not_match'
        ]);
    }
}
