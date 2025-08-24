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
                            <div class="flex items-start gap-4">
                                <div
                                    class="w-12 h-12 rounded-lg bg-gradient-to-r from-indigo-50 to-purple-50 flex items-center justify-center">
                                    <i class="fas fa-question text-indigo-600"></i>
                                </div>
                                <div>
                                    <p class="text-gray-800 font-semibold leading-relaxed">
                                        {{ Str::limit($question->question_text, 140) }}</p>
                                    <p class="text-sm text-gray-500 mt-1">
                                        {{ $question->category->name ?? 'Sem categoria' }} • ID #{{ $question->id }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center gap-3">
                                <a href=""
                                    class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-indigo-200 rounded-xl shadow-sm hover:shadow-md transition text-indigo-600">
                                    <i class="fas fa-edit"></i>
                                    Editar
                                </a>

                                <!-- botão abre modal -->
                                <button type="button"
                                    class="open-modal inline-flex items-center gap-2 px-4 py-2 rounded-xl font-semibold shadow-md transform hover:scale-[1.02] transition
                                           bg-gradient-to-r from-red-500 to-pink-600 text-white"
                                    data-modal-target="modal-delete-{{ $question->id }}">
                                    <i class="fas fa-trash"></i>
                                    Excluir
                                </button>
                            </div>
                        </div>

                        <!-- Modal (hidden por padrão) -->
                        <div id="modal-delete-{{ $question->id }}"
                            class="modal fixed inset-0 z-50 hidden items-center justify-center px-4">
                            <!-- backdrop -->
                            <div class="modal-backdrop absolute inset-0 bg-black/50 backdrop-blur-sm"></div>

                            <!-- modal content -->
                            <div class="relative w-full max-w-2xl mx-auto">
                                <div
                                    class="bg-white rounded-2xl overflow-hidden shadow-2xl transform transition-all duration-300 translate-y-4 opacity-0 scale-95">
                                    <div
                                        class="p-6 bg-gradient-to-r from-red-500 to-pink-600 text-white flex items-center justify-between">
                                        <div class="flex items-center gap-4">
                                            <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-exclamation-triangle text-white"></i>
                                            </div>
                                            <div>
                                                <h3 class="text-xl font-bold">Confirmar Exclusão</h3>
                                                <p class="text-sm opacity-90">Essa ação não pode ser desfeita.</p>
                                            </div>
                                        </div>
                                        <button type="button"
                                            class="close-modal text-white text-xl font-bold px-3 py-1 rounded-md"
                                            aria-label="Fechar">&times;</button>
                                    </div>

                                    <div class="p-6 bg-white">
                                        <p class="text-gray-700 mb-4">Tem certeza que deseja excluir a seguinte questão?</p>
                                        <div class="p-4 bg-gray-50 border border-gray-100 rounded-lg mb-6">
                                            <p class="text-gray-800 font-semibold">{{ $question->question_text }}</p>
                                            <p class="text-sm text-gray-500 mt-2">
                                                {{ $question->category->name ?? 'Sem categoria' }} • ID #{{ $question->id }}
                                            </p>
                                        </div>

                                        <div class="flex items-center justify-end gap-3">
                                            <button type="button"
                                                class="close-modal px-5 py-3 bg-white border border-gray-200 rounded-lg hover:shadow-sm transition">Cancelar</button>

                                            <form action="{{ route('questions.destroy', $question->id) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="px-5 py-3 rounded-lg text-white font-semibold bg-gradient-to-r from-red-600 to-pink-600 hover:opacity-95 transition-shadow shadow">
                                                    <i class="fas fa-trash-alt mr-2"></i> Excluir permanentemente
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /Modal -->
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
        /* Modal helpers: controlam a animação (mantenha compatível com Tailwind) */
        .modal {
            /* flex utilities aplicadas inline via classe; manter hidden por padrão */
        }

        /* elemento interno que será animado (a div .relative > .bg-white) */
        .modal .relative>div {
            transform-origin: center top;
        }

        /* quando a modal fica visível, removemos hidden e adicionamos classes via JS */
        .modal.show>.relative>div {
            opacity: 1 !important;
            transform: translateY(0) scale(1) !important;
        }

        /* inicial (fechado) — o JS aplica/removerá a classe .show */
        .modal .relative>div[style] {
            /* inline styles are managed by JS for height control if necessary */
        }
    </style>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const openButtons = document.querySelectorAll('.open-modal');
            const closeSelectors = '.close-modal, .modal-backdrop';

            function openModal(modal) {
                if (!modal) return;
                // mostrar modal
                modal.classList.remove('hidden');
                // forçar reflow para permitir animação via classes inline
                const inner = modal.querySelector('.relative > div');
                // aplicar animação inicial
                inner.style.opacity = '0';
                inner.style.transform = 'translateY(12px) scale(.98)';
                // bloquear scroll da página
                document.documentElement.classList.add('overflow-hidden');
                setTimeout(() => {
                    modal.classList.add('show');
                    inner.style.transition = 'all 220ms ease-out';
                    inner.style.opacity = '1';
                    inner.style.transform = 'translateY(0) scale(1)';
                }, 10);

                // foco no primeiro botão (acessibilidade)
                const cancelBtn = modal.querySelector('.close-modal');
                if (cancelBtn) cancelBtn.focus();
            }

            function closeModal(modal) {
                if (!modal) return;
                const inner = modal.querySelector('.relative > div');
                // animação fechamento
                inner.style.transition = 'all 180ms ease-in';
                inner.style.opacity = '0';
                inner.style.transform = 'translateY(8px) scale(.98)';
                // remover classe show após animação
                setTimeout(() => {
                    modal.classList.remove('show');
                    modal.classList.add('hidden');
                    // limpar inline styles
                    inner.style.opacity = '';
                    inner.style.transform = '';
                    inner.style.transition = '';
                    document.documentElement.classList.remove('overflow-hidden');
                }, 190);
            }

            // abrir modal ao clicar no botão
            openButtons.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    const targetId = this.getAttribute('data-modal-target');
                    if (!targetId) return;
                    const modal = document.getElementById(targetId);
                    openModal(modal);
                });
            });

            // delegação para fechar (botão fechar e clique no backdrop)
            document.addEventListener('click', function(e) {
                // fechar via botão/link
                if (e.target.closest('.close-modal')) {
                    const modal = e.target.closest('.modal');
                    closeModal(modal);
                    return;
                }

                // fechar via backdrop
                if (e.target.classList && e.target.classList.contains('modal-backdrop')) {
                    const modal = e.target.closest('.modal');
                    closeModal(modal);
                    return;
                }
            });

            // ESC para fechar última modal aberta
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' || e.key === 'Esc') {
                    const openModalEl = document.querySelector('.modal:not(.hidden)');
                    if (openModalEl) closeModal(openModalEl);
                }
            });

            // prevenir submit acidental por Enter ao focar em botões (opcional)
            document.querySelectorAll('button').forEach(b => b.setAttribute('type', b.getAttribute('type') ||
                'button'));
        });
    </script>
@endsection
