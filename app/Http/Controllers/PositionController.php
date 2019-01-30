<?php

namespace App\Http\Controllers;

use App\Position;
use Illuminate\Http\Request;

class PositionController extends Controller
{

    public function showAllPositions()
    {
        return response()->json(Position::all());
    }

    public function showPositionById($id)
    {
        return response()->json(Position::where(['pos_id' => $id])->firstOrFail(), 200);
    }   
}