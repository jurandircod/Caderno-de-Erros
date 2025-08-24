@extends('layouts.app')

@section('title', 'Estatísticas Avançadas - Caderno de Erros')

@section('content')
<div class="animate-fade-in-up">
    <!-- Page Header -->
    <div class="text-center mb-12">
        <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-r from-cyan-500 to-blue-600 rounded-3xl mb-6 shadow-2xl">
            <i class="fas fa-chart-line text-white text-3xl"></i>
        </div>
        <h2 class="text-5xl font-bold gradient-text mb-4">Estatísticas Avançadas</h2>
        <p class="text-xl text-gray-600 font-medium">Acompanhe seu progresso e desempenho detalhado</p>
    </div>

    <!-- Filter Section -->
    <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-3xl p-8 border border-indigo-100 mb-10 shadow-xl animate-slide-in" style="animation-delay: 0.1s">
        <div class="flex items-center mb-6">
            <div class="w-10 h-10 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center mr-4">
                <i class="fas fa-filter text-white"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-800">Filtros</h3>
        </div>
        
        <form method="GET" class="space-y-6">
            <div class="relative">
                <label for="categories" class="block text-lg font-bold text-gray-700 mb-4">Filtrar por Categoria:</label>
                <div class="relative">
                    <select name="categories[]" 
                            id="categories" 
                            class="w-full px-6 py-4 bg-white border-2 border-indigo-200 rounded-2xl focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all duration-300 text-gray-800 font-medium shadow-sm hover:shadow-md" 
                            multiple>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" 
                                {{ in_array($category->id, $selectedCategories ?? []) ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                        <i class="fas fa-chevron-down text-indigo-400"></i>
                    </div>
                </div>
                <p class="text-sm text-gray-600 mt-2 flex items-center">
                    <i class="fas fa-info-circle mr-2 text-indigo-400"></i>
                    Segure Ctrl (ou Cmd) para selecionar múltiplas categorias
                </p>
            </div>
            <button type="submit" 
                    class="px-8 py-4 bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-2xl font-bold text-lg hover:shadow-xl hover:scale-105 transition-all duration-300 flex items-center space-x-3 shadow-lg">
                <i class="fas fa-filter"></i>
                <span>Aplicar Filtros</span>
            </button>
        </form>
    </div>

    <!-- Summary Section -->
    <div class="bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl border border-white/20 overflow-hidden mb-10 animate-fade-in-up" style="animation-delay: 0.2s">
        <div class="px-8 py-6 bg-gradient-to-r from-gray-700 to-gray-900">
            <h4 class="text-2xl font-bold text-white flex items-center">
                <i class="fas fa-info-circle mr-3"></i>
                Resumo Geral por Categoria
            </h4>
        </div>
        <div class="p-8">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b-2 border-gray-200">
                            <th class="text-left py-4 px-6 font-bold text-gray-800 text-lg">Categoria</th>
                            <th class="text-center py-4 px-6 font-bold text-gray-800 text-lg">Total de Questões</th>
                            <th class="text-center py-4 px-6 font-bold text-emerald-600 text-lg">Acertos</th>
                            <th class="text-center py-4 px-6 font-bold text-red-600 text-lg">Erros</th>
                            <th class="text-center py-4 px-6 font-bold text-blue-600 text-lg">% de Acertos</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $cat)
                            @php
                                $questions = $mostWrong->merge($mostCorrect)->where('category_id', $cat->id);
                                $total = $questions->count();
                                $correct = $questions->sum('correct_count');
                                $wrong = $questions->sum('wrong_count');
                                $percent = $total > 0 ? round(($correct / ($correct+$wrong))*100, 2) : 0;
                            @endphp
                            @if($total > 0)
                            <tr class="border-b border-gray-100 hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 transition-all duration-300 group">
                                <td class="py-4 px-6">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform duration-300">
                                            <i class="fas fa-folder text-white"></i>
                                        </div>
                                        <span class="font-semibold text-gray-800 text-lg">{{ $cat->name }}</span>
                                    </div>
                                </td>
                                <td class="text-center py-4 px-6">
                                    <span class="inline-flex items-center px-3 py-1 bg-gray-100 text-gray-800 rounded-full font-bold">
                                        {{ $total }}
                                    </span>
                                </td>
                                <td class="text-center py-4 px-6">
                                    <span class="inline-flex items-center px-3 py-1 bg-emerald-100 text-emerald-800 rounded-full font-bold">
                                        <i class="fas fa-check mr-1"></i>
                                        {{ $correct }}
                                    </span>
                                </td>
                                <td class="text-center py-4 px-6">
                                    <span class="inline-flex items-center px-3 py-1 bg-red-100 text-red-800 rounded-full font-bold">
                                        <i class="fas fa-times mr-1"></i>
                                        {{ $wrong }}
                                    </span>
                                </td>
                                <td class="text-center py-4 px-6">
                                    <div class="flex items-center justify-center space-x-2">
                                        <div class="w-20 bg-gray-200 rounded-full h-2">
                                            <div class="bg-gradient-to-r from-emerald-500 to-teal-600 h-2 rounded-full transition-all duration-500" 
                                                 style="width: {{ $percent }}%"></div>
                                        </div>
                                        <span class="font-bold text-lg {{ $percent >= 70 ? 'text-emerald-600' : ($percent >= 50 ? 'text-yellow-600' : 'text-red-600') }}">
                                            {{ $percent }}%
                                        </span>
                                    </div>
                                </td>
                            </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-8 mb-10">
        <!-- Most Wrong Questions -->
        <div class="bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl border border-white/20 overflow-hidden animate-fade-in-up" style="animation-delay: 0.3s">
            <div class="px-8 py-6 bg-gradient-to-r from-red-500 to-pink-600">
                <h4 class="text-xl font-bold text-white flex items-center">
                    <i class="fas fa-times-circle mr-3"></i>
                    Questões Mais Erradas
                </h4>
            </div>
            <div class="p-8 max-h-96 overflow-y-auto">
                @if($mostWrong->isEmpty())
                    <div class="text-center py-12">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-check-circle text-gray-400 text-2xl"></i>
                        </div>
                        <p class="text-gray-500 text-lg font-semibold">Nenhuma questão errada registrada!</p>
                        <p class="text-gray-400">Parabéns pelo excelente desempenho!</p>
                    </div>
                @else
                    @foreach($mostWrong->groupBy('category.name') as $categoryName => $questions)
                        <div class="mb-6 last:mb-0">
                            <!-- Usando details/summary nativo -->
                            <details id="wrong-{{ Str::slug($categoryName) }}" class="group rounded-xl border border-red-200 overflow-hidden">
                                <summary class="flex items-center justify-between p-4 bg-gradient-to-r from-red-50 to-pink-50 cursor-pointer list-none">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-gradient-to-r from-red-500 to-pink-600 rounded-lg flex items-center justify-center mr-3">
                                            <i class="fas fa-folder text-white text-sm"></i>
                                        </div>
                                        <div>
                                            <h5 class="font-bold text-gray-800">{{ $categoryName ?? 'Sem Categoria' }}</h5>
                                            <p class="text-sm text-gray-600">{{ $questions->count() }} questões</p>
                                        </div>
                                    </div>

                                    <i class="fas fa-chevron-down text-red-400 transition-transform duration-200 transform group-open:rotate-180"></i>
                                </summary>

                                <div class="px-4 py-3 transition-[max-height,opacity] duration-300 ease-out bg-white">
                                    <div class="space-y-3">
                                        @foreach($questions as $q)
                                            <div class="p-4 bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition-all duration-300">
                                                <div class="flex flex-col space-y-3">
                                                    <h6 class="font-semibold text-gray-800 text-sm leading-relaxed">{{ $q->question_text }}</h6>
                                                    <div class="flex flex-wrap gap-2">
                                                        <span class="inline-flex items-center px-3 py-1 bg-red-100 text-red-800 rounded-full text-xs font-bold">
                                                            <i class="fas fa-times mr-1"></i>
                                                            Erros: {{ $q->wrong_count }}
                                                        </span>
                                                        <span class="inline-flex items-center px-3 py-1 bg-emerald-100 text-emerald-800 rounded-full text-xs font-bold">
                                                            <i class="fas fa-check mr-1"></i>
                                                            Acertos: {{ $q->correct_count }}
                                                        </span>
                                                        <span class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-bold">
                                                            <i class="fas fa-calculator mr-1"></i>
                                                            Total: {{ $q->wrong_count + $q->correct_count }}
                                                        </span>
                                                        <span class="inline-flex items-center px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-bold">
                                                            <i class="fas fa-percentage mr-1"></i>
                                                            Taxa: {{ $q->wrong_count + $q->correct_count > 0 ? round(($q->correct_count/($q->correct_count+$q->wrong_count))*100, 2) : 0 }}%
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </details>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        <!-- Most Correct Questions -->
        <div class="bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl border border-white/20 overflow-hidden animate-fade-in-up" style="animation-delay: 0.4s">
            <div class="px-8 py-6 bg-gradient-to-r from-emerald-500 to-teal-600">
                <h4 class="text-xl font-bold text-white flex items-center">
                    <i class="fas fa-check-circle mr-3"></i>
                    Questões Mais Acertadas
                </h4>
            </div>
            <div class="p-8 max-h-96 overflow-y-auto">
                @if($mostCorrect->isEmpty())
                    <div class="text-center py-12">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-times-circle text-gray-400 text-2xl"></i>
                        </div>
                        <p class="text-gray-500 text-lg font-semibold">Nenhuma questão acertada ainda!</p>
                        <p class="text-gray-400">Continue praticando para ver seus acertos aqui.</p>
                    </div>
                @else
                    @foreach($mostCorrect->groupBy('category.name') as $categoryName => $questions)
                        <div class="mb-6 last:mb-0">
                            <details id="correct-{{ Str::slug($categoryName) }}" class="group rounded-xl border border-emerald-200 overflow-hidden">
                                <summary class="flex items-center justify-between p-4 bg-gradient-to-r from-emerald-50 to-teal-50 cursor-pointer list-none">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-gradient-to-r from-emerald-500 to-teal-600 rounded-lg flex items-center justify-center mr-3">
                                            <i class="fas fa-folder text-white text-sm"></i>
                                        </div>
                                        <div>
                                            <h5 class="font-bold text-gray-800">{{ $categoryName ?? 'Sem Categoria' }}</h5>
                                            <p class="text-sm text-gray-600">{{ $questions->count() }} questões</p>
                                        </div>
                                    </div>

                                    <i class="fas fa-chevron-down text-emerald-400 transition-transform duration-200 transform group-open:rotate-180"></i>
                                </summary>

                                <div class="px-4 py-3 transition-[max-height,opacity] duration-300 ease-out bg-white">
                                    <div class="space-y-3">
                                        @foreach($questions as $q)
                                            <div class="p-4 bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition-all duration-300">
                                                <div class="flex flex-col space-y-3">
                                                    <h6 class="font-semibold text-gray-800 text-sm leading-relaxed">{{ $q->question_text }}</h6>
                                                    <div class="flex flex-wrap gap-2">
                                                        <span class="inline-flex items-center px-3 py-1 bg-emerald-100 text-emerald-800 rounded-full text-xs font-bold">
                                                            <i class="fas fa-check mr-1"></i>
                                                            Acertos: {{ $q->correct_count }}
                                                        </span>
                                                        <span class="inline-flex items-center px-3 py-1 bg-red-100 text-red-800 rounded-full text-xs font-bold">
                                                            <i class="fas fa-times mr-1"></i>
                                                            Erros: {{ $q->wrong_count }}
                                                        </span>
                                                        <span class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-bold">
                                                            <i class="fas fa-calculator mr-1"></i>
                                                            Total: {{ $q->wrong_count + $q->correct_count }}
                                                        </span>
                                                        <span class="inline-flex items-center px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-bold">
                                                            <i class="fas fa-percentage mr-1"></i>
                                                            Taxa: {{ $q->wrong_count + $q->correct_count > 0 ? round(($q->correct_count/($q->correct_count+$q->wrong_count))*100, 2) : 0 }}%
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </details>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

    <!-- Performance Chart -->
    <div class="bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl border border-white/20 overflow-hidden mb-10 animate-fade-in-up" style="animation-delay: 0.5s">
        <div class="px-8 py-6 bg-gradient-to-r from-blue-600 to-purple-700">
            <h4 class="text-2xl font-bold text-white flex items-center">
                <i class="fas fa-chart-bar mr-3"></i>
                Gráficos de Desempenho
            </h4>
        </div>
        <div class="p-8">
            <div class="bg-gradient-to-br from-blue-50 to-purple-50 rounded-2xl p-6 border border-blue-100">
                <canvas id="chartPerformance" class="w-full" height="400"></canvas>
            </div>
        </div>
    </div>

    <!-- Back Button -->
    <div class="flex justify-center animate-fade-in-up" style="animation-delay: 0.6s">
        <a href="{{ route('quiz') }}" 
           class="px-8 py-4 bg-white text-gray-700 rounded-2xl font-bold text-lg border-2 border-gray-200 hover:bg-gray-50 hover:shadow-lg transition-all duration-300 flex items-center space-x-3 hover:scale-105">
            <i class="fas fa-arrow-left"></i>
            <span>Voltar ao Quiz</span>
        </a>
    </div>
</div>

<style>
/* Custom animations */
@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
@keyframes slideIn { from { opacity: 0; transform: translateX(-10px);} to { opacity: 1; transform: translateX(0);} }
.animate-fade-in-up { animation: fadeInUp 0.6s ease-out forwards; }
.animate-slide-in { animation: slideIn 0.4s ease-out forwards; }

.gradient-text {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

/* Custom scrollbar for overflow areas */
.overflow-y-auto::-webkit-scrollbar { width: 6px; }
.overflow-y-auto::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 3px; }
.overflow-y-auto::-webkit-scrollbar-thumb { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 3px; }
.overflow-y-auto::-webkit-scrollbar-thumb:hover { background: linear-gradient(135deg, #5a67d8 0%, #6b46c1 100%); }

/* details/summary styling + animation */
/* remove default marker */
summary::-webkit-details-marker { display: none; }
details summary { list-style: none; outline: none; }

/* visual summary */
details summary { cursor: pointer; }

/* animate inner content using max-height and opacity */
details > div {
    max-height: 0;
    opacity: 0;
    overflow: hidden;
    transition: max-height 0.35s ease, opacity 0.25s ease;
}

/* when open, reveal */
details[open] > div {
    max-height: 2000px; /* alto o suficiente */
    opacity: 1;
}

/* rotate chevron when open using attribute selector */
details[open] .fa-chevron-down {
    transform: rotate(180deg);
}

/* utility to ensure smooth rotation */
.fa-chevron-down { transition: transform 0.2s ease; }
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Preparar dados para gráfico
    const labels = [
        @foreach($categories as $cat)
            "{{ $cat->name }}",
        @endforeach
    ];

    const correctData = [
        @foreach($categories as $cat)
            {{ $mostCorrect->where('category_id', $cat->id)->sum('correct_count') }},
        @endforeach
    ];

    const wrongData = [
        @foreach($categories as $cat)
            {{ $mostWrong->where('category_id', $cat->id)->sum('wrong_count') }},
        @endforeach
    ];

    const ctx = document.getElementById('chartPerformance').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Acertos',
                    data: correctData,
                    backgroundColor: 'rgba(16, 185, 129, 0.8)',
                    borderColor: 'rgba(16, 185, 129, 1)',
                    borderWidth: 2,
                    borderRadius: 8,
                    borderSkipped: false,
                },
                {
                    label: 'Erros',
                    data: wrongData,
                    backgroundColor: 'rgba(239, 68, 68, 0.8)',
                    borderColor: 'rgba(239, 68, 68, 1)',
                    borderWidth: 2,
                    borderRadius: 8,
                    borderSkipped: false,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        font: { size: 14, weight: 'bold' },
                        padding: 20
                    }
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: 'white',
                    bodyColor: 'white',
                    borderColor: 'rgba(255, 255, 255, 0.1)',
                    borderWidth: 1,
                    cornerRadius: 10,
                    displayColors: true,
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ' + context.parsed.y + ' questões';
                        }
                    }
                }
            },
            scales: {
                x: { stacked: true, grid: { display: false }, ticks: { font: { size: 12, weight: 'bold' }, color: '#374151' } },
                y: { stacked: true, beginAtZero: true, grid: { color: 'rgba(156, 163, 175, 0.2)', drawBorder: false }, ticks: { font: { size: 12, weight: 'bold' }, color: '#374151' } }
            },
            animation: { duration: 2000, easing: 'easeInOutQuart' }
        }
    });
</script>
@endsection
