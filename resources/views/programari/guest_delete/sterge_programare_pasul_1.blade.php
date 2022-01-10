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
                                        Transcrierii certificate
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

                <div class="card-body py-2"
                    style="
                        color:rgb(0, 0, 0);
                        background-color:#ffffff;
                        border:5px solid #B0413E;
                        border-radius: 0px 0px 40px 40px
                    "
                >

                @include ('errors')

                @if (!is_null($programare))
                    <div class="row">
                        <div class="col-lg-7 py-2 mx-auto">
                            <h5 class="ps-3 py-2 mb-0 text-center alert alert-danger">
                                Doriți să vă ștergeți programarea din sistem?
                            </h5>
                        </div>
                        <div class="col-lg-6 mx-auto">
                            <div class="row g-3 align-items-center">
                                <div class="col-lg-2">
                                    <label for="data" class="col-form-label">Data:</label>
                                </div>
                                <div class="col-lg-8">
                                    <label for="data" class="col-form-label">
                                        <b>
                                            {{ \Carbon\Carbon::parse($programare->data)->dayName }}, {{ \Carbon\Carbon::parse($programare->data)->isoFormat('DD MMMM YYYY') }}
                                        </b>
                                    </label>
                                </div>
                            </div>
                            <div class="row g-3 align-items-center">
                                <div class="col-lg-2">
                                    <label for="ora" class="col-form-label">Ora:</label>
                                </div>
                                <div class="col-lg-8">
                                    <label for="ora" class="col-form-label">
                                        <b>
                                            {{ \Carbon\Carbon::parse($programare->ora)->isoFormat('HH:mm') }}
                                            -
                                            {{-- Programari evidenta persoanelor: la fiecare 15 minute
                                            Programari transcrieri certificate sau casatorii: la fiecare 30 de minute --}}
                                            {{ \Carbon\Carbon::parse($programare->ora)->addMinutes(($programare->serviciu == 1) ? 15 : 30)->isoFormat('HH:mm') }}
                                        </b>
                                    </label>
                                </div>
                            </div>
                            <div class="row g-3 align-items-center">
                                <div class="col-lg-2">
                                    <label for="nume" class="col-form-label">Nume:</label>
                                </div>
                                <div class="col-lg-8">
                                    <label for="nume" class="col-form-label">
                                        <b>
                                            {{ $programare->nume }}
                                        </b>
                                    </label>
                                </div>
                            </div>
                            <div class="row g-3 align-items-center">
                                <div class="col-lg-2">
                                    <label for="prenume" class="col-form-label">Prenume:</label>
                                </div>
                                <div class="col-lg-8">
                                    <label for="prenume" class="col-form-label">
                                        <b>
                                            {{ $programare->prenume }}
                                        </b>
                                    </label>
                                </div>
                            </div>
                            <div class="row g-3 align-items-center">
                                <div class="col-lg-2">
                                    <label for="email" class="col-form-label">Email:</label>
                                </div>
                                <div class="col-lg-8">
                                    <label for="email" class="col-form-label">
                                        <b>
                                            {{ $programare->email }}
                                        </b>
                                    </label>
                                </div>
                            </div>
                            <div class="row g-3 mb-2 align-items-center">
                                <div class="col-lg-2">
                                    <label for="cnp" class="col-form-label">CNP:</label>
                                </div>
                                <div class="col-lg-8">
                                    <label for="cnp" class="col-form-label">
                                        <b>
                                            {{ $programare->cnp }}
                                        </b>
                                    </label>
                                </div>
                            </div>

                            <div class="row g-3 justify-content-center">
                                <form method="POST" action="/{{$serviciu}}/programari/sterge-programare-pasul-1/{{$cheie_unica}}">
                                    @method('DELETE')
                                    @csrf
                                    <div class="col-lg-12 d-grid">
                                        <button
                                            type="submit"
                                            class="btn btn-danger rounded-3 text-white"
                                            >
                                            Șterge Programare
                                        </button>
                                    </div>
                                </form>
                                <div class="col-lg-12 d-grid">
                                    <a class="btn btn-secondary text-white rounded-3" href="https://evidentapersoanelorfocsani.ro/">Înapoi la site-ul principal</a>
                                </div>
                            </div>
                        </div>
                    </div>


                {{-- Programarea nu exista in sistem --}}
                @else
                    <div class="row">
                        <div class="col-lg-7 py-4 mx-auto">
                            <h5 class="ps-3 py-2 mb-0 text-center alert alert-warning">
                                Această programare nu există în sistem!
                            </h5>
                        </div>

                        <div class="col-lg-7 d-grid pb-4 mx-auto">
                            <a class="btn btn-secondary text-white rounded-3" href="https://evidentapersoanelorfocsani.ro/">Înapoi la site-ul principal</a>
                        </div>
                        </div>
                    </div>
                @endif

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
