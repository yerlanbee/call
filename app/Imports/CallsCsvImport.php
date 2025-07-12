<?php

namespace App\Imports;

use App\Models\Call;
use App\Models\Operator;
use App\Traits\DateHelper;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class CallsCsvImport implements ToModel, WithHeadingRow, WithValidation
{

    public function model(array $row): ?Call
    {
        $seconds = DateHelper::toSecond((float) $row['dlitelnost_razgovora']);
        $dateTime = Carbon::instance(Date::excelToDateTimeObject($row['data_i_vremia']));
        $date = Carbon::instance(Date::excelToDateTimeObject($row['data']));
        $operator = Operator::query()->where('username', $row['fio_menedzera'])->first();

        if (!$operator) {
            $operator = Operator::query()->create([
                'username' => $row['fio_menedzera'],
            ]);
        }

        return new Call([
            'operator_id' => $operator->id,
            'call_duration' => $seconds,
            'date' => $date->format('Y-m-d'),
            'date_time' => $dateTime->format('Y-m-d H:i:s'),
        ]);

    }

    public function rules(): array
    {
        return [
            'fio_menedzera' => 'required|string',
            'dlitelnost_razgovora' => 'required',
            'data_i_vremia' => 'required',
            'data' => 'required',
        ];
    }
}
