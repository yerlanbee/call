<?php

namespace App\Models;

use App\Dto\TotalTimeDto;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Orchid\Filters\Filterable;

/**
 * @property string $username
 * @property-read Collection $calls
 */
class Operator extends Model
{
    use Filterable;

    protected $table = 'operators';

    protected $fillable = [
        'username',
    ];

    public function calls(): HasMany
    {
        return $this->hasMany(Call::class, 'operator_id');
    }

    /**
     * Считаем по формуле.
     *
     * @return TotalTimeDto
     */
    public function getTotalSecondsByOperator(): TotalTimeDto
    {
        $totalSecondsFact = 0;
        $totalSecondsCalculated = 0;
        $previousEnd = null; // H[i] - H[i-1]

        foreach ($this->calls as $call) {
            $start = Carbon::parse($call->date_time);
            $end = $start->copy()->addSeconds(ceil((float) $call->call_duration)); // Дата и время окончания разговора.

            if ($previousEnd) {
                $gapInSeconds = $previousEnd->diffInSeconds($end);

                if ($gapInSeconds <= 600) {
                    $totalSecondsCalculated += $gapInSeconds;
                }
            }

            $previousEnd = $end;
            $totalSecondsFact += floatval($call->call_duration);
        }

        return new TotalTimeDto($totalSecondsFact, $totalSecondsCalculated);
    }
}
