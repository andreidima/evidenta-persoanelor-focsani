@component('mail::layout')
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            Evidența persoanelor Focșani
        @endcomponent
    @endslot

# Bună ziua,
<br>

Doriți să vă programați la serviciul „Transcrieri certificate”
<br>
Pentru adresa de email „{{ $emailDeVerificat->email }}” a fost generat codul <span style="font-size: 120%; font-weight:bold">{{ $emailDeVerificat->cod_validare }}</span>
<br>
Folosiți codul în formularul de pe site.
<br>
Codul este valabil 15 minute, până la {{ \Carbon\Carbon::parse($emailDeVerificat->created_at)->addMinutes(15)->isoFormat('DD.MM.YYYY HH:mm') }}
<br>
<br>

Mulțumim,<br>
{{ config('app.name') }}


{{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
            © {{ date('Y') }} {{ config('app.name') }}
            <br>
            Sistem informatic dezvoltat de <a href="validsoftware.ro" target="_blank">validsoftware.ro</a>
            <br>
            - Servicii Informatice Focșani -
        @endcomponent
    @endslot
@endcomponent
