<?php

namespace App\Domain\Repositories;

use App\Domain\Models\Product;

interface Catalog
{
    public function getByProductCode(string $code): ?Product;
}
