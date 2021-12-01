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
                                Depunerea cererii în vederea eliberării actului de identitate
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

                    {{-- <h3 class="mb-5 text-center" style="color:#B0413E">
                        Depunerea cererii în vederea eliberării actului de identitate
                    </h3> --}}

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

                    @php
                    // switch (\Carbon\Carbon::parse($programare->data)->dayOfWeek) {
                    //     case '1':
                    //         $ora_inceput->hour = 8;
                    //         $ora_inceput->minute = 30;
                    //         $ora_sfarsit->hour = 12;
                    //         $ora_sfarsit->minute = 30;
                    //         break;
                    //     case '2':
                    //         $ora_inceput->hour = 8;
                    //         $ora_inceput->minute = 30;
                    //         $ora_sfarsit->hour = 12;
                    //         $ora_sfarsit->minute = 30;
                    //         break;
                    //     case '3':
                    //         $ora_inceput->hour = 12;
                    //         $ora_inceput->minute = 00;
                    //         $ora_sfarsit->hour = 18;
                    //         $ora_sfarsit->minute = 30;
                    //         break;
                    //     case '4':
                    //         $ora_inceput->hour = 8;
                    //         $ora_inceput->minute = 30;
                    //         $ora_sfarsit->hour = 12;
                    //         $ora_sfarsit->minute = 30;
                    //         break;
                    //     case '5':
                    //         $ora_inceput->hour = 8;
                    //         $ora_inceput->minute = 30;
                    //         $ora_sfarsit->hour = 12;
                    //         $ora_sfarsit->minute = 30;
                    //         break;
                    //     default:
                    //         echo 'nu este program';
                    // }

                    $ora_afisare = \Carbon\Carbon::parse($ora_inceput);
                    $ora_afisare = $ora_afisare->startOfHour();
                    // echo $ora_afisare . '<br>' . $ora_inceput;

                    $ora_sfarsit_afisare = \Carbon\Carbon::parse($ora_sfarsit);
                    $ora_sfarsit_afisare = $ora_sfarsit_afisare->endOfHour();
                    @endphp
{{-- {{ $ora_afisare }}{{ $ora_inceput }} --}}
                    <div class="row">
                            @while ($ora_afisare->lessThan($ora_sfarsit_afisare))

                                @if ($ora_afisare->minute == 0)
                                    <div class="col-lg-12 mb-2 d-flex justify-content-center align-items-center">
                                        <b>
                                            Ora {{ $ora_afisare->isoFormat('HH') }} :
                                        </b>
                                @endif
                                @if (($ora_afisare->greaterThanOrEqualTo($ora_inceput)) && ($ora_afisare->lessThan($ora_sfarsit)))
                                    <form class="needs-validation mb-0" novalidate method="POST" action="/programari/adauga-programare-pasul-2">
                                        @csrf

                                            <input type="hidden" id="ora" name="ora" value="{{ $ora_afisare }}">
                                            @if (in_array($ora_afisare->toTimeString(), $ore_disponibile))
                                                <button type="submit" name=""
                                                    class="btn btn-sm btn-success px-1 mx-1 text-white" style="">
                                                        {{ $ora_afisare->isoFormat('HH') }}:{{ $ora_afisare->isoFormat('mm') }}
                                                        -
                                                        {{ $ora_afisare->addMinutes(15)->isoFormat('HH') }}:{{ $ora_afisare->isoFormat('mm') }}
                                                </button>
                                            @else
                                                <button type="submit" name=""
                                                    class="btn btn-sm btn-danger px-1 mx-1 text-white" disabled style="">
                                                        {{ $ora_afisare->isoFormat('HH') }}:{{ $ora_afisare->isoFormat('mm') }}
                                                        -
                                                        {{ $ora_afisare->addMinutes(15)->isoFormat('HH') }}:{{ $ora_afisare->isoFormat('mm') }}
                                                </button>
                                            @endif

                                    </form>
                                @else
                                            <button type="" name=""
                                                class="btn btn-sm btn-secondary px-1 mx-1 text-white" disabled style="">
                                     {{-- <span class="badge" style="background-color: rgb(170, 170, 170)">
                                        <h6 class="mb-0"> --}}
                                            {{ $ora_afisare->isoFormat('HH') }}:{{ $ora_afisare->isoFormat('mm') }}
                                            -
                                            {{ $ora_afisare->addMinutes(15)->isoFormat('HH') }}:{{ $ora_afisare->isoFormat('mm') }}
                                            {{-- </h6>
                                    </span> --}}
                                @endif
                                @if ($ora_afisare->minute == 0)
                                    </div>
                                @endif
                            @endwhile
                            {{-- @for ($ora_afisare; $ora_afisare->lessThan($ora_sfarsit->endOfHour()) ; $ora_afisare->addMinutes(15))
                                <span class="badge bg-success">
                                    <h6 class="mb-0">
                                        {{ $ora_afisare->isoFormat('HH') }}:{{ $ora_afisare->isoFormat('mm') }}
                                        -
                                        {{ $ora_afisare->isoFormat('HH') }}:{{ $ora_afisare->isoFormat('mm') }}
                                        </h6>
                                </span>
                                @if ($ora_afisare->minute == 0)
                                    </div>
                                    <div class="col-lg-12 mb-2">
                                @endif
                            @endfor --}}
                        </div>

                    <div class="row py-2 g-3 justify-content-center">
                        <div class="col-lg-4 d-grid">
                            <a class="btn btn-primary text-white rounded-pill" href="/programari/adauga-programare-pasul-1">Înapoi</a>
                        </div>
                    </div>




                </div>
            </div>
        </div>
    </div>
</div>
@endsection
