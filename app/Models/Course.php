<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $appends = ['price'];

    public function coupons()
    {
        return $this->hasMany(Coupon::class);
    }

    public function getPriceAttribute()
    {
        return ($this->amount/100);
    }

}
