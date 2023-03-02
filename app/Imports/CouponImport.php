<?php

namespace App\Imports;

use App\Models\Coupon;
use App\Models\CouponStudent;
use App\Models\Course;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CouponImport implements ToModel, WithHeadingRow, WithChunkReading, WithBatchInserts

{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {

        dd($row);
        $serial_number = trim($row['serial_number']);
        $section = trim($row['section']) == 'بنين' ? '1' : '2';
        $value   = trim($row['value']);
        $code    = trim($row['code']);

        $course = Course::query()->where('code', '=', 'regular')->first();
        $student = Student::query()
            ->where('serial_number', '=', $serial_number)
            ->where('section', '=', $section)
            ->first();

        if(!empty($serial_number) && !empty($section) && !empty($value) && !empty($code) && $student){

            $coupon = Coupon::create([
                'code' => $code,
                'type' => 'fixed',
                'value' => $value,
                'usage_limit' => 1,
                'start_date' => Carbon::now('Asia/Riyadh')->toDate(),
//                'end_date' => '2022-11-01 00:00:00',
                'active' => 1,
                'limit_user' => 1,
                'specific_users' => 1,
                'course_id' => $course->id,
            ]);

            CouponStudent::create([
                'student_id' => $student->id,
                'coupon_id'  => $coupon->id,
            ]);

        }
    }

    public function batchSize(): int
    {
        return 300;
    }

    public function chunkSize(): int
    {
        return 300;
    }
}
