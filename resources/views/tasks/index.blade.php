<!DOCTYPE html>
<html>
<head>
    <title>Tarefas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
            overflow: hidden;
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
            max-height: 55vh; /* Altura máxima da lista */
            min-height: 55vh; /* Altura máxima da lista */
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
            max-width: 230px; /* ajuste conforme necessário */
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
            margin: 8px auto 48px auto; /* diminua ainda mais o valor para aproximar do topo/lista */
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

        /* Sidebar visível */
        #task-sidebar.show {
            right: 0 !important;
        }

        #sidebar-description {
            white-space: pre-line;
            word-break: break-word;
            overflow-wrap: break-word;
        }

        .form-box {
            /* ...existing code... */
            margin: 8px auto 48px auto; /* diminua ainda mais o valor para aproximar do topo/lista */
        }        h1 {
            margin-top: 12px;
            margin-bottom: 12px;
            /* Você pode ajustar os valores conforme preferir */
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
                                @if($task->date)
                                    @php
                                        $today = \Carbon\Carbon::today();
                                        $date = $task->date->copy()->startOfDay();
                                        $diff = $date->diffInDays($today, false);
                                        if ($diff === -1) {
                                            $dateLabel = 'Amanhã';
                                        } elseif ($diff === 0) {
                                            $dateLabel = 'Hoje';
                                        } elseif ($diff === 1) {
                                            $dateLabel = 'Ontem';
                                        } else {
                                            $dateLabel = $task->date->format('d/m/Y');
                                        }
                                    @endphp
                                    <span style="color: #888; font-size: 0.9em;">({{ $dateLabel }})</span>
                                @endif
                                <br>
                                {{ $task->description }}
                            </div>
                        </form>
                        <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" style="margin-left: 12px;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background: #e74c3c; color: #fff; border: none; border-radius: 4px; padding: 6px 10px; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24">
                                    <path fill="currentColor" d="M9 3a3 3 0 0 1 6 0h5a1 1 0 1 1 0 2h-1v15a3 3 0 0 1-3 3H8a3 3 0 0 1-3-3V5H4a1 1 0 1 1 0-2h5Zm8 2H7v15a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V5Zm-5 3a1 1 0 0 1 2 0v8a1 1 0 1 1-2 0V8Zm-4 0a1 1 0 0 1 2 0v8a1 1 0 1 1-2 0V8Z"/>
                                </svg>
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
                                    @php
                                        $today = \Carbon\Carbon::today();
                                        $date = $task->date->copy()->startOfDay();
                                        $diff = $date->diffInDays($today, false);
                                        if ($diff === -1) {
                                            $dateLabel = 'Amanhã';
                                        } elseif ($diff === 0) {
                                            $dateLabel = 'Hoje';
                                        } elseif ($diff === 1) {
                                            $dateLabel = 'Ontem';
                                        } else {
                                            $dateLabel = $task->date->format('d/m/Y');
                                        }
                                    @endphp
                                    <span style="color: #888; font-size: 0.9em;">({{ $dateLabel }})</span>
                                @endif
                                @if($task->status === 'late')
                                    <span style="color: red; font-weight: bold;">[Atrasada]</span>
                                @elseif($task->status === 'completed')
                                    <span style="color: green;">(Completa)</span>
                                @endif
                                <br>
                                {{ $task->description }}
                            </div>
                        </form>
                        <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" style="margin-left: 12px;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background: #e74c3c; color: #fff; border: none; border-radius: 4px; padding: 6px 10px; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24">
                                    <path fill="currentColor" d="M9 3a3 3 0 0 1 6 0h5a1 1 0 1 1 0 2h-1v15a3 3 0 0 1-3 3H8a3 3 0 0 1-3-3V5H4a1 1 0 1 1 0-2h5Zm8 2H7v15a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V5Zm-5 3a1 1 0 0 1 2 0v8a1 1 0 1 1-2 0V8Zm-4 0a1 1 0 0 1 2 0v8a1 1 0 1 1-2 0V8Z"/>
                                </svg>
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
                                @if($task->date)
                                    @php
                                        $today = \Carbon\Carbon::today();
                                        $date = $task->date->copy()->startOfDay();
                                        $diff = $date->diffInDays($today, false);
                                        if ($diff === -1) {
                                            $dateLabel = 'Amanhã';
                                        } elseif ($diff === 0) {
                                            $dateLabel = 'Hoje';
                                        } elseif ($diff === 1) {
                                            $dateLabel = 'Ontem';
                                        } else {
                                            $dateLabel = $task->date->format('d/m/Y');
                                        }
                                    @endphp
                                    <span style="color: #888; font-size: 0.9em;">({{ $dateLabel }})</span>
                                @endif
                                <span style="color: green;">(Completa)</span>
                                <br>
                                {{ $task->description }}
                            </div>
                        </form>
                        <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" style="margin-left: 12px;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background: #e74c3c; color: #fff; border: none; border-radius: 4px; padding: 6px 10px; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24">
                                    <path fill="currentColor" d="M9 3a3 3 0 0 1 6 0h5a1 1 0 1 1 0 2h-1v15a3 3 0 0 1-3 3H8a3 3 0 0 1-3-3V5H4a1 1 0 1 1 0-2h5Zm8 2H7v15a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V5Zm-5 3a1 1 0 0 1 2 0v8a1 1 0 1 1-2 0V8Zm-4 0a1 1 0 0 1 2 0v8a1 1 0 1 1-2 0V8Z"/>
                                </svg>
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
<!-- Sidebar para detalhes da tarefa -->
<div id="task-sidebar" style="
    position: fixed;
    top: 0; right: -400px;
    width: 350px;
    height: 100vh;
    background: #fff;
    box-shadow: -2px 0 12px #0002;
    z-index: 9999;
    transition: right 0.3s;
    padding: 32px 24px 24px 24px;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
">
    <button id="close-sidebar" style="align-self: flex-end; background: none; border: none; font-size: 1.5em; cursor: pointer;">&times;</button>
    <h2 id="sidebar-title"></h2>
    <p id="sidebar-date" style="color: #888; margin: 0 0 8px 0;"></p>
    <p id="sidebar-status" style="font-weight: bold; margin: 0 0 8px 0;"></p>
    <div id="sidebar-description" style="white-space: pre-line;"></div>
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

    // Função para abrir a sidebar com dados da tarefa
    function openSidebar(task) {
        document.getElementById('sidebar-title').textContent = task.title;
        document.getElementById('sidebar-date').textContent = task.date ? 'Data: ' + task.dateLabel : '';
        document.getElementById('sidebar-status').textContent = 'Status: ' + task.statusLabel;
        document.getElementById('sidebar-description').textContent = task.description || '(Sem descrição)';
        document.getElementById('task-sidebar').style.right = '0';
    }
    // Fecha a sidebar
    document.getElementById('close-sidebar').onclick = function() {
        document.getElementById('task-sidebar').style.right = '-400px';
    };

    // Adiciona evento de clique nas tarefas
    document.querySelectorAll('.task-box').forEach(function(box) {
        box.addEventListener('click', function(e) {
            // Evita abrir ao clicar no botão Excluir ou checkbox
            if (e.target.tagName === 'BUTTON' || e.target.type === 'checkbox') return;

            // Busca dados da tarefa nos elementos HTML
            const title = box.querySelector('.task-title')?.textContent || '';
            const description = box.querySelector('.task-info')?.childNodes[box.querySelector('.task-info').childNodes.length-1]?.textContent.trim() || '';
            const dateSpan = box.querySelector('.task-info span');
            const dateLabel = dateSpan ? dateSpan.textContent.replace(/[()]/g, '') : '';
            let statusLabel = '';
            if (box.classList.contains('completed')) statusLabel = 'Completa';
            else if (box.classList.contains('late')) statusLabel = 'Atrasada';
            else statusLabel = 'Pendente';

            openSidebar({
                title: title,
                date: dateLabel,
                dateLabel: dateLabel,
                statusLabel: statusLabel,
                description: description
            });
        });
    });
</script>
<style>
    /* Sidebar visível */
    #task-sidebar.show {
        right: 0 !important;
    }
    @media (max-width: 500px) {
        #task-sidebar {
            width: 98vw;
            padding: 18px 8px 8px 8px;
        }
    }
</style>
</body>
</html>