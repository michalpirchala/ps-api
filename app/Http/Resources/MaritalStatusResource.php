<?php

namespace App\Http\Resources;

use App\Models\MaritalStatusName;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MaritalStatusResource extends JsonUnescapedResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $names = [];
        foreach ($this->maritalStatusNames as $name){
            $genderCode = (empty($name->gender)) ? "general" : $name->gender->code;
            $names[$genderCode] = $name->name;
        }
        return [
            'code' => $this->code,
            'name' => $names,
        ];
    }
}
