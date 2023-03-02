<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function subscribes()
    {
        return $this->hasMany(Subscribe::class, 'student_id');
    }

    public function customPrice()
    {
        return $this->hasOne(CustomPrice::class)->where('status', '=', '1');
    }

    public function allCustomPrice()
    {
        return $this->hasOne(CustomPrice::class);
    }
}
