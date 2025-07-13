<?php

namespace App\Models;

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
     * @return float|int
     */
    public function getTotalSecondsByOperator(): float|int
    {
        $totalSeconds = 0;
        $previousEnd = null; // H[i] - H[i-1]

        foreach ($this->calls as $call) {
            $start = Carbon::parse($call->date_time);
            $end = $start->copy()->addSeconds(ceil((float) $call->call_duration)); // Дата и время окончания разговора.

            if ($previousEnd) {
                $gapInSeconds = $previousEnd->diffInSeconds($end);

                if ($gapInSeconds <= 480) {
                    $totalSeconds += $gapInSeconds;
                }
            }

            $previousEnd = $end;
        }

        return $totalSeconds;
    }
}
