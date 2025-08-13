<?php
declare(strict_types=1);

namespace App\Dto;

class TotalTimeDto
{
    public function __construct(
        public int $secondsFact,
        public int $secondsCalculated,
    )
    {}
}
