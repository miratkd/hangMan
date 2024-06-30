<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserFullResource;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use App\Http\Resources\GameListResource;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\GameMatch;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json('Method is not supported for this route',405);
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
    public function store(UserRequest $request)
    {
        if (strpos($request->input('email'), 'outlook')) return response()->json(['message'=>'outlook emails are not alowed'],403);
        return new UserFullResource(User::create($request->all()));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return response()->json('Method is not supported for this route',405);
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
        return response()->json('Method is not supported for this route',405);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return response()->json('Method is not supported for this route',405);
    }

    public function me (Request $request){
        return new UserResource($request->user());
    }
    public function history (Request $request){
        return GameListResource::collection($request->user()->matches()->orderBy('id', 'desc')->paginate(10));
    }

    public function login(LoginRequest $request){
        $credentials = json_decode($request->getContent(), true);
        if (Auth::attempt($credentials)){
            $user = Auth::user();
            return response()->json([
                'message' => 'Token created',
                'type' => 'Bearer',
                'token' => $user->createToken('user-token', [], now()->addHours(6))->plainTextToken,
                'duration' => '6 hours'
            ], 201);
        }
        return response()->json(['error' => 'Wrong credentials'],403);
    }
}
