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
    @case('4')
    @case('5')
    @case('6')
        Oficieri Căsătorii
        @break
    @default
@endswitch

<br>

Datele programării dumneavoastră sunt următoarele:
<ul style="margin: 0px">
    <li>
        Nume{{ ($programare->serviciu == 4 || $programare->serviciu == 5 || $programare->serviciu == 6) ? ' soț' : '' }}: {{ $programare->nume }}
    </li>
    <li>
        Prenume{{ ($programare->serviciu == 4 || $programare->serviciu == 5 || $programare->serviciu == 6) ? ' soț' : '' }}: {{ $programare->prenume }}
    </li>
    <li>
        CNP{{ ($programare->serviciu == 4 || $programare->serviciu == 5 || $programare->serviciu == 6) ? ' soț' : '' }}: {{ $programare->cnp }}
    </li>
</ul>

{{-- Doar pentru casatorii-oficieri --}}
@if($programare->serviciu == 4 || $programare->serviciu == 5 || $programare->serviciu == 6)
<ul style="margin: 0px">
    <li>
        Nume soție: {{ $programare->nume_sotie }}
    </li>
    <li>
        Prenume soție: {{ $programare->prenume_sotie }}
    </li>
    <li>
        CNP soție: {{ $programare->cnp_sotie }}
    </li>
    <li>
        Telefon: {{ $programare->telefon }}
    </li>
</ul>
@endif

<ul style="margin: 0px">
    <li>
        Email: {{ $programare->email }}
    </li>
</ul>

{{-- Doar pentru casatorii-oficieri --}}
@switch($programare->serviciu)
    @case(4)
<ul style="margin: 0px">
    <li>
        Locație: Sediul S.P.C.L.E.P. Focșani
    </li>
</ul>
    @break
    @case(5)
<ul style="margin: 0px">
    <li>
        Locație: Foișorul central din Grădina Publică
    </li>
</ul>
    @break
    @case(6)
<ul style="margin: 0px">
    <li>
        Locație: Teatrul Municipal Focșani „Mr. Gheorghe Pastia”
    </li>
</ul>
    @break
    @default
@endswitch

<ul style="margin: 0px">
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
