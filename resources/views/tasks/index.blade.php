<!DOCTYPE html>
<html>
<head>
    <title>Tarefas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        html, body {
            margin: 0;
            padding: 0;
            /* Remova height: 100% daqui */
        }
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            /* Remova height: 100vh e min-height: 100vh daqui */
            display: flex;
            flex-direction: column;
        }
        .container-todo {
            display: flex;
            flex-direction: column;
            /* Remova height: 90vh e min-height: 400px daqui */
            max-width: 600px;
            margin: 0 auto;
            width: 95vw;
        }
        .tasks-list-container {
            flex: 3;
            height: 55%;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            margin-bottom: 4px;
            z-index: 1;
        }
        ul {
            list-style: none;
            padding: 0;
            width: 100%;
            max-width: 380px;
            min-width: 0;
            margin: 0 auto;
            text-align: left;
            max-height: 320px; /* Altura máxima da lista */
            overflow-y: auto;   /* Só a lista rola */
            background: #fff;
            border-radius: 8px;
            box-sizing: border-box;
        }
        li {
            margin-bottom: 12px;
            padding: 8px;
            border-bottom: 1px solid #eee;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .task-info {
            flex: 1;
            margin-left: 12px;
        }
        .task-checkbox {
            margin-right: 8px;
        }
        .form-box {
            flex: 1;
            border: 1px solid #ccc;
            padding: 16px;
            width: 100%;
            max-width: 380px;
            min-width: 0;
            box-sizing: border-box;
            border-radius: 8px;
            background: #f9f9f9;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-end;
            min-height: 120px;
            margin: 0 auto 48px auto;
            position: relative;
            top: 0;
            z-index: 2;
        }
        .form-box input {
            margin-bottom: 8px;
            width: 100%;
            text-align: left;
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }
        @media (max-width: 500px) {
            .container-todo {
                max-width: 98vw;
                min-width: 0;
            }
            ul, .form-box {
                max-width: 98vw;
                min-width: 0;
            }
        }
    </style>
</head>
<body>
<div id="app">
    <h1>Tarefas</h1>
    <div class="container-todo">
        <div class="tasks-list-container">
            <ul>
                {{-- Tarefas não concluídas --}}
                @foreach ($tasks->where('completed', false) as $task)
                    <li>
                        <form action="{{ route('tasks.update', $task->id) }}" method="POST" style="display: flex; align-items: center; margin: 0; flex: 1;">
                            @csrf
                            @method('PUT')
                            <input
                                type="checkbox"
                                class="task-checkbox"
                                name="completed"
                                value="1"
                                onchange="this.form.submit()"
                                {{ $task->completed ? 'checked' : '' }}
                            >
                            <div class="task-info">
                                <strong>{{ $task->title }}</strong>
                                <br>
                                {{ $task->description }}
                            </div>
                        </form>
                        <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" style="margin-left: 12px;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background: #e74c3c; color: #fff; border: none; border-radius: 4px; padding: 6px 10px; cursor: pointer;">
                                Excluir
                            </button>
                        </form>
                    </li>
                @endforeach

                {{-- Tarefas concluídas --}}
                @foreach ($tasks->where('completed', true) as $task)
                    <li style="opacity: 0.6;">
                        <form action="{{ route('tasks.update', $task->id) }}" method="POST" style="display: flex; align-items: center; margin: 0; flex: 1;">
                            @csrf
                            @method('PUT')
                            <input
                                type="checkbox"
                                class="task-checkbox"
                                name="completed"
                                value="1"
                                onchange="this.form.submit()"
                                {{ $task->completed ? 'checked' : '' }}
                            >
                            <div class="task-info">
                                <strong>{{ $task->title }}</strong>
                                <span style="color: green;">(Completa)</span>
                                <br>
                                {{ $task->description }}
                            </div>
                        </form>
                        <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" style="margin-left: 12px;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background: #e74c3c; color: #fff; border: none; border-radius: 4px; padding: 6px 10px; cursor: pointer;">
                                Excluir
                            </button>
                        </form>
                    </li>
                @endforeach

                @if($tasks->count() == 0)
                    <li>Nenhuma tarefa cadastrada.</li>
                @endif
            </ul>
        </div>
        <!-- Formulário para adicionar nova tarefa -->
        <div class="form-box">
            <form action="{{ route('tasks.store') }}" method="POST" style="width: 100%; display: flex; flex-direction: column; gap: 10px;" onsubmit="return false;">
                @csrf
                <input type="text" name="title" id="title" required placeholder="Título" autocomplete="off" style="margin-bottom: 4px;">
                <input type="text" name="description" id="description" placeholder="Descrição" autocomplete="off" style="margin-bottom: 0; height: 75px;">
            </form>
        </div>
    </div>
</div>
<script>
    // Salva tarefa ao pressionar Enter em qualquer campo do formulário
    document.querySelector('.form-box form').addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            const form = e.target.form;
            if (form.title.value.trim() !== '') {
                form.submit();
            }
        }
    });
</script>
</body>
</html>