<?php

namespace App\Imports;

use App\Models\PhoneBock;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PhoneBockImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new PhoneBock([
            'name' => $row['name'],
            'mobile' => $row['mobile'],
        ]);
    }
}
