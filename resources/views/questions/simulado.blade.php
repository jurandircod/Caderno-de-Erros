@extends('layouts.app')

@section('title', "Mini Simulado - {$qtd} questões")

@section('content')
<div class="max-w-5xl mx-auto bg-white/80 backdrop-blur-sm rounded-3xl shadow-2xl p-8">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold">Mini Simulado — {{ $qtd }} questões</h2>

        <div class="flex items-center gap-4">
            <div>
                <label class="text-sm text-gray-600 block">Tempo restante</label>
                <div id="timer" class="text-lg font-bold text-red-600">--:--</div>
            </div>

            <a href="{{ route('quiz') }}" class="text-sm text-gray-600 hover:underline">Voltar</a>
        </div>
    </div>

    <form id="simulado-form">
        @csrf
        <input type="hidden" name="simulado_id" value="{{ $simulado->id ?? ($newSimulado->id ?? '') }}">

        @foreach($questions as $index => $q)
            <div class="mb-6 p-6 rounded-xl border bg-gray-50" data-qid="{{ $q->id }}">
                <div class="mb-3 font-semibold text-lg">{{ $index + 1 }}. {!! $q->question_text !!}</div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @foreach($q->options as $key => $opt)
                        <label class="inline-flex items-center p-3 rounded-lg border hover:shadow-sm cursor-pointer">
                            <input type="radio" name="answers[{{ $q->id }}]" value="{{ $key }}" class="mr-3">
                            <div>
                                <div class="text-sm font-bold">{{ strtoupper($key) }}</div>
                                <div class="text-sm">{{ $opt }}</div>
                            </div>
                        </label>
                    @endforeach
                </div>

                {{-- hidden com o mapa shuffled => original (JSON) --}}
                <input type="hidden" class="shuffled-map" data-qid="{{ $q->id }}" name="shuffled_maps[{{ $q->id }}]" value='@json($q->shuffled_map)'>
                <input type="hidden" name="question_ids[]" value="{{ $q->id }}">
            </div>
        @endforeach

        <div class="text-center">
            <div class="flex items-center justify-center gap-4">
                <button type="button" id="corrigir-btn" class="px-6 py-3 bg-emerald-500 text-white rounded-2xl font-bold">Corrigir Simulado</button>

                @if(isset($simulado) && $simulado)
                    <a id="refazer-btn" href="{{ route('quiz.simulado.refazer', $simulado->id) }}" class="px-4 py-2 bg-indigo-500 text-white rounded-lg">Refazer (mesmas categorias)</a>
                    <a id="export-btn" href="{{ route('quiz.simulado.export', $simulado->id) }}" class="px-4 py-2 bg-gray-800 text-white rounded-lg">Exportar Erradas (CSV)</a>
                @endif
            </div>
        </div>
    </form>

    <div id="resultado" class="mt-8 hidden">
        <div id="summary" class="mb-4 p-4 rounded-lg bg-indigo-50 border-l-4 border-indigo-500"></div>
        <div id="erros-container" class="space-y-4"></div>
    </div>
</div>
@endsection

@section('scripts')
<script>
(() => {
    const totalSeconds = {{ $simulado->time_seconds ?? ($newSimulado->time_seconds ?? ($timerMin ? $timerMin*60 : 0)) }};
    let secondsLeft = totalSeconds || 0;
    const timerEl = document.getElementById('timer');
    const form = document.getElementById('simulado-form');
    const corregirBtn = document.getElementById('corrigir-btn');

    // mostra tempo inicial
    function formatTime(s) {
        const mm = Math.floor(s/60).toString().padStart(2,'0');
        const ss = (s%60).toString().padStart(2,'0');
        return mm + ':' + ss;
    }

    if (secondsLeft > 0) {
        timerEl.textContent = formatTime(secondsLeft);

        const interval = setInterval(() => {
            secondsLeft--;
            timerEl.textContent = formatTime(secondsLeft);

            if (secondsLeft <= 0) {
                clearInterval(interval);
                // auto-submeter para correção com flag tempo esgotado
                submitSimulado(true);
            }
        }, 1000);
    } else {
        timerEl.textContent = 'Sem limite';
    }

    corregirBtn.addEventListener('click', () => submitSimulado(false));

    function collectShuffledMaps() {
        const maps = {};
        document.querySelectorAll('.shuffled-map').forEach(inp => {
            try {
                const qid = inp.dataset.qid;
                const parsed = JSON.parse(inp.value);
                maps[qid] = parsed;
            } catch (e) {
                // ignore se falhar
            }
        });
        return maps;
    }

    function submitSimulado(timeout = false) {
        corregirBtn.disabled = true;
        corregirBtn.textContent = 'Corrigindo...';

        const questionIds = [];
        form.querySelectorAll('input[name="question_ids[]"]').forEach(i => questionIds.push(i.value));

        const answers = {};
        form.querySelectorAll('input[type=radio]').forEach(r => {
            if (r.checked) {
                const m = r.name.match(/^answers\[(\d+)\]$/);
                if (m) answers[m[1]] = r.value;
            }
        });

        const shuffledMaps = collectShuffledMaps();

        const payload = {
            question_ids: questionIds,
            answers: answers,
            shuffled_maps: shuffledMaps,
            simulado_id: form.querySelector('input[name="simulado_id"]').value || null,
            duration_seconds: totalSeconds ? (totalSeconds - secondsLeft) : null,
            timed_out: timeout ? 1 : 0
        };

        fetch("{{ route('quiz.simulado.corrigir') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(payload)
        })
        .then(r => r.json())
        .then(data => {
            corregirBtn.disabled = false;
            corregirBtn.textContent = 'Corrigir Simulado';

            document.getElementById('summary').textContent = `Você acertou ${data.acertos} de ${data.total} — Errou ${data.erradas_count}`;
            const container = document.getElementById('erros-container');
            container.innerHTML = '';

            if (data.erradas.length === 0) {
                container.innerHTML = `<div class="p-6 rounded-lg bg-green-50 border-l-4 border-green-500 text-green-700">Parabéns — você acertou todas!</div>`;
            } else {
                data.erradas.forEach(e => {
                    container.innerHTML += `
                        <div class="p-4 rounded-xl border bg-red-50">
                            <div class="font-bold text-lg mb-2">${e.question}</div>
                            <div class="mb-1"><span class="font-semibold text-red-600">Sua resposta:</span> ${e.resposta}</div>
                            <div class="mb-2"><span class="font-semibold text-green-600">Correta:</span> ${e.correta}</div>
                            <div class="text-sm text-gray-700 mt-2"><strong>Explicação:</strong> ${e.reason}</div>
                        </div>
                    `;
                });
            }

            // mostrar botões de refazer/exportar se veio simulado_id
            if (data.simulado_id) {
                const ref = document.getElementById('refazer-btn');
                const exp = document.getElementById('export-btn');
                if (ref) ref.href = `/quiz/simulado/refazer/${data.simulado_id}`;
                if (exp) exp.href = `/quiz/simulado/${data.simulado_id}/export-erros`;
            }

            document.getElementById('resultado').classList.remove('hidden');
            window.scrollTo({ top: document.getElementById('resultado').offsetTop - 20, behavior: 'smooth' });
        })
        .catch(err => {
            console.error(err);
            corregirBtn.disabled = false;
            corregirBtn.textContent = 'Corrigir Simulado';
            alert('Erro ao corrigir o simulado. Tente novamente.');
        });
    }
})();
</script>
@endsection
