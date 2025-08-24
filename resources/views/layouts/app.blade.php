<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Caderno de Erros')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        .main-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            margin: 2rem 0;
        }

        .header {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
            padding: 2rem;
            text-align: center;
            border-radius: 20px 20px 0 0;
        }

        .question-card {
            background: #f8f9ff;
            border-radius: 15px;
            border: none;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .option-btn {
            border-radius: 15px;
            margin: 8px 0;
            padding: 15px 20px;
            border: 2px solid #e9ecef;
            background: white;
            transition: all 0.3s ease;
            text-align: left;
            width: 100%;
        }

        .option-btn:hover {
            border-color: #667eea;
            transform: translateX(5px);
        }

        .option-btn.correct {
            background: #d4edda;
            border-color: #28a745;
            color: #155724;
        }

        .option-btn.incorrect {
            background: #f8d7da;
            border-color: #dc3545;
            color: #721c24;
        }

        .reason-box {
            background: #e7f3ff;
            border-left: 5px solid #2196F3;
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
        }

        .btn-custom {
            border-radius: 25px;
            padding: 12px 30px;
            font-weight: 600;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="main-container">
            <div class="header">
                <h1><i class="fas fa-book-open"></i> Caderno de Erros</h1>
                <p class="mb-0">Sistema Inteligente de Estudos com Questões</p>
            </div>

            <div class="p-4">
                <nav class="nav nav-pills nav-fill mb-4">
                    <a class="nav-link {{ Route::is('quiz') ? 'active' : '' }}" href="{{ route('quiz') }}">
                        <i class="fas fa-brain"></i> Estudar
                    </a>
                    <a class="nav-link {{ Route::is('questions.create') ? 'active' : '' }}"
                        href="{{ route('questions.create') }}">
                        <i class="fas fa-plus-circle"></i> Nova Questão
                    </a>
                    <a class="nav-link" href="{{ route('questions.delete') }}">
                        <i class="fas fa-trash-alt"></i> Excluir Questões
                    </a>

                    <a class="nav-link {{ Route::is('categories.index') ? 'active' : '' }}"
                        href="{{ route('categories.index') }}">
                        <i class="fas fa-tags"></i> Categorias
                    </a>

                    <a class="nav-link {{ Route::is('questions.stats') ? 'active' : '' }}"
                        href="{{ route('questions.stats') }}">
                        <i class="fas fa-chart-bar"></i> Estatísticas
                    </a>

                </nav>

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>

</html>
