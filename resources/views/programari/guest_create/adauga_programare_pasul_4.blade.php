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
                        <div class="col-lg-12 mb-2 d-flex justify-content-between align-items-end">
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

                <div class="card-body py-2"
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

                    <div class="row">
                        <div class="col-lg-7 py-2 mx-auto">
                            <h5 class="ps-3 py-2 mb-0 text-center alert alert-success">
                                Programarea dumneavoastră a fost înregistrată cu success!
                            </h5>
                        </div>
                        <div class="col-lg-7 mx-auto">
                            <form  class="mb-0 needs-validation" novalidate method="POST" action="/evidenta-persoanelor/programari/adauga-programare-pasul-3">

                                {{-- Serviciul casatorii-oficieri are si locatii, 3 la numar --}}
                                @if($serviciu === 'casatorii-oficieri')
                                <div class="row g-3 align-items-center mb-3">
                                    <div class="col-lg-3">
                                        <label for="locatie" class="col-form-label py-0">Locație:</label>
                                    </div>
                                    <div class="col-lg-8">
                                        <label for="locatie" class="col-form-label py-0">
                                            <b>
                                                @switch($programare->serviciu)
                                                    @case(4)
                                                        Sediul S.P.C.L.E.P. Focșani
                                                        @break
                                                    @case(5)
                                                        Foișorul central din Grădina Publică
                                                        @break
                                                    @case(6)
                                                        Teatrul Municipal Focșani „Mr. Gheorghe Pastia”
                                                        @break
                                                    @default
                                                @endswitch
                                            </b>
                                        </label>
                                    </div>
                                </div>
                                @endif

                                <div class="row g-3 align-items-center mb-3">
                                    <div class="col-lg-3">
                                        <label for="data" class="col-form-label py-0">Data:</label>
                                    </div>
                                    <div class="col-lg-8">
                                        <label for="data" class="col-form-label py-0">
                                            <b>
                                                {{ \Carbon\Carbon::parse($programare->data)->dayName }}, {{ \Carbon\Carbon::parse($programare->data)->isoFormat('DD MMMM YYYY') }}
                                            </b>
                                        </label>
                                    </div>
                                </div>
                                <div class="row g-3 align-items-center mb-3">
                                    <div class="col-lg-3">
                                        <label for="ora" class="col-form-label py-0">Ora:</label>
                                    </div>
                                    <div class="col-lg-8">
                                        <label for="ora" class="col-form-label py-0">
                                            <b>
                                                {{ \Carbon\Carbon::parse($programare->ora)->isoFormat('HH:mm') }}
                                                -
                                                {{-- Programari evidenta persoanelor: la fiecare 15 minute
                                                Programari transcrieri certificate: la fiecare 40 de minute
                                                Programari casatorii: la fiecare 30 de minute
                                                Programari casatorii-oficieri: la fiecare 15 de minute --}}
                                                {{ \Carbon\Carbon::parse($programare->ora)->addMinutes(($programare->serviciu == 1 || $programare->serviciu == 4 || $programare->serviciu == 5 || $programare->serviciu == 6) ? 15 : (($programare->serviciu == 2) ? 40 : 30))->isoFormat('HH:mm') }}
                                            </b>
                                        </label>
                                    </div>
                                </div>
                                <div class="row g-3 align-items-center mb-3">
                                    <div class="col-lg-3">
                                        <label for="nume" class="col-form-label py-0">Nume{{ ($programare->serviciu == 4 || $programare->serviciu == 5 || $programare->serviciu == 6) ? ' soț' : '' }}:</label>
                                    </div>
                                    <div class="col-lg-8">
                                        <label for="nume" class="col-form-label py-0">
                                            <b>
                                                {{ $programare->nume }}
                                            </b>
                                        </label>
                                    </div>
                                </div>
                                <div class="row g-3 align-items-center mb-3">
                                    <div class="col-lg-3">
                                        <label for="prenume" class="col-form-label py-0">Prenume{{ ($programare->serviciu == 4 || $programare->serviciu == 5 || $programare->serviciu == 6) ? ' soț' : '' }}:</label>
                                    </div>
                                    <div class="col-lg-8">
                                        <label for="prenume" class="col-form-label py-0">
                                            <b>
                                                {{ $programare->prenume }}
                                            </b>
                                        </label>
                                    </div>
                                </div>
                                <div class="row g-3 align-items-center mb-3">
                                    <div class="col-lg-3">
                                        <label for="cnp" class="col-form-label py-0">CNP{{ ($programare->serviciu == 4 || $programare->serviciu == 5 || $programare->serviciu == 6) ? ' soț' : '' }}:</label>
                                    </div>
                                    <div class="col-lg-8">
                                        <label for="cnp" class="col-form-label py-0">
                                            <b>
                                                {{ $programare->cnp }}
                                            </b>
                                        </label>
                                    </div>
                                </div>

                                @if ($programare->serviciu == 4 || $programare->serviciu == 5 || $programare->serviciu == 6)
                                    <div class="row g-3 align-items-center mb-3">
                                        <div class="col-lg-3">
                                            <label for="nume_sotie" class="col-form-label py-0">Nume soție:</label>
                                        </div>
                                        <div class="col-lg-8">
                                            <label for="nume_sotie" class="col-form-label py-0">
                                                <b>
                                                    {{ $programare->nume_sotie }}
                                                </b>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="row g-3 align-items-center mb-3">
                                        <div class="col-lg-3">
                                            <label for="prenume_sotie" class="col-form-label py-0">Prenume soție:</label>
                                        </div>
                                        <div class="col-lg-8">
                                            <label for="prenume_sotie" class="col-form-label py-0">
                                                <b>
                                                    {{ $programare->prenume_sotie }}
                                                </b>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="row g-3 align-items-center mb-3">
                                        <div class="col-lg-3">
                                            <label for="cnp_sotie" class="col-form-label py-0">CNP soție:</label>
                                        </div>
                                        <div class="col-lg-8">
                                            <label for="cnp_sotie" class="col-form-label py-0">
                                                <b>
                                                    {{ $programare->cnp_sotie }}
                                                </b>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="row g-3 align-items-center mb-3">
                                        <div class="col-lg-3">
                                            <label for="telefon" class="col-form-label py-0">Telefon:</label>
                                        </div>
                                        <div class="col-lg-8">
                                            <label for="telefon" class="col-form-label py-0">
                                                <b>
                                                    {{ $programare->telefon }}
                                                </b>
                                            </label>
                                        </div>
                                    </div>
                                @endif

                                <div class="row g-3 align-items-center mb-3">
                                    <div class="col-lg-3">
                                        <label for="email" class="col-form-label py-0">Email:</label>
                                    </div>
                                    <div class="col-lg-8">
                                        <label for="email" class="col-form-label py-0">
                                            <b>
                                                {{ $programare->email }}
                                            </b>
                                        </label>
                                    </div>
                                </div>

                                <div class="row g-3 justify-content-center">
                                    <div class="col-lg-12 m-4 text-center">
                                            Dacă doriți, puteți achita taxa online la
                                                <a href="https://www.ghiseul.ro/" target="_blank">
                                                    Ghiseul.ro
                                                </a>
                                    </div>
                                </div>

                                <div class="row g-3 justify-content-center">
                                    <div class="col-lg-12 d-grid">
                                        <a class="btn btn-primary text-white rounded-pill" href="https://evidentapersoanelorfocsani.ro/">Înapoi la site-ul principal</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>


                    <br>




                </div>
            </div>
        </div>
    </div>
</div>
@endsection
