<?php

namespace App\Imports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentImport implements ToModel, WithHeadingRow, WithChunkReading, WithBatchInserts
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $serial_number = trim($row['serial_number']);
        $name = trim($row['display_name']);
        $section = trim($row['section']);
        $zoho_id = trim($row['contact_id']);

        if (!empty($serial_number) && !empty($name) && !empty($zoho_id)) {

            if ($section == 'بنين') {
                $custom_section = '1';
            }else{
                $custom_section = '2';
            }

            Student::query()->updateOrCreate([
                'serial_number' => $serial_number,
                'section' => $custom_section,
            ], [
                'name' => $name,
                'client_zoho_id' => $zoho_id,
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
