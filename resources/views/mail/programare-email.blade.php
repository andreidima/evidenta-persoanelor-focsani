@component('mail::message')
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            Evidența persoanelor Focșani
        @endcomponent
    @endslot

# Bună ziua {{ $programare->nume ?? ''}},
<br>

Datele programării dumneavoastră sunt următoarele:
<ul>
        <li>
            Nume: {{ $programare->nume }}
        </li>
        <li>
            Email: {{ $programare->email }}
        </li>
        <li>
            CNP: {{ $programare->cnp }}
        </li>
        <li>
            Data: {{ \Carbon\Carbon::parse($programare->data)->dayName }}, {{ \Carbon\Carbon::parse($programare->data)->isoFormat('DD MMMM YYYY') }}
        </li>
        <li>
            Ora: {{ \Carbon\Carbon::parse($programare->ora)->isoFormat('HH:mm') }}
        </li>
</ul>

Mulțumim,<br>
{{ config('app.name') }}


{{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
            <br>
            © {{ date('Y') }} {{ config('app.name') }}
        @endcomponent
    @endslot
@endcomponent