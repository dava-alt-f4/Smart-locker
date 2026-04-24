<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    public function locker()
    {
        return $this->hasOne(Locker::class);
    }

    public function rfidCard()
    {
        return $this->hasOne(RfidCard::class);
    }
}
