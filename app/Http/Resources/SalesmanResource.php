<?php

namespace App\Http\Resources;

use App\Models\Title;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SalesmanResource extends JsonUnescapedResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $tb = [];
        $ta = [];

        foreach ($this->titles as $t) {
            if ($t->type == Title::TYPE_BEFORE) {
                $tb[] = $t->name;
            } else if ($t->type == Title::TYPE_AFTER) {
                $ta[] = $t->name;
            }
        }

        return [
            'id' => $this->uuid,
            'self' => '/salesmen/'.$this->uuid,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'display_name' => join(" ", array_merge($tb, [$this->first_name, $this->last_name], $ta)),
            'titles_before' => $tb,
            'titles_after' => $ta,
            'prosight_id' => $this->prosight_id,
            'email' => $this->email,
            'phone' => $this->phone,
            'gender' => $this->gender->code,
            'marital_status' => $this->maritalStatus->code ?? null,
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }
}
