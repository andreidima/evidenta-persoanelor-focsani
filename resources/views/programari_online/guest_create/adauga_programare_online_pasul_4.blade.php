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
                <h5 class="ps-3 py-2 text-start alert alert-success">
                    Înregistrarea dumneavoastră a fost salvată cu success în baza de date.
                </h5>

                    <div class="row">
                        <div class="col-lg-6 mx-auto">
                            <form  class="mb-0 needs-validation" novalidate method="POST" action="programari-online/adauga-programare-online-pasul-3">
                                <div class="row g-3 align-items-center">
                                    <div class="col-lg-2">
                                        <label for="data" class="col-form-label">Data:</label>
                                    </div>
                                    <div class="col-lg-8">
                                        <label for="data" class="col-form-label">
                                            <b>
                                                {{ \Carbon\Carbon::parse($programare_online->data)->dayName }}, {{ \Carbon\Carbon::parse($programare_online->data)->isoFormat('DD MMMM YYYY') }}
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
                                                {{ \Carbon\Carbon::parse($programare_online->ora)->isoFormat('HH:mm') }}
                                                -
                                                {{ \Carbon\Carbon::parse($programare_online->ora)->addMinutes(15)->isoFormat('HH:mm') }}
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
                                                {{ $programare_online->nume }}
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
                                                {{ $programare_online->email }}
                                            </b>
                                        </label>
                                    </div>
                                </div>
                                <div class="row g-3 align-items-center">
                                    <div class="col-lg-2">
                                        <label for="cnp" class="col-form-label">CNP:</label>
                                    </div>
                                    <div class="col-lg-8">
                                        <label for="cnp" class="col-form-label">
                                            <b>
                                                {{ $programare_online->cnp }}
                                            </b>
                                        </label>
                                    </div>
                                </div>

                                <div class="row g-3 justify-content-center">
                                    <div class="col-lg-12 mb-4">
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
