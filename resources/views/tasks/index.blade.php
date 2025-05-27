<!DOCTYPE html>
<html>
<head>
    <title>Lista de Tarefas</title>
</head>
<body>
    <h1>Lista de Tarefas</h1>
    <ul>
        @forelse ($tasks as $task)
            <li>
                <strong>{{ $task->title }}</strong>
                @if($task->completed)
                    (Completa)
                @else
                    (Incompleta)
                @endif
                <br>
                {{ $task->description }}
            </li>
        @empty
            <li>Nenhuma tarefa cadastrada.</li>
        @endforelse
    </ul>
</body>
</html>