<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
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
            'title' => $this->title,
            'description' => $this->description,
            'is_completed' => $this->is_completed,
            'status' => $this->is_completed ? 'Completed' : 'Pending',
            'due_date' =>  $this->due_date ? Carbon::parse($this->due_date)->format('M d, Y') : '',
        ];
        // return parent::toArray($request);
    }
}
