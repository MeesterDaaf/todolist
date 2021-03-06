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
            'todos' => Todo::whereNull('parent')->with('children')->get() //get only parent todo's
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

        return redirect()->route('home');
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

        $json = null;
        //check if todo has parent if not, it is a parent
        if ($todo->parent != null) {
            $parent = $todo->parent()->with('children')->first();
            //filter through the children and check done status, count the amount found
            $amountNotDone = $parent->children->filter(function ($value) {
                return $value['done'] == 0;
            })->count();

            if ($amountNotDone == 0) { //if all the children are set on done...set parent on done
                $result =  $parent->update([
                    'done'  => 1
                ]);

                //parent is done.. you get the joke
                $json = $todo->getTheJoke();
            }
        } else {
            //only parent is set to done. So jokes on you
            $json = $todo->getTheJoke();
        }

        return response($json, $result ? 200 : 500);
    }
}
