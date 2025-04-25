<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
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
}
