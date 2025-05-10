<?php
declare(strict_types=1);

namespace App\Models;

use App\Traits\DateHelper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use Orchid\Filters\Filterable;
use Orchid\Filters\Types\Like;
use PhpOffice\PhpSpreadsheet\Shared\Date;

/**
 * @property int $operator_id
 * @property string $call_duration
 * @property string $date
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
     * @param int|null $operator_id
     * @param string|null $date
     * @return Collection
     */
    public static function getByOperatorOrDate(
        ?int $operator_id = null,
        ?string $date = null,
    ): Collection
    {
        return Call::query()
            ->when($operator_id, function ($query, $operator) {
                return $query->where('operator_id', $operator);
            })
            ->when($date, function ($query, $date) {

                $date = Carbon::parse($date);

                $from = DateHelper::toExcel($date->copy()->startOfMonth()->format('d.m.Y'));
                $to = DateHelper::toExcel($date->copy()->endOfMonth()->format('d.m.Y'));

                $query->whereBetween('date', [
                    $from,
                    $to
                ]);
            })
            ->get();
    }

    public function toDate(): string
    {
        return Carbon::instance(Date::excelToDateTimeObject($this->date))->format('d.m.Y');
    }

    public function convertDurationToTime(): string
    {
        return DateHelper::convertDurationToTime((float) $this->call_duration);
    }
}
