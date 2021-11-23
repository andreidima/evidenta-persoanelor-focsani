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

                <h5 class="ps-4 mb-2 text-start">
                    Selectați o zi disponibilă din următoarele 2 luni calendaristice:
                </h5>

                @for ($luna = 0; $luna <= 1 ; $luna++)
                    @php
                        $luna_prima_zi = \Carbon\Carbon::today()->addMonth($luna)->startOfMonth();
                        $luna_ultima_zi = \Carbon\Carbon::today()->addMonth($luna)->endOfMonth();
                    @endphp

                    {{-- <div class="d-flex flex-wrap">
                            <div style="width:100px; border:1px solid white">
                                Luni
                            </div>
                            <div style="width:100px; border:1px solid white">
                                Marți
                            </div>
                            <div style="width:100px; border:1px solid white">
                                Miercuri
                            </div>
                            <div style="width:100px; border:1px solid white">
                                Joi
                            </div>
                            <div style="width:100px; border:1px solid white">
                                Vineri
                            </div>
                            <div style="width:100px; border:1px solid white">
                                Sâmbătă
                            </div>
                            <div style="width:100px; border:1px solid white">
                                Duminică
                            </div>
                    </div> --}}


                    <style>
                    #lunar {
                    border-collapse: collapse;
                    color: rgb(151, 0, 0);
                    margin: auto;
                    /* width: 100% !important; */
                    }

                    #lunar td, #lunar th {
                    border: 1px solid rgb(0, 0, 0);
                    padding: 8px;
                    text-align: center;
                    }

                    #lunar th {
                    padding-top: 12px;
                    padding-bottom: 12px;
                    }
                    </style>

                    <div class="row p-md-4 justify-content-center">
                        <div class="table-responsive px-0" style="background-color: rgb(255, 255, 255)">
                            <table class="table align-middle" id="lunar" style="width: 100%">
                                <tr>
                                    <th colspan="7">
                                        {{ ucfirst($luna_prima_zi->isoFormat('MMMM YYYY')) }}
                                    </th>
                                </tr>
                                <tr>
                                    <th width="13%">
                                        Luni
                                    </th>
                                    <th width="13%">
                                        Marți
                                    </th>
                                    <th width="13%">
                                        Miercuri
                                    </th>
                                    <th width="13%">
                                        Joi
                                    </th>
                                    <th width="13%">
                                        Vineri
                                    </th>
                                    <th width="13%">
                                        Sâmbătă
                                    </th>
                                    <th width="13%">
                                        Duminică
                                    </th>
                                </tr>
                                <tr>
                                    @for ($ziua = $luna_prima_zi->startOfWeek(); $ziua < $luna_ultima_zi->endOfWeek(); $ziua->addDay())
                                        @if (($ziua->isMonday() == true) && ($ziua->day > 1))
                                            <tr>
                                        @endif

                                        @if ($ziua->month != \Carbon\Carbon::today()->addMonth($luna)->month)
                                            <td class="" style="">
                                                {{ $ziua->isoFormat('DD') }}
                                            </td>
                                        @elseif (($ziua->isWeekend() == true) || ($ziua->lessThan(\Carbon\Carbon::tomorrow())))
                                            <td class="" style="background-color:rgb(219, 219, 219)">
                                                {{ $ziua->isoFormat('DD') }}
                                            </td>
                                        @else
                                            <td class="p-0" style="">
                                                <form class="needs-validation mb-0" novalidate method="POST" action="/programari-online/adauga-programare-online-pasul-1">
                                                    @csrf
                                                    <div class="d-grid" style="height:100%;width:100%;">
                                                        <input type="hidden" id="data" name="data" value="{{ $ziua }}">
                                                        <button type="submit" name=""
                                                            class="btn btn-success text-white" style="">
                                                            <b>{{ $ziua->isoFormat('DD') }}</b>
                                                        </button>
                                                    </div>
                                                </form>
                                            </td>
                                        @endif

                                        @if ($ziua->isSunday() == true)
                                            </tr>
                                        @endif
                                    @endfor
                                </tr>
                            </table>
                        </div>
                    </div>
                @endfor


                </div>
            </div>
        </div>
    </div>
</div>
