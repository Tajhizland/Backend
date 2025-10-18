<?php

namespace App\Imports;

use App\Models\PhoneBock;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PhoneBockImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        if (empty($row['mobile'])) {
            return null;
        }

        $exists = PhoneBock::where('mobile', $row['mobile'])->exists();

        if (!$exists) {
            return new PhoneBock([
                'name' => $row['name'] ?? null,
                'mobile' => $row['mobile'],
            ]);
        }

        return null;
    }
}
