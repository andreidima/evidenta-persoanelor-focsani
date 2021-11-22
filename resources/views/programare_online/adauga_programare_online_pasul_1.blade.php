@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row my-5 justify-content-center">
        <div class="col-md-9 p-0">
            <div class="shadow-lg bg-white" style="border-radius: 40px 40px 40px 40px;">
                <div class="p-2 d-flex justify-content-between align-items-end"
                    style="border-radius: 40px 40px 0px 0px; border:2px solid #B0413E">
                    <h3 class="ml-3 my-2" style="color:#B0413E"><i class="fas fa-users fa-lg mr-1"></i>Evidența persoanelor Focșani</h3>
                    {{-- <img src="{{ asset('images/logo.png') }}" height="70" class="mr-3"> --}}
                </div>

                @include ('errors')

                <div class="card-body py-2 text-center"
                    style="
                        color:white;
                        background-color:#B0413E;
                        border-radius: 0px 0px 40px 40px
                    "
                >

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
                    color: white;
                    margin: auto;
                    /* width: 100% !important; */
                    }

                    #lunar td, #lunar th {
                    border: 1px solid #ddd;
                    padding: 8px;
                    text-align: center;
                    }

                    #lunar th {
                    padding-top: 12px;
                    padding-bottom: 12px;
                    }
                    </style>

                    <div class="row p-md-4 justify-content-center">
                        <div class="table-responsive">
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
                                            <td class="" style="background-color:rgb(119, 118, 118)">
                                                {{ $ziua->isoFormat('DD') }}
                                            </td>
                                        @else
                                            <td class="p-0" style="">
                                                    <div class="d-grid" style="height:100%;width:100%">
                                                        {{-- <button class="btn btn-primary" type="button">1</button> --}}
                                                        <a class="btn btn-success btn-block" href="#" role="button">
                                                            {{ $ziua->isoFormat('DD') }}
                                                        </a>
                                                        {{-- {{ $ziua->isoFormat('DD') }} --}}
                                                    </div>
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
