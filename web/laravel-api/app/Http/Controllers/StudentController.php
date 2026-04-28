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
    $request->validate([
        'student_id' => 'required|exists:students,id',
        'locker_id' => 'required|exists:lockers,id',
        'match' => 'required|boolean'
    ]);

    $student = Student::find($request->student_id);

    if (!$student->locker) {
        return response()->json(['status' => 'no_locker']);
    }
    
    if ($student->locker->id != $request->locker_id) {
        return response()->json(['status' => 'wrong_locker']);
    }

    if ($request->match) {
        return response()->json([
            'status' => 'verified',
            'locker_id' => $student->locker->id
        ]);
    }

    return response()->json(['status' => 'face_not_match']);
}
}
