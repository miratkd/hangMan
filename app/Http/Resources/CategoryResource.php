<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Database\Eloquent\Builder;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "allWords" => $this->words()->count(),
            "finishedWords" => $this->words()->whereHas('matches', function (Builder $query) use ($request) {
                $query->where('user_id', $request->user()->id)->where('is_win',1);
            })->count()
        ];
    }
}
