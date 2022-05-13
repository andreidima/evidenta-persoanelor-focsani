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
                            <div class="col-lg-12 text-center">
                                <h5 class="ps-3 alert alert-warning text-start">
                                    Selectați locația în care doriți sa aibă loc oficierea căsătoriei:
                                </h5>
                                <form class="needs-validation mb-3" novalidate method="POST" action="/{{ $serviciu }}/programari/adauga-programare-pasul-0">
                                    @csrf
                                    <div class="" style="">
                                        <input type="hidden" id="serviciu" name="serviciu" value="4">
                                        <button type="submit" name=""
                                            class="btn btn-success text-white" style="">
                                            <b>Sediul S.P.C.L.E.P Focșani</b>
                                        </button>
                                    </div>
                                </form>
                                <form class="needs-validation mb-3" novalidate method="POST" action="/{{ $serviciu }}/programari/adauga-programare-pasul-0">
                                    @csrf
                                    <div>
                                        <input type="hidden" id="serviciu" name="serviciu" value="5">
                                        <button type="submit" name=""
                                            class="btn btn-success text-white" style="">
                                            <b>Foișorul central din Grădina Publică</b>
                                        </button>
                                    </div>
                                </form>
                                <form class="needs-validation mb-3" novalidate method="POST" action="/{{ $serviciu }}/programari/adauga-programare-pasul-0">
                                    @csrf
                                    <div>
                                        <input type="hidden" id="serviciu" name="serviciu" value="6">
                                        <button type="submit" name=""
                                            class="btn btn-success text-white" style="">
                                            <b>Teatrul Municipal Focșani „Mr. Gheorghe Pastia”</b>
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
