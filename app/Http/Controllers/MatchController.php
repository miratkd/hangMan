<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GameMatch;
use App\Models\Word;
use App\Http\Resources\GameResource;
use App\Http\Resources\GameListResource;
use \Datetime;


class MatchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!$request['categoryId']) return Response()->json(['message'=>'categoryId is required.'], 422);
        $word = Word::where('category_id', $request['categoryId'])->get();
        $game = new GameMatch;
        $game->letters_list='';
        $game->letters_right=0;
        $game->is_win=false;
        $game->user_id=$request->user()->id;
        $game->word_id=$word[mt_rand(0,count($word)-1)]->id;
        $game->save();
        return new GameResource($game);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, Request $request)
    {
        $game = GameMatch::find($id);
        if ($game && $game->user_id != $request->user()->id) return Response()->json('Access Denied',403);
        if ($game) return new GameResource($game);
        return Response()->json('Not found',404);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $game = GameMatch::find($id);
        if (!$game) return Response()->json('Not found',404);
        if ($game && $game->user_id != $request->user()->id) return Response()->json('Access Denied',403);
        if ($game->is_win || Strlen($game->letters_list) - $game->letters_right > 5) return Response()->json('Sorry, this match is over',400);
        if (in_array($request['letter'], str_split($game->letters_list))) return Response()->json('Sorry, you already chose this letter',400);
        if($game->isTimeout()) return Response()->json('Sorry, this match is over',400);
        if (in_array($request['letter'], str_split(strtolower(iconv('UTF-8','ASCII//TRANSLIT',$game->word()->first()->text))))) $game->letters_right++;
        $game->letters_list = $game->letters_list . $request['letter'];
        $game->is_win = $game->isWin();
        $game->save();
        return new GameResource($game);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function getAvaibleMatches(Request $request)
    {
        $now = strtotime("-10 minutes");
        $query = GameMatch::where('user_id',$request->user()->id)->where('is_win',false)->whereDate('created_at', '>=', date("Y-m-d", $now))->get();
        $resp = [];
        foreach ($query as $game) if (!$game->isTimeout() && Strlen($game->letters_list) - $game->letters_right <= 5) array_push($resp, $game);
        return GameListResource::collection($resp);
    }
}
