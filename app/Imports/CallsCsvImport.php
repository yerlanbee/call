<?php

namespace App\Imports;

use App\Models\Call;
use App\Models\Operator;
use App\Traits\DateHelper;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CallsCsvImport implements ToModel, WithHeadingRow
{

    public function model(array $row): ?Call
    {
        $seconds = DateHelper::toSecond((float) $row['dlitelnost_razgovora']);
        $operator = Operator::query()->where('username', $row['fio_menedzera'])->first();

        if (!$operator) {
            $operator = Operator::query()->create([
                'username' => $row['fio_menedzera'],
            ]);
        }

        return new Call([
            'operator_id' => $operator->id,
            'call_duration' => $seconds,
            'date' => $row['data'],
            'date_time' => $row['data_i_vremia'],
        ]);

    }
}
