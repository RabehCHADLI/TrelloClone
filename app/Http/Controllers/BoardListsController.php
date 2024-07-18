<?php

namespace App\Http\Controllers;

use App\Models\BoardLists;
use App\Models\Boards;
use Illuminate\Http\Request;

function allForBoard()
{
}
class BoardListsController extends Controller
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
        $position = null;
        $data = $request->all();
        if ($data["title"] === "") {
        } else {

            $listexistantes = BoardLists::all();
            $listexistante = $listexistantes->where('board_id', $data['board_id'])->first();

            if ($listexistante === null) {
                $position = 1;
            } else {
                $position = count($listexistante->get()) + 1;
            }
            $list = new BoardLists();
            $list->title = $data['title'];
            $list->position = $position;
            $list->description = $data['description'];
            $list->board_id = $data['board_id'];
            $list->save([$list]);
            $allboards = Boards::all();
            $board = $allboards->where('id', $data['board_id'])->first();
            $alllist = BoardLists::all();
            $listByBoardId = $alllist->where('board_id', $data['board_id']);

            return redirect(route('boards.show', $data['board_id']));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        return dd($request->all());
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BoardLists $boardLists)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BoardLists $boardLists)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id, Request $request)
    {
        $data = $request->all();
        $boardLists = BoardLists::find($id);
        $boardLists->delete();
        return redirect(route('boards.show', $data['board_id']));
    }
}
