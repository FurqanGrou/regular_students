<?php

namespace App\Imports;

use App\Models\Coupon;
use App\Models\CouponStudent;
use App\Models\Course;
use App\Models\CustomPrice;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CustomPricesImport implements ToModel, WithHeadingRow, WithChunkReading, WithBatchInserts

{

    public function model(array $row)
    {

        $serial_number    = trim($row['serial_number']);
        $section          = trim($row['section']) == 'بنين' ? '1' : '2';
        $discount_value   = trim($row['discount_value']);
        $discount_percent = trim($row['discount_percent']);
        $discount_reason  = trim($row['discount_reason']);

        if(!empty($serial_number) && !empty($section) && !empty($discount_reason) && !(is_numeric($discount_value) && is_numeric($discount_percent)) ){
            $student = Student::query()
                ->where('serial_number', $serial_number)
                ->where('section', $section)
                ->first();

            if ($student && (is_numeric($discount_percent) || is_numeric($discount_value)) ){
                $student->allCustomPrice()->updateOrCreate([

                ],
                [
                    'discount_percent' => is_numeric($discount_percent) ? $discount_percent : null,
                    'discount_value' => is_numeric($discount_value) ? $discount_value : null,
                    'discount_reason' => $discount_reason,
                ]);
            }
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
