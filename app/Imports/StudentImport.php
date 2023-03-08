<?php

namespace App\Imports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentImport implements ToModel, WithHeadingRow, WithChunkReading, WithBatchInserts
{

    public $section;

    public function __construct($section)
    {
        $this->section = $section;
    }

    public function model(array $row)
    {

        $serial_number = trim($row['alrkm_altslsly']);
        $client_zoho_id   = trim($row['rkm_zoho']);

        if(!empty($serial_number) && !empty($client_zoho_id)){

            $student = Student::query()->where([
                    ['serial_number', '=', $serial_number],
                    ['section', '=', $this->section],
                ])->first();

            if ($student){
                $student->update([
                    'client_zoho_id' => $client_zoho_id,
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
