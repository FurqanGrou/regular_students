<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use \Illuminate\Support\Facades\Session;

class Coupon extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $appends = ['is_valid'];

    public function getIsValidAttribute(){
        $student_id = Session::get('student_id');

        return $student_id &&
            $this->active &&
            $this->validDates() &&
            $this->validUsageLimit() &&
            $this->validLimitUser($student_id) &&
            $this->validUserAccess($student_id);
    }

    private function validDates()
    {
        if ($this->start_date > Carbon::now('Asia/Riyadh')) return false;

        return ! $this->end_date || $this->end_date > Carbon::now('Asia/Riyadh');
    }

    private function validUsageLimit()
    {
        return ! $this->usage_limit || $this->times_used < $this->usage_limit;
    }

    public function validLimitUser($student_id)
    {
        if (! $this->limit_user) {
            return true;
        }

        $coupon_used = DB::table('usage_coupons')->where('coupon_id', $this->id)->where('student_id', $student_id)->first();
        return ! $coupon_used;
    }

    public function validUserAccess($student_id)
    {

        if (! $this->specific_users) {
            return true;
        }

        $has_access = DB::table('coupon_student')->where('coupon_id', $this->id)->where('student_id', $student_id)->first();
        return $has_access;
    }

    public function use($student_id)
    {
        $this->times_used += 1;
        $this->save();

        DB::table('usage_coupons')->insert(
            ['student_id' => $student_id, 'coupon_id' => $this->id]
        );


    }

    public function getDiscount($subtotal)
    {
        switch ($this->type) {
            case 'percent':
                return round($subtotal / 100 * $this->value, 2);
            case 'fixed':
                return $this->value;
        }
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'coupon_student');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

}
