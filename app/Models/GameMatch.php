<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use \Datetime;

class GameMatch extends Model
{
    use HasFactory;

    public function word(): BelongsTo
    {
        return $this->belongsTo(Word::class);
    }

    public function isWin() {
        $word =iconv('UTF-8','ASCII//TRANSLIT',$this->word()->first()->text);
        $resp = true;
        foreach (str_split($word) as $letter) {
            if($letter != ' ' && !stristr($this->letters_list,$letter)) $resp = false;
        }
        return $resp;
    }
    public function isTimeout() {
        $created = new DateTime($this->created_at);
        $now = new DateTime();
        return $now->getTimestamp() - $created->getTimestamp() > 600;
    }
}
