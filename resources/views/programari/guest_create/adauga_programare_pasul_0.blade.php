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
                                    @case('casatorii-oficieri')
                                        Căsătorii
                                        <br>
                                        <small class="fs-5">
                                            Programare online în vederea oficierii căsătoriei
                                        </small>
                                        @break
                                    @default
                                @endswitch
                            </h3>
                        </div>
                    </div>
                </div>

                <div class="card-body py-4 text-start"
                    style="
                        color:rgb(0, 0, 0);
                        background-color:#ffffff;
                        border:5px solid #B0413E;
                        border-radius: 0px 0px 40px 40px
                    "
                >

                @include ('errors')


                @switch($serviciu)
                    @case('evidenta-persoanelor')

                        @break
                    @case('transcrieri-certificate')

                        @break
                    @case('casatorii')

                        @break
                    @case('casatorii-oficieri')
                        <div class="row">
                            <div class="col-lg-12">
                                <p class="ms-3">Înainte de a vă programa, vă rugăm să citiți acest
                                    <a href="{{ asset('documente/Regulament-privind-programarea-online-in-vederea-oficierii-casatoriei.pdf') }}" target="_blank"><b>REGULAMENT</b></a>.
                                <p class="ms-3">Programarea online se poate înregistra cu cel puțin 14 zile înainte de încheierea căsătoriei.</p>
                                <h5 class="ps-3 alert alert-warning text-start">
                                    Selectați locația în care doriți sa aibă loc oficierea căsătoriei:
                                </h5>
                            </div>
                            <div class="col-lg-4">
                                <form class="needs-validation mb-3" novalidate method="POST" action="/{{ $serviciu }}/programari/adauga-programare-pasul-0">
                                    @csrf
                                    <div class="d-grid gap-0" style="">
                                        <input type="hidden" id="serviciu" name="serviciu" value="4">
                                        <button type="submit" name=""
                                            class="btn btn-primary text-white" style="">
                                            <h5 class="mb-0">Sediul S.P.C.L.E.P. Focșani</h5>
                                        </button>
                                        <table class="fs-6 table table-sm table-striped table-hover mb-0 border">
                                            <tr>
                                                <td class="">
                                                    Luni
                                                </td>
                                                <td class="text-center">
                                                    09:00 - 16:00
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Marți
                                                </td>
                                                <td class="text-center">
                                                    09:00 - 16:00
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="pe-4">
                                                    Miercuri
                                                </td>
                                                <td class="text-center">
                                                    09:00 - 16:00
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Joi
                                                </td>
                                                <td class="text-center">
                                                    09:00 - 16:00
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Vineri
                                                </td>
                                                <td class="text-center">
                                                    09:00 - 16:00
                                                </td>
                                            </tr>
                                        </table>
                                        <button type="submit" name=""
                                            class="btn btn-success text-white" style="">
                                            <h5 class="mb-0">Selectează</h5>
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <div class="col-lg-4">
                                <form class="needs-validation mb-3" novalidate method="POST" action="/{{ $serviciu }}/programari/adauga-programare-pasul-0">
                                    @csrf
                                    <div class="d-grid gap-0" style="">
                                        <input type="hidden" id="serviciu" name="serviciu" value="5">
                                        <button type="submit" name=""
                                            class="btn btn-primary text-white" style="">
                                            <h5 class="mb-0">Foișorul central din Grădina Publică</h5>
                                        </button>
                                        <table class="fs-6 table table-sm table-striped table-hover mb-0 border">
                                            <tr>
                                                <td class="">
                                                    Sâmbătă
                                                </td>
                                                <td class="text-center">
                                                    09:00 - 13:00
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Duminică
                                                </td>
                                                <td class="text-center">
                                                    09:00 - 13:00
                                                </td>
                                            </tr>
                                        </table>
                                        <button type="submit" name=""
                                            class="btn btn-success text-white" style="">
                                            <h5 class="mb-0">Selectează</h5>
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <div class="col-lg-4">
                                <form class="needs-validation mb-3" novalidate method="POST" action="/{{ $serviciu }}/programari/adauga-programare-pasul-0">
                                    @csrf
                                    <div class="d-grid gap-0" style="">
                                        <input type="hidden" id="serviciu" name="serviciu" value="6">
                                        <button type="submit" name=""
                                            class="btn btn-primary text-white" style="">
                                            <h5 class="mb-0">Teatrul Municipal Focșani „Mr. Gheorghe Pastia”</h5>
                                        </button>
                                        <table class="fs-6 table table-sm table-striped table-hover mb-0 border" style="">
                                            <tr>
                                                <td class="">
                                                    Luni
                                                </td>
                                                <td class="text-center">
                                                    09:00 - 15:00
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Marți
                                                </td>
                                                <td class="text-center">
                                                    09:00 - 15:00
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="pe-4">
                                                    Miercuri
                                                </td>
                                                <td class="text-center">
                                                    09:00 - 15:00
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Joi
                                                </td>
                                                <td class="text-center">
                                                    09:00 - 15:00
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Vineri
                                                </td>
                                                <td class="text-center">
                                                    09:00 - 15:00
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="">
                                                    Sâmbătă
                                                </td>
                                                <td class="text-center">
                                                    13:00 - 18:00
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Duminică
                                                </td>
                                                <td class="text-center">
                                                    13:00 - 18:00
                                                </td>
                                            </tr>
                                        </table>
                                        <button type="submit" name=""
                                            class="btn btn-success text-white" style="">
                                            <h5 class="mb-0">Selectează</h5>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        @break
                    @default
                @endswitch




                </div>
            </div>
        </div>
    </div>
</div>
@endsection
