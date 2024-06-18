<?php

namespace App\Services\Color;

use App\Repositories\Color\ColorRepository;

interface ColorService
{
    public function getRepository(): ColorRepository;

    public function upsert(array $attributes);
}
