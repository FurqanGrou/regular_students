<?php

namespace App\Imports;

use App\Models\Country;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CountryImport implements ToModel, WithHeadingRow, WithChunkReading, WithBatchInserts
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $country = trim($row['country']);
        $code    = trim($row['code']);
        $lang = trim($row['lang']);

        if(!is_null($row['country']) && !is_null($row['code']) && !is_null($row['lang'])){

            Country::create([
                'name' => $country,
                'code' => $code,
                'lang'  => $lang,
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
