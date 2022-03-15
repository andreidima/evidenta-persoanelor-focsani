@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row my-4 justify-content-center">
        <div class="col-md-9 p-0">
            <div class="shadow-lg bg-white" style="border-radius: 40px 40px 40px 40px;">
                <div class="p-2"
                    style="
                        border-radius: 40px 40px 0px 0px;
                        border:5px solid #B0413E;
                        color: #ffffff;
                        background-color:#B0413E;
                    "
                >
                    <div class="row">
                        <div class="col-lg-12 mb-4 d-flex justify-content-between align-items-end">
                            <h3 class="ms-3 my-2" style="color:#ffffff"><i class="fas fa-users fa-lg me-1"></i>Evidența persoanelor Focșani</h3>
                            {{-- <img src="{{ asset('images/logo.png') }}" height="70" class="mr-3"> --}}
                        </div>
                        <div class="col-lg-12">
                            <h3 class="mb-0 text-center" style="color:#ffffff">
                                @switch($serviciu)
                                    @case('evidenta-persoanelor')
                                        Depunerea cererii în vederea eliberării actului de identitate
                                        @break
                                    @case('transcrieri-certificate')
                                        Transcrieri certificate
                                        <br>
                                        <small class="fs-5">
                                            Depunere acte/documente în vederea Transcrierii de certificate/extrase emise de autoritățile străine
                                        </small>
                                        @break
                                    @case('casatorii')
                                        Căsătorii
                                        <br>
                                        <small class="fs-5">
                                            Depunere acte necesare în vederea oficierii căsătoriei
                                        </small>
                                        @break
                                    @default
                                @endswitch
                            </h3>
                        </div>
                    </div>
                </div>

                <div class="card-body py-2 text-start"
                    style="
                        color:rgb(0, 0, 0);
                        background-color:#ffffff;
                        border:5px solid #B0413E;
                        border-radius: 0px 0px 40px 40px
                    "
                >

                @include ('errors')

                    <h5 class="ps-4 mb-4 text-center">
                        Programări disponibile pentru
                            <b>
                                {{ \Carbon\Carbon::parse($programare->data)->dayName }}, {{ \Carbon\Carbon::parse($programare->data)->isoFormat('DD MMMM YYYY') }}
                            </b>
                    </h5>

                    <div class="row">
                        <div class="col-lg-12 text-start">
                            Intervale orare disponibile:
                            <b>
                                {{ \Carbon\Carbon::parse($ora_inceput)->isoFormat('HH:mm') }}
                                -
                                {{ \Carbon\Carbon::parse($ora_sfarsit)->isoFormat('HH:mm') }}
                            </b>
                            <br>
                            Legendă:
                            <span class="badge bg-success"><h6 class="mb-0">Perioadă disponibilă</h6></span>
                            <span class="badge border border-white" style="background-color:#ED7C78"><h6 class="mb-0">Perioadă indisponibilă</h6></span>
                            <span class="badge border border-white" style="background-color:#A0A6AB"><h6 class="mb-0">Perioadă în afara programului</h6></span>
                        </div>
                    </div>

                    <br>


                    {{-- Programari evidenta persoanelor: la fiecare 15 minute --}}
                    {{-- Programari casatorii: la fiecare 30 de minute --}}
                    @if(($programare->serviciu == 1) || ($programare->serviciu == 3))
                        @php
                        $ora_afisare = \Carbon\Carbon::parse($ora_inceput);
                        $ora_afisare = $ora_afisare->startOfHour();

                        $ora_sfarsit_afisare = \Carbon\Carbon::parse($ora_sfarsit);
                        $ora_sfarsit_afisare = ($ora_sfarsit_afisare->minute <> 00) ? $ora_sfarsit_afisare->endOfHour() : $ora_sfarsit_afisare;
                        @endphp
                        <div class="row">
                            @while ($ora_afisare->lessThan($ora_sfarsit_afisare))

                                @if ($ora_afisare->minute == 0)
                                    <div class="col-lg-12 mb-2 d-flex justify-content-center align-items-center">
                                        <b>
                                            Ora {{ $ora_afisare->isoFormat('HH') }} :
                                        </b>
                                @endif
                                @if (($ora_afisare->greaterThanOrEqualTo($ora_inceput)) && ($ora_afisare->lessThan($ora_sfarsit)))
                                    <form class="needs-validation mb-0" novalidate method="POST" action="/{{ $serviciu }}/programari/adauga-programare-pasul-2">
                                        @csrf

                                            <input type="hidden" id="ora" name="ora" value="{{ $ora_afisare }}">
                                            @if (in_array($ora_afisare->toTimeString(), $ore_disponibile))
                                                <button type="submit" name=""
                                                    class="btn btn-sm btn-success px-1 mx-1 text-white" style="">
                                                        {{ $ora_afisare->isoFormat('HH') }}:{{ $ora_afisare->isoFormat('mm') }}
                                                        -
                                                        {{-- Programari evidenta persoanelor: la fiecare 15 minute --}}
                                                        {{-- Programari casatorii: la fiecare 30 de minute --}}
                                                        {{ $ora_afisare->addMinutes(($programare->serviciu == 1) ? 15 : (($programare->serviciu == 2) ? 40 : 30))->isoFormat('HH') }}:{{ $ora_afisare->isoFormat('mm') }}
                                                </button>
                                            @else
                                                <button type="submit" name=""
                                                    class="btn btn-sm btn-danger px-1 mx-1 text-white" disabled style="">
                                                        {{ $ora_afisare->isoFormat('HH') }}:{{ $ora_afisare->isoFormat('mm') }}
                                                        -
                                                        {{-- Programari evidenta persoanelor: la fiecare 15 minute --}}
                                                        {{-- Programari casatorii: la fiecare 30 de minute --}}
                                                        {{ $ora_afisare->addMinutes(($programare->serviciu == 1) ? 15 : (($programare->serviciu == 2) ? 40 : 30))->isoFormat('HH') }}:{{ $ora_afisare->isoFormat('mm') }}
                                                </button>
                                            @endif

                                    </form>
                                @else
                                    <button type="" name=""
                                        class="btn btn-sm btn-secondary px-1 mx-1 text-white" disabled style="">
                                            {{ $ora_afisare->isoFormat('HH') }}:{{ $ora_afisare->isoFormat('mm') }}
                                            -
                                            {{-- Programari evidenta persoanelor: la fiecare 15 minute --}}
                                            {{-- Programari casatorii: la fiecare 30 de minute --}}
                                            {{ $ora_afisare->addMinutes(($programare->serviciu == 1) ? 15 : (($programare->serviciu == 2) ? 40 : 30))->isoFormat('HH') }}:{{ $ora_afisare->isoFormat('mm') }}
                                @endif
                                @if ($ora_afisare->minute == 0)
                                    </div>
                                @endif
                            @endwhile
                        </div>

                    {{-- Programari evidenta persoanelor: la fiecare 40 minute - este un alt mod de afisare al orelor, mai simplist, pentru ca sunt si mai putine --}}
                    @elseif($programare->serviciu == 2)
                        @php
                        $ora_afisare = \Carbon\Carbon::parse($ora_inceput);
                        // $ora_afisare = $ora_afisare->startOfHour();

                        $ora_sfarsit_afisare = \Carbon\Carbon::parse($ora_sfarsit);
                        // $ora_sfarsit_afisare = ($ora_sfarsit_afisare->minute <> 00) ? $ora_sfarsit_afisare->endOfHour() : $ora_sfarsit_afisare;
                        @endphp
                        <div class="row">
                            @while ($ora_afisare->lessThan($ora_sfarsit_afisare))

                                {{-- @if ($ora_afisare->minute == 0) --}}
                                    <div class="col-lg-12 mb-2 d-flex justify-content-center align-items-center">
                                        {{-- <b>
                                            Ora {{ $ora_afisare->isoFormat('HH') }} :
                                        </b> --}}
                                {{-- @endif --}}
                                @if (($ora_afisare->greaterThanOrEqualTo($ora_inceput)) && ($ora_afisare->lessThan($ora_sfarsit)))
                                    <form class="needs-validation mb-0" novalidate method="POST" action="/{{ $serviciu }}/programari/adauga-programare-pasul-2">
                                        @csrf

                                            <input type="hidden" id="ora" name="ora" value="{{ $ora_afisare }}">
                                            @if (in_array($ora_afisare->toTimeString(), $ore_disponibile))
                                                <button type="submit" name=""
                                                    class="btn btn-sm btn-success px-1 mx-1 text-white" style="">
                                                        {{ $ora_afisare->isoFormat('HH') }}:{{ $ora_afisare->isoFormat('mm') }}
                                                        -
                                                        {{-- Programari evidenta persoanelor: la fiecare 15 minute --}}
                                                        {{-- Programari casatorii: la fiecare 30 de minute --}}
                                                        {{ $ora_afisare->addMinutes(($programare->serviciu == 1) ? 15 : (($programare->serviciu == 2) ? 40 : 30))->isoFormat('HH') }}:{{ $ora_afisare->isoFormat('mm') }}
                                                </button>
                                            @else
                                                <button type="submit" name=""
                                                    class="btn btn-sm btn-danger px-1 mx-1 text-white" disabled style="">
                                                        {{ $ora_afisare->isoFormat('HH') }}:{{ $ora_afisare->isoFormat('mm') }}
                                                        -
                                                        {{-- Programari evidenta persoanelor: la fiecare 15 minute --}}
                                                        {{-- Programari casatorii: la fiecare 30 de minute --}}
                                                        {{ $ora_afisare->addMinutes(($programare->serviciu == 1) ? 15 : (($programare->serviciu == 2) ? 40 : 30))->isoFormat('HH') }}:{{ $ora_afisare->isoFormat('mm') }}
                                                </button>
                                            @endif

                                    </form>
                                @else
                                    <button type="" name=""
                                        class="btn btn-sm btn-secondary px-1 mx-1 text-white" disabled style="">
                                            {{ $ora_afisare->isoFormat('HH') }}:{{ $ora_afisare->isoFormat('mm') }}
                                            -
                                            {{-- Programari evidenta persoanelor: la fiecare 15 minute --}}
                                            {{-- Programari casatorii: la fiecare 30 de minute --}}
                                            {{ $ora_afisare->addMinutes(($programare->serviciu == 1) ? 15 : (($programare->serviciu == 2) ? 40 : 30))->isoFormat('HH') }}:{{ $ora_afisare->isoFormat('mm') }}
                                @endif
                                {{-- @if ($ora_afisare->minute == 0) --}}
                                    </div>
                                {{-- @endif --}}
                            @endwhile
                        </div>

                    @endif

                    <div class="row py-2 g-3 justify-content-center">
                        <div class="col-lg-4 d-grid">
                            <a class="btn btn-primary text-white rounded-pill" href="/{{ $serviciu }}/programari/adauga-programare-pasul-1">Înapoi</a>
                        </div>
                    </div>




                </div>
            </div>
        </div>
    </div>
</div>
@endsection
