<!DOCTYPE html>
<html>
<head>
    <title>Tarefas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        html, body {
            margin: 0;
            padding: 0;
            height: 100%; /* Adicione esta linha */
        }
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            display: flex;
            flex-direction: column;
            min-height: 100vh; /* Adicione esta linha */
        }
        .container-todo {
            display: flex;
            flex-direction: column;
            max-width: 600px;
            margin: 0 auto;
            width: 95vw;
            flex: 1; /* Adicione esta linha */
            min-height: 80vh; /* Adicione esta linha para garantir altura mínima */
            justify-content: flex-end; /* Adicione esta linha para empurrar o form para baixo */
        }
        .tasks-list-container {
            flex: 1; /* Altere para ocupar o espaço disponível */
            height: auto; /* Remova ou ajuste se necessário */
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
            max-height: 45vh; /* Altura máxima da lista */
            min-height: 45vh; /* Altura máxima da lista */
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
        .task-title {
            display: block;
            max-width: 250px; /* ajuste conforme necessário */
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            vertical-align: middle;
        }
        .task-checkbox {
            margin-right: 8px;
        }
        .form-box {
            flex: none; /* Garante que o form-box não cresça */
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

        /* Novos estilos para as tarefas */
        .task-box {
            background: #fff;
            border: 2px solid #eee;
            border-radius: 8px;
            transition: border-color 0.2s, box-shadow 0.2s;
            box-shadow: 0 1px 4px rgba(0,0,0,0.03);
            padding: 8px;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .task-box:hover {
            background: #f1f1f1;
        }
        .task-box.completed {
            opacity: 0.6;
        }
        .task-box.late {
            background: #ffeaea;
            border-color: #ffa726;
        }
        .task-box.selected {
            border-color: #ffa726;
            box-shadow: 0 0 0 2px #ffa72633;
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
                @foreach ($tasks->where('status', 'unfinished') as $task)
                    <li class="task-box
                        @if($task->status === 'late') late
                        @elseif($task->status === 'completed') completed
                        @endif
                    ">
                        <form action="{{ route('tasks.update', $task->id) }}" method="POST" style="display: flex; align-items: center; margin: 0; flex: 1;">
                            @csrf
                            @method('PUT')
                            <input
                                type="checkbox"
                                class="task-checkbox"
                                name="completed"
                                value="1"
                                onchange="this.form.submit()"
                                {{ $task->status === 'completed' ? 'checked' : '' }}
                            >
                            <div class="task-info">
                                <strong class="task-title">{{ $task->title }}</strong>
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

                {{-- Tarefas atrasadas --}}
                @foreach ($tasks->where('status', 'late') as $task)
                    <li class="task-box
                        @if($task->status === 'late') late
                        @elseif($task->status === 'completed') completed
                        @endif
                    " style="background: #ffeaea;">
                        <form action="{{ route('tasks.update', $task->id) }}" method="POST" style="display: flex; align-items: center; margin: 0; flex: 1;">
                            @csrf
                            @method('PUT')
                            <input
                                type="checkbox"
                                class="task-checkbox"
                                name="completed"
                                value="1"
                                onchange="this.form.submit()"
                                {{ $task->status === 'completed' ? 'checked' : '' }}
                            >
                            <div class="task-info">
                                <strong class="task-title">{{ $task->title }}</strong>
                                @if($task->date)
                                    <span style="color: #888; font-size: 0.9em;">({{ $task->date->format('d/m/Y') }})</span>
                                @endif
                                <span style="color: red; font-weight: bold;">[Atrasada]</span>
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
                @foreach ($tasks->where('status', 'completed') as $task)
                    <li class="task-box
                        @if($task->status === 'late') late
                        @elseif($task->status === 'completed') completed
                        @endif
                    " style="opacity: 0.6;">
                        <form action="{{ route('tasks.update', $task->id) }}" method="POST" style="display: flex; align-items: center; margin: 0; flex: 1;">
                            @csrf
                            @method('PUT')
                            <input
                                type="checkbox"
                                class="task-checkbox"
                                name="completed"
                                value="1"
                                onchange="this.form.submit()"
                                {{ $task->status === 'completed' ? 'checked' : '' }}
                            >
                            <div class="task-info">
                                <strong class="task-title">{{ $task->title }}</strong>
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
            <form action="{{ route('tasks.store') }}" method="POST" style="width: 100%; display: flex; flex-direction: column; gap: 10px;">
                @csrf
                <input type="text" name="title" id="title" required placeholder="Título" autocomplete="off" style="margin-bottom: 4px;">
                <input type="text" name="description" id="description" placeholder="Descrição" autocomplete="off" style="margin-bottom: 0; height: 75px;">
                <div style="display: flex; width: 100%; gap: 8px; align-items: center;">
                    <input type="date" name="date" id="date" placeholder="Data" style="margin-bottom: 0; flex: 1; height: 40px;">
                    <button type="submit" style="margin-top: 0; min-width: 110px; height: 40px;">Adicionar</button>
                </div>
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

    document.querySelectorAll('.task-checkbox').forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            document.querySelectorAll('.task-box').forEach(function(box) {
                box.classList.remove('selected');
            });
            if (checkbox.checked) {
                checkbox.closest('.task-box').classList.add('selected');
            }
        });
        // Também destaca ao focar
        checkbox.addEventListener('focus', function() {
            checkbox.closest('.task-box').classList.add('selected');
        });
        checkbox.addEventListener('blur', function() {
            checkbox.closest('.task-box').classList.remove('selected');
        });
    });
</script>
</body>
</html>