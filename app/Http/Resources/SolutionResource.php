<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SolutionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'problem_id' => $this->problem_id,
            'code' => $this->code,
            'status' => $this->status,
            'time_taken' => $this->time_taken,
            'output' => $this->output,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
