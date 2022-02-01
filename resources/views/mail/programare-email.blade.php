@component('mail::layout')
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            Evidența persoanelor Focșani
        @endcomponent
    @endslot

# Bună ziua {{ $programare->nume ?? ''}},
<br>

V-ați programat la serviciul:
@switch($programare->serviciu)
    @case('1')
        Depuneri cereri în vederea eliberării actului de identitate
        @break
    @case('2')
        Transcrieri certificate
        @break
    @case('3')
        Căsătorii
        @break
    @default
@endswitch

<br>

Datele programării dumneavoastră sunt următoarele:
<ul>
        <li>
            Nume: {{ $programare->nume }}
        </li>
        <li>
            Prenume: {{ $programare->prenume }}
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

<br>

Dacă doriți să renunțați la programare, puteți face acest lucru de
<a href="{{ config('app.url') }}/{{
        ($programare->serviciu == '1') ? 'evidenta-persoanelor' : (($programare->serviciu == '2') ? 'transcrieri-certificate' : (($programare->serviciu == '3') ? 'casatorii' : ''))
    }}/programari/sterge-programare-pasul-1/{{$programare->cheie_unica}}">
    aici</a>.
Programările se pot anula până în ziua datei programate.

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
