@extends('layouts.app')

@section('title', 'Excluir Questões - Caderno de Erros')

@section('content')
    <div class="animate-fade-in-up max-w-6xl mx-auto py-8">
        <div class="text-center mb-10">
            <div
                class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-r from-red-500 to-pink-600 rounded-3xl mb-4 shadow-2xl">
                <i class="fas fa-trash-alt text-white text-3xl"></i>
            </div>
            <h1 class="text-4xl font-bold text-gray-800 mb-2">Excluir Questões</h1>
            <p class="text-gray-600">Remova questões que não são mais necessárias — ação irreversível.</p>
        </div>

        <div class="bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl border border-white/20 overflow-hidden p-8">
            @if ($questions->isEmpty())
                <div class="text-center py-12">
                    <div
                        class="w-20 h-20 bg-gradient-to-r from-gray-100 to-gray-200 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner">
                        <i class="fas fa-question text-gray-400 text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-semibold text-gray-800 mb-2">Nenhuma questão cadastrada</h3>
                    <p class="text-gray-500 mb-6">Adicione questões para começar a construir seu banco de estudos.</p>
                    <a href="{{ route('questions.create') }}"
                        class="inline-flex items-center gap-3 px-6 py-3 bg-gradient-to-r from-emerald-500 to-teal-600 text-white rounded-2xl font-semibold shadow-lg hover:scale-[1.02] transition">
                        <i class="fas fa-plus-circle"></i>
                        Criar Nova Questão
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 gap-4">
                    @foreach ($questions as $question)
                        <div
                            class="flex items-center justify-between gap-4 p-4 rounded-2xl border border-gray-100 hover:shadow-md transition">
                            <!-- ... resumo da questão ... -->
                            <div
                                class="flex items-center justify-between gap-4 p-4 rounded-2xl border border-gray-100 hover:shadow-md transition">
                                <div class="flex items-start gap-4">
                                    <div
                                        class="w-12 h-12 rounded-lg bg-gradient-to-r from-indigo-50 to-purple-50 flex items-center justify-center">
                                        <i class="fas fa-question text-indigo-600"></i>
                                    </div>

                                    <div>
                                        <p class="text-gray-800 font-semibold leading-relaxed">
                                            {{ Str::limit($question->question_text, 180) }}
                                        </p>

                                        <p class="text-sm text-gray-500 mt-1">
                                            {{ $question->category->name ?? 'Sem categoria' }} • ID #{{ $question->id }}
                                            <span class="ml-3">• Acertos:
                                                <strong>{{ $question->correct_count }}</strong></span>
                                            <span class="ml-2">• Erros:
                                                <strong>{{ $question->wrong_count }}</strong></span>
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3">
                                    <a href="javascript:void(0)"
                                        class="open-modal inline-flex items-center gap-2 px-4 py-2 bg-white border rounded-xl shadow-sm hover:shadow-md transition text-indigo-600"
                                        data-modal-target="modal-edit-{{ $question->id }}">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>

                                    <button type="button"
                                        class="open-modal inline-flex items-center gap-2 px-4 py-2 rounded-xl font-semibold shadow-md transform hover:scale-[1.02] transition bg-gradient-to-r from-red-500 to-pink-600 text-white"
                                        data-modal-target="modal-delete-{{ $question->id }}">
                                        <i class="fas fa-trash"></i>
                                        Excluir
                                    </button>
                                </div>
                            </div>

                        </div>

                        <!-- Modal Delete (mesma sua estrutura) -->
                        <!-- ... modal-delete-{{ $question->id }} ... -->

                        <!-- Edit Modal -->
                        <!-- START: Edit Modal (substitua o bloco antigo) -->
                         <div id="modal-edit-{{ $question->id }}" class="modal inset-0 hidden z-[9999]">
                            <!-- Backdrop -->
                            <div class="modal-backdrop absolute inset-0 bg-black/60"></div>

                            <!-- Dialog (centro da viewport) -->
                            <div class="modal-wrapper fixed inset-0 flex items-center justify-center px-4">
                                <div
                                    class="modal-dialog relative w-full max-w-4xl max-h-[90vh] bg-white rounded-3xl shadow-2xl overflow-hidden transform transition-all">
                                    <!-- Cabeçalho (sticky) -->
                                    <div
                                        class="modal-header p-6 bg-gradient-to-r from-indigo-500 to-purple-600 text-white flex items-center justify-between sticky top-0 z-10">
                                        <div class="flex items-center gap-4">
                                            <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-edit text-white"></i>
                                            </div>
                                            <div>
                                                <h3 class="text-xl font-bold">Editar Questão</h3>
                                                <p class="text-sm opacity-90">Altere o enunciado, opções, categoria e
                                                    resposta correta.</p>
                                            </div>
                                        </div>

                                        <button type="button" class="close-modal text-white text-2xl font-bold px-3 py-1"
                                            aria-label="Fechar">&times;</button>
                                    </div>

                                    <!-- Corpo (scroll interno) -->
                                    <div class="modal-body p-6 overflow-y-auto" style="max-height:calc(90vh - 120px);">
                                        <form action="{{ route('questions.update', $question->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')

                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Texto da Questão</label>
                                                <textarea name="question_text" rows="3" class="w-full px-4 py-3 border rounded-lg">{{ old('question_text', $question->question_text) }}</textarea>
                                            </div>

                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-3">
                                                <div>
                                                    <label class="form-label fw-bold">Opção A</label>
                                                    <input type="text" name="option_a"
                                                        value="{{ old('option_a', $question->options['a'] ?? '') }}"
                                                        class="w-full px-4 py-3 border rounded-lg">
                                                </div>
                                                <div>
                                                    <label class="form-label fw-bold">Opção B</label>
                                                    <input type="text" name="option_b"
                                                        value="{{ old('option_b', $question->options['b'] ?? '') }}"
                                                        class="w-full px-4 py-3 border rounded-lg">
                                                </div>
                                                <div>
                                                    <label class="form-label fw-bold">Opção C</label>
                                                    <input type="text" name="option_c"
                                                        value="{{ old('option_c', $question->options['c'] ?? '') }}"
                                                        class="w-full px-4 py-3 border rounded-lg">
                                                </div>
                                                <div>
                                                    <label class="form-label fw-bold">Opção D</label>
                                                    <input type="text" name="option_d"
                                                        value="{{ old('option_d', $question->options['d'] ?? '') }}"
                                                        class="w-full px-4 py-3 border rounded-lg">
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Categoria</label>
                                                <select name="category_id" class="w-full px-4 py-3 border rounded-lg">
                                                    <option value="">-- Sem categoria --</option>
                                                    @foreach ($categories as $cat)
                                                        <option value="{{ $cat->id }}"
                                                            {{ $question->category_id == $cat->id ? 'selected' : '' }}>
                                                            {{ $cat->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Resposta Correta</label>
                                                <select name="correct_answer" class="w-full px-4 py-3 border rounded-lg">
                                                    <option value="a"
                                                        {{ $question->correct_answer === 'a' ? 'selected' : '' }}>Opção A
                                                    </option>
                                                    <option value="b"
                                                        {{ $question->correct_answer === 'b' ? 'selected' : '' }}>Opção B
                                                    </option>
                                                    <option value="c"
                                                        {{ $question->correct_answer === 'c' ? 'selected' : '' }}>Opção C
                                                    </option>
                                                    <option value="d"
                                                        {{ $question->correct_answer === 'd' ? 'selected' : '' }}>Opção D
                                                    </option>
                                                </select>
                                            </div>

                                            <div class="mb-4">
                                                <label class="form-label fw-bold">Explicação</label>
                                                <textarea name="reason" rows="3" class="w-full px-4 py-3 border rounded-lg">{{ old('reason', $question->reason) }}</textarea>
                                            </div>

                                            <!-- Footer (fixo/visível) -->
                                            <div
                                                class="modal-footer sticky bottom-0 bg-white p-4 flex items-center justify-end gap-3 border-t">
                                                <button type="button"
                                                    class="close-modal px-4 py-2 border rounded-lg">Cancelar</button>
                                                <button type="submit"
                                                    class="px-5 py-3 rounded-lg bg-indigo-600 text-white">Salvar
                                                    Alterações</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- END: Edit Modal -->
                    @endforeach

                </div>

                <div class="mt-8 text-center">
                    <a href="{{ route('quiz') }}"
                        class="inline-flex items-center gap-3 px-6 py-3 bg-white border border-gray-200 rounded-2xl font-semibold hover:shadow-md transition">
                        <i class="fas fa-arrow-left text-gray-700"></i>
                        Voltar ao Quiz
                    </a>
                </div>
            @endif
        </div>
    </div>

    <style>
        /* Base modal */
        .modal.hidden {
            display: none;
        }

        .modal {
            position: fixed;
            inset: 0;
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* wrapper/dialog */
        .modal-wrapper {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            box-sizing: border-box;
        }

        /* animação do diálogo */
        .modal-dialog {
            transform: translateY(8px) scale(.98);
            opacity: 0;
            transition: all 200ms ease;
        }

        /* quando aberto */
        .modal.is-open .modal-dialog {
            transform: translateY(0) scale(1);
            opacity: 1;
        }

        /* dialog visual (use as suas classes tailwind já existentes ou adapte) */
        .modal-dialog {
            width: 100%;
            max-width: 64rem;
            /* max-w-4xl */
            max-height: 90vh;
            background: #fff;
            border-radius: 1rem;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            overflow: hidden;
        }

        /* corpo do modal rolável */
        .modal-body {
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
            padding: 1.25rem;
            /* p-6 */
            max-height: calc(90vh - 120px);
            /* espaço para header/footer sticky */
        }

        /* header/footer sticky */
        .modal-header {
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .modal-footer {
            position: sticky;
            bottom: 0;
            z-index: 10;
            background: #fff;
        }
    </style>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const openButtons = document.querySelectorAll('.open-modal');
            const closeSelectors = ['.close-modal', '.modal-backdrop'];

            function openModal(modal) {
                if (!modal) return;
                if (modal.classList.contains('is-open')) return;
                modal.classList.remove('hidden');
                // força reflow pra animação
                void modal.offsetWidth;
                modal.classList.add('is-open');
                // foco acessível
                const focusable = modal.querySelector(
                    'input, textarea, select, button, [tabindex]:not([tabindex="-1"])');
                if (focusable) focusable.focus({
                    preventScroll: true
                });
            }

            function closeModal(modal) {
                if (!modal) return;
                modal.classList.remove('is-open');
                setTimeout(() => {
                    modal.classList.add('hidden');
                }, 220); // esperar animação
            }

            openButtons.forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const targetId = btn.getAttribute('data-modal-target');
                    if (!targetId) return;
                    const modal = document.getElementById(targetId);
                    openModal(modal);
                });
            });

            document.addEventListener('click', (e) => {
                const close = e.target.closest(closeSelectors.join(','));
                if (close) {
                    const modal = close.closest('.modal');
                    if (modal) closeModal(modal);
                }
            });

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' || e.key === 'Esc') {
                    const openModalEl = document.querySelector('.modal.is-open');
                    if (openModalEl) closeModal(openModalEl);
                }
            });

            // define tipo padrão para botões dentro de modais (evita submits acidentais)
            document.querySelectorAll('.modal button').forEach(b => {
                if (!b.hasAttribute('type')) b.setAttribute('type', 'button');
            });
        });
    </script>
@show
