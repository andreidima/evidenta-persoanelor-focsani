@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row my-4 justify-content-center">
        <div class="col-md-9 p-0">
            <div class="shadow-lg bg-white" style="border-radius: 40px 40px 40px 40px;">
                <div class="p-2 d-flex justify-content-between align-items-end"
                    style="
                        border-radius: 40px 40px 0px 0px;
                        border:5px solid #B0413E;
                        color: #ffffff;
                        background-color:#B0413E;
                    "
                >
                    <h3 class="ms-3 my-2" style="color:#ffffff"><i class="fas fa-users fa-lg me-1"></i>Evidența persoanelor Focșani</h3>
                    {{-- <img src="{{ asset('images/logo.png') }}" height="70" class="mr-3"> --}}
                </div>

                @include ('errors')

                <div class="card-body py-2 text-center"
                    style="
                        color:rgb(0, 0, 0);
                        background-color:#ffffff;
                        border:5px solid #B0413E;
                        border-radius: 0px 0px 40px 40px
                    "
                >

                    <h3 class="mb-5 text-center" style="color:#B0413E">
                        Depunerea cererii în vederea eliberării actului de identitate
                    </h3>

                    <h5 class="ps-4 mb-4 text-center">
                        Programări disponibile pentru
                            <b>
                                {{ \Carbon\Carbon::parse($programare_online->data)->dayName }}, {{ \Carbon\Carbon::parse($programare_online->data)->isoFormat('DD MMMM YYYY') }}
                            </b>
                    </h5>

                    <div class="row">
                        <div class="col-lg-6 text-start">
                            Intervale orare disponibile: <b> 08:30-16:30 </b>
                            <br>
                            Legendă:
                            <span class="badge bg-success"><h6 class="mb-0">Perioadă disponibilă</h6></span>
                            <span class="badge bg-danger border border-white"><h6 class="mb-0">Perioadă indisponibilă</h6></span>
                        </div>
                    </div>

                    <br>

                    @php
                    $ora_inceput = \Carbon\Carbon::today();
                    $ora_inceput->hour = 8;
                    $ora_inceput->minute = 30;

                    $ora_sfarsit = \Carbon\Carbon::today();
                    $ora_sfarsit->hour = 16;
                    $ora_sfarsit->minute = 30;


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
                                    <form class="needs-validation mb-0" novalidate method="POST" action="/programari-online/adauga-programare-online-pasul-2">
                                        @csrf

                                            <input type="hidden" id="ora" name="ora" value="{{ $ora_afisare }}">
                                            <button type="submit" name=""
                                                class="btn btn-sm btn-success px-1 mx-1 text-white" style="">
                                                    {{-- <span class="badge bg-success"> --}}
                                                        {{-- <h6 class="mb-0"> --}}
                                                            {{ $ora_afisare->isoFormat('HH') }}:{{ $ora_afisare->isoFormat('mm') }}
                                                            -
                                                            {{ $ora_afisare->addMinutes(15)->isoFormat('HH') }}:{{ $ora_afisare->isoFormat('mm') }}
                                                            {{-- </h6> --}}
                                                    {{-- </span> --}}
                                            </button>

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
                    </div>



                </div>
            </div>
        </div>
    </div>
</div>
