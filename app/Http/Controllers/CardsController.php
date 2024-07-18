<?php

namespace App\Http\Controllers;

use App\Models\BoardLists;
use App\Models\Boards;
use App\Models\Cards;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CardsController extends Controller
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

    public function returnShow($id, Request $request)
    {
        $user = Auth::user();
        $UserId = $request->user()->id;
        $board = Boards::find($id);
        if (!$board) {
            return redirect()->back()->with('error', 'Board not found.');
        }

        $userIdBoard = $board->user_id;

        if ($UserId !== $userIdBoard) {
            return 'pas de grujaje ici aller dégage';
        }

        $listByBoardId = BoardLists::where('board_id', $id)->get();
        $allcards = Cards::whereIn('board_list_id', $listByBoardId->pluck('id'))->get();

        $cardsByListId = [];

        foreach ($listByBoardId as $list) {
            $cardsByListId[$list->id] = $allcards->where('board_list_id', $list->id);
        }

        return compact('board', 'listByBoardId', 'cardsByListId');
    }
    public function store(Request $request)
    {
        $data = $request->all();
        $allCards = Cards::all();
        $cardsByList = $allCards->where('board_list_id', $data['list_id']);
        $position = count($cardsByList) + 1;
        $card = new Cards($data);
        $card->board_list_id =  $data['list_id'];
        $card->position = $position;
        $card->save();



        $id = $data['board_id'];
        $dataShow = $this->returnShow($id, $request);
        $board = $dataShow['board'];
        $listByBoardId = $dataShow['listByBoardId'];
        $cardsByListId = $dataShow['cardsByListId'];


        return redirect(route('boards.show', $id));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {

        $data = $request->all();
        foreach ($data['cardIds'] as $key => $value) {
            $cards = Cards::all();
            $card = $cards->where('id', $value)->first();
            $card->position = $key;
            $card->save();
        }
    }

    public function edit($id, $cardId)
    {
        $cards = Cards::all();
        $card = $cards->where('id', $cardId)->first();
        $card->board_list_id = $id;
        $card->save();
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cards $cards)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id, Request $request)
    {
        $data = $request->all();
        $cards = Cards::find($id);
        $cards->delete();
        return redirect(route('boards.show', $data['board_id']));
    }
}
