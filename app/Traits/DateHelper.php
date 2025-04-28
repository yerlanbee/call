<?php

namespace App\Traits;

use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class DateHelper
{

    public static function toSecond(float $time): float
    {
        return $time * 86400;
    }

    public static function toExcel(
        string $date
    ): int
    {
        $date = Carbon::createFromFormat('d.m.Y', $date);
        return Date::PHPToExcel($date);
    }

    public static function convertDurationToTime(float $duration): string
    {
        $seconds = round($duration * 86400); // 24 * 60 * 60
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $seconds = $seconds % 60;

        return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
    }
}
