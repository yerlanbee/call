<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Orchid\Filters\Filterable;

/**
 * @property string $username
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
}
