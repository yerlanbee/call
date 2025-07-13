<?php
declare(strict_types=1);

namespace App\Models;

use App\Traits\DateHelper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Orchid\Filters\Filterable;
use Orchid\Filters\Types\Like;

/**
 * @property int $operator_id
 * @property string $call_duration
 * @property string $date
 * @property string $date_time
 * @property string $created_at
 * @property-read Operator $operator
 */
class Call extends Model
{
    use Filterable;

    protected $table = 'calls';

    protected $fillable = [
        'operator_id',
        'call_duration',
        'date',
        'date_time',
    ];

    protected $allowedFilters = [
        'username' => Like::class
    ];

    public function operator(): BelongsTo
    {
        return $this->belongsTo(Operator::class);
    }

    /**
     * @param array|null $operatorIds
     * @param string|null $date
     * @return Collection
     */
    public static function getByOperatorOrDate(
        ?array $operatorIds = [],
        ?string $date = null,
    ): Collection
    {
        return Call::query()
            ->when($operatorIds, function ($query, $operator) {
                return $query->whereIn('operator_id', $operator);
            })
            ->when($date, function ($query, $date) {
                $date = Carbon::parse($date);

                $from = $date->copy()->startOfMonth()->format('Y-m-d');
                $to = $date->copy()->endOfMonth()->format('Y-m-d');

                $query->whereBetween('date', [
                    $from,
                    $to
                ]);
            })
            ->get();
    }

    public function toDate(): string
    {
        return Carbon::parse($this->date)->format('d.m.Y');
    }

    public function convertDurationToTime(): string
    {
        return DateHelper::convertDurationToTime((float) $this->call_duration);
    }

    public function toHM(int|float $seconds): string
    {
        $hours = floor($seconds / 3600);
        $minutesOnly = floor(($seconds % 3600) / 60);

        return "{$hours} ч {$minutesOnly} мин";
    }

    public function toM(int|float $seconds): string
    {
        $totalMinutes = floor($seconds / 60);

        return "$totalMinutes мин";
    }

    public function toHMS(int|float $seconds): string
    {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $seconds = $seconds % 60;

        return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
    }
}
