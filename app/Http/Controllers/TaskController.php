<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task as Task; 

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = Task::all();

        // Atualiza status para 'late' se a data for passada e não estiver concluída
        foreach ($tasks as $task) {
            if ($task->date && $task->status !== 'completed' && $task->date->isPast()) {
                if ($task->status !== 'late') {
                    $task->status = 'late';
                    $task->save();
                }
            }
            // Se não está atrasada e não está concluída, volta para 'unfinished'
            if ($task->status === 'late' && $task->date && !$task->date->isPast()) {
                $task->status = 'unfinished';
                $task->save();
            }
        }

        return view('tasks.index', compact('tasks'));
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
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'nullable|date',
        ]);

        $data['status'] = 'unfinished';

        Task::create($data);

        return redirect()->route('tasks.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        // Alterna status entre 'completed' e 'unfinished'
        $task->status = $task->status === 'completed' ? 'unfinished' : 'completed';
        $task->save();

        return redirect()->route('tasks.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $task = Task::findOrFail($id);
        $task->delete();
        return redirect()->route('tasks.index');
    }
}
