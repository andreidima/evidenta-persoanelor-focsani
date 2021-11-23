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

                <div class="card-body py-2"
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

                    <div class="row">
                        <div class="col-lg-6 mx-auto">
                            <form  class="needs-validation" novalidate method="POST" action="programari-online/adauga-programare-online-pasul-3">
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
                                            </b>
                                        </label>
                                    </div>
                                </div>
                                <div class="row g-3 align-items-center">
                                    <div class="col-lg-2">
                                        <label for="nume" class="col-form-label">Nume*:</label>
                                    </div>
                                    <div class="col-lg-8">
                                        <input
                                            type="text"
                                            class="form-control form-control-sm rounded-pill {{ $errors->has('nume') ? 'is-invalid' : '' }}"
                                            name="nume"
                                            placeholder=""
                                            value="{{ old('nume', $programare_online->nume) }}"
                                            >
                                    </div>
                                </div>
                                <div class="row g-3 align-items-center">
                                    <div class="col-lg-2">
                                        <label for="email" class="col-form-label">Email:</label>
                                    </div>
                                    <div class="col-lg-8">
                                        <input
                                            type="text"
                                            class="form-control form-control-sm rounded-pill {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                            name="email"
                                            placeholder=""
                                            value="{{ old('email', $programare_online->email) }}"
                                            >
                                    </div>
                                </div>
                                <div class="row g-3 align-items-center">
                                    <div class="col-lg-2">
                                        <label for="cnp" class="col-form-label">CNP*:</label>
                                    </div>
                                    <div class="col-lg-8">
                                        <input
                                            type="text"
                                            class="form-control form-control-sm rounded-pill {{ $errors->has('cnp') ? 'is-invalid' : '' }}"
                                            name="cnp"
                                            placeholder=""
                                            value="{{ old('cnp', $programare_online->cnp) }}"
                                            >
                                    </div>
                                </div>

                                <div class="row g-3">
                                    <div class="col-lg-12 border-start border-warning" style="border-width:5px !important"
                                    >
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" name="gdpr" value="1" required
                                            {{ old('gdpr', ($programare_online->gdpr ?? "0")) === "1" ? 'checked' : '' }}>
                                            <label class="form-check-label" for="gdpr">
                                                * Sunt de acord cu prelucrarea datelor mele personale în conformitate cu Regulamentul (UE) 2016-679 - privind protecţia persoanelor fizice în ceea ce priveşte
                                                prelucrarea datelor cu caracter personal şi privind libera circulaţie a acestor date şi de abrogare a Directivei 95/46/CE ale cărei prevederi le-am citit şi le cunosc.
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-3 py-2 justify-content-center">
                                    <div class="col-lg-5 py-2 d-grid">
                                        <button type="submit" class="btn btn-primary text-white me-2 rounded-pill">Înscrie programarea</button>
                                    </div>
                                    <div class="col-lg-5 py-2 d-grid">
                                        <a class="btn btn-secondary rounded-pill" href="#">Renunță</a>
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
