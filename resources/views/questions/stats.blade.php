@extends('layouts.app')

@section('content')
<div class="container">
    <h2>📊 Estatísticas</h2>

    <div class="row">
        <div class="col-md-6">
            <h3>❌ Questões mais erradas</h3>
            <ul>
                @foreach($mostWrong as $q)
                    <li>
                        {{ $q->question_text }} <br>
                        Erros: {{ $q->wrong_count }} | Acertos: {{ $q->correct_count }}
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="col-md-6">
            <h3>✅ Questões mais acertadas</h3>
            <ul>
                @foreach($mostCorrect as $q)
                    <li>
                        {{ $q->question_text }} <br>
                        Acertos: {{ $q->correct_count }} | Erros: {{ $q->wrong_count }}
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endsection
