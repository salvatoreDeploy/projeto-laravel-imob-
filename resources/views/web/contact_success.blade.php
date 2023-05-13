@extends('web.master.master')

@section('content')

    <div class="container p-5">
        <h2 class="text-center text-front p-3">Seu e-mail foi enviado com sucesso! Em breve entraremos em contato.</h2>
        <div class="d-flex justify-content-center align-items-center">
            <a href="{{ url()->previous() }}" class="text-center">
                <button type="submit" class="btn btn-front">... Continuar navegando!</button>
            </a>
        </div>
    </div>

@endsection
