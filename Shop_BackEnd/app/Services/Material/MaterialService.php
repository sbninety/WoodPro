<?php

namespace App\Services\Material;

use App\Repositories\Material\MaterialRepository;

interface MaterialService
{
    public function getRepository(): MaterialRepository;

    public function upsert(array $attributes);
}
