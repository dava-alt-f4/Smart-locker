<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function locker()
    {
        return $this->belongsTo(Locker::class);
    }
}
