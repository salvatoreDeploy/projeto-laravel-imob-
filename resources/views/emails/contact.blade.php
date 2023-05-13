@component('mail::message')
    # Novo contato feito através do Site Imob

    Contato: {{ $name }}
    E-mail: <{{ $email }}>
    Telefone: {{ $cell }}

    {{ $message }}
@endcomponent
