<?php

namespace App\Services\Size;

use App\Repositories\Size\SizeRepository;

interface SizeService
{
    public function getRepository(): SizeRepository;

    public function upsert(array $attributes);
}
