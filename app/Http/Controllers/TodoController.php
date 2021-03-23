<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index()
    {
        return view('todo.index', [
            'todos' => Todo::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'   => 'required',
            'content' => 'required',
        ]);


        if ($request->parent == 0) {
            $request->parent = NULL;
        }

        Todo::create(
            [
                'title'   => $request->title,
                'content' => $request->content,
                'parent'  => $request->parent,
            ]

        );

        
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Todo $todo)
    {
        $result = $todo->update(['done' => ($request->get('done') === 'on')]);

        //check if todo has parent
        if ($todo->parent != null) {

            //filter through the children and check done status, count the amount found
            $amountNotDone = $todo->parent()->first()->children->filter(function ($value) {
                return $value['done'] == 0;
            })->count();

            if ($amountNotDone == 0) { //if all the children are set on done...set parent on done
                $todo->parent()->first()->update([
                    'done'  => 1
                ]);
            }
        }

        return response($result, $result ? 200 : 500);
    }
}
