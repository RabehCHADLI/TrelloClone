<?php

namespace App\Http\Controllers;

use App\Models\BoardLists;
use App\Models\Boards;
use App\Models\Cards;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class BoardsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $id = $request->user()->id;
        $allboards = Boards::all();

        $userboards = $allboards->where('user_id', $id);

        return view('boards.index', compact('userboards'));
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
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);

        $request->user()->boards()->create($validated);

        return redirect(route('boards.index'));
    }

    /**
     * Display the specified resource.
     */
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
            $cardsByListId[$list->id] = $allcards->where('board_list_id', $list->id)->sortBy('position');
        }

        return compact('board', 'listByBoardId', 'cardsByListId');
    }
    public function show($id, Request $request)
    {
        $data = $this->returnShow($id, $request);
        $board = $data['board'];
        $listByBoardId = $data['listByBoardId'];
        $cardsByListId = $data['cardsByListId'];

        return view('boards.show', compact('board', 'listByBoardId', 'cardsByListId'));
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id, Request $request)
    {
        $user = Auth::user();
        $UserId = $request->user()->id;
        $allboards = Boards::all();
        $board = $allboards->where('id', $id)->first();
        $userIdBoard = $board->user_id;
        if ($UserId === $userIdBoard) {

            return view('boards.edit', compact('board'));
        } else {
            return 'pas de grujaje ici aller dégage';
        }
    }
    public function update($id, Request $request)
    {

        $UserId = $request->user()->id;
        $data = $request->all();
        $allboards = Boards::all();
        $board = $allboards->where('id', $id)->first();
        $userIdBoard = $board->user_id;
        if ($UserId === $userIdBoard) {

            $board->title = $data['title'];
            $board->description = $data['description'];
            $board->save();
            return redirect(route('boards.index'));
        } else {
            return 'pas de grujaje ici aller dégage';
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $board = Boards::find($id);
        $board->delete();
        return redirect(route('boards.index'));
    }
}
