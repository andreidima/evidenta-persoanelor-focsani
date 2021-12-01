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
</ul>

Mulțumim,<br>
{{ config('app.name') }}


{{-- Footer --}}
    @slot('footer')
        @component('mail::footer')

        @endcomponent
    @endslot
@endcomponent
