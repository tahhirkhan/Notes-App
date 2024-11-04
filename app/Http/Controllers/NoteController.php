<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function index()
    {
        $notes = Note::query()
            ->where('user_id', request()->user()->id)
            ->orderBy("created_at", "desc")
            ->paginate();
        return view('note.index', ['notes' => $notes]);
    }


    public function create()
    {
        return view('note.create');
    }



    public function store(Request $request)
    {
        $data = $request->validate([
            'note' => ['required', 'string']
        ]);
        $data['user_id'] = $request->user()->id;
        $note = Note::create($data);
        return to_route('note.show', $note)->with('message', 'note created successfully');
    }


    public function show(Note $note)
    {
        if($note->user_id !== request()->user()->id) {
            abort(403,'You are not authorized to view this page');
        }
        return view( 'note.show', ['note' => $note]);
    }


    public function edit(Note $note)
    {
        if($note->user_id !== request()->user()->id) {
            abort(403,'You are not authorized to view this page');
        }
        return view( 'note.edit', ['note' => $note]);
    }


    public function update(Request $request, Note $note)
    {
        if($note->user_id !== request()->user()->id) {
            abort(403,'You are not authorized to view this page');
        }
        $data = $request->validate([
            'note' => ['required', 'string']
        ]);
        
        $note->update($data);
        return to_route('note.show', $note)->with('message', 'note updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function destroy(Note $note)
    {
        if($note->user_id !== request()->user()->id) {
            abort(403,'You are not authorized to view this page');
        }
        $note->delete();
        return to_route('note.index')->with('message', 'note deleted successfully');
    }
}
