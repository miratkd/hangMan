<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GameResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $resp=[];
        $word =iconv('UTF-8','ASCII//TRANSLIT',$this->word()->first()->text);
        for ($i=0; $i < Strlen($word); $i++) { 
            if (in_array(strtolower($word[$i]),str_split($this->letters_list))||$word[$i] == ' ') array_push($resp,$word[$i]);
            else array_push($resp,null);
        }
        return [
            'id' => $this->id,
            'category' => $this->word()->first()->category()->first()->name,
            'letters_list' => $this->letters_list,
            'letters_right' => $this->letters_right,
            'is_win' => $this->is_win,
            'word' => $resp
        ];
    }
}
