<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class JsonUnescapedResource extends JsonResource
{
    public function jsonOptions()
    {
        $opts = (config('app.debug')) ? JSON_PRETTY_PRINT : 0;

        return $opts | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES;
    }
}
