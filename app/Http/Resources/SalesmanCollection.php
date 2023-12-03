<?php

namespace App\Http\Resources;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SalesmanCollection extends ResourceCollection
{
    public $preserveAllQueryParameters = true;
    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }
}
