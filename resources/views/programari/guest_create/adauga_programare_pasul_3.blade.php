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
                        <div class="col-lg-12 mb-3 d-flex justify-content-between align-items-end">
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
                        <div class="col-lg-7 mx-auto">
                            <form  class="mb-0 needs-validation" novalidate method="POST" action="/{{ $serviciu }}/programari/adauga-programare-pasul-3">
                                @csrf

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
                                        <label for="nume" class="col-form-label py-0">Nume{{ ($programare->serviciu == 4 || $programare->serviciu == 5 || $programare->serviciu == 6) ? ' soț' : '' }}*:</label>
                                    </div>
                                    <div class="col-lg-8">
                                        <input
                                            type="text"
                                            class="form-control rounded-pill {{ $errors->has('nume') ? 'is-invalid' : '' }}"
                                            name="nume"
                                            placeholder=""
                                            value="{{ old('nume', $programare->nume) }}"
                                            >
                                    </div>
                                </div>
                                <div class="row g-3 align-items-center mb-3">
                                    <div class="col-lg-3">
                                        <label for="prenume" class="col-form-label py-0">Prenume{{ ($programare->serviciu == 4 || $programare->serviciu == 5 || $programare->serviciu == 6) ? ' soț' : '' }}*:</label>
                                    </div>
                                    <div class="col-lg-8">
                                        <input
                                            type="text"
                                            class="form-control rounded-pill {{ $errors->has('prenume') ? 'is-invalid' : '' }}"
                                            name="prenume"
                                            placeholder=""
                                            value="{{ old('prenume', $programare->prenume) }}"
                                            >
                                    </div>
                                </div>
                                {{-- Transcrieri certificate: initial era martea pentru romani, cu cnp, iar miercurea pentru straini, deci fara cnp --}}
                                {{-- @if (!(($programare->serviciu == 2) && (\Carbon\Carbon::parse($programare->data)->dayOfWeekIso == 3))) --}}
                                @if (!($programare->serviciu == 2))
                                    <div class="row g-3 align-items-center mb-3">
                                        <div class="col-lg-3">
                                            <label for="cnp" class="col-form-label py-0">CNP{{ ($programare->serviciu == 4 || $programare->serviciu == 5 || $programare->serviciu == 6) ? ' soț' : '' }}*:</label>
                                        </div>
                                        <div class="col-lg-8">
                                            <input
                                                type="text"
                                                class="form-control rounded-pill {{ $errors->has('cnp') ? 'is-invalid' : '' }}"
                                                name="cnp"
                                                placeholder=""
                                                value="{{ old('cnp', $programare->cnp) }}"
                                                >
                                        </div>
                                    </div>
                                @endif

                                @if ($programare->serviciu == 4 || $programare->serviciu == 5 || $programare->serviciu == 6)
                                    <div class="row g-3 align-items-center mb-3">
                                        <div class="col-lg-3">
                                            <label for="nume_sotie" class="col-form-label py-0">Nume soție*:</label>
                                        </div>
                                        <div class="col-lg-8">
                                            <input
                                                type="text"
                                                class="form-control rounded-pill {{ $errors->has('nume_sotie') ? 'is-invalid' : '' }}"
                                                name="nume_sotie"
                                                placeholder=""
                                                value="{{ old('nume_sotie', $programare->nume_sotie) }}"
                                                >
                                        </div>
                                    </div>
                                    <div class="row g-3 align-items-center mb-3">
                                        <div class="col-lg-3">
                                            <label for="prenume_sotie" class="col-form-label py-0">Prenume soție*:</label>
                                        </div>
                                        <div class="col-lg-8">
                                            <input
                                                type="text"
                                                class="form-control rounded-pill {{ $errors->has('prenume_sotie') ? 'is-invalid' : '' }}"
                                                name="prenume_sotie"
                                                placeholder=""
                                                value="{{ old('prenume_sotie', $programare->prenume_sotie) }}"
                                                >
                                        </div>
                                    </div>
                                    <div class="row g-3 align-items-center mb-3">
                                        <div class="col-lg-3">
                                            <label for="cnp_sotie" class="col-form-label py-0">CNP soție*:</label>
                                        </div>
                                        <div class="col-lg-8">
                                            <input
                                                type="text"
                                                class="form-control rounded-pill {{ $errors->has('cnp_sotie') ? 'is-invalid' : '' }}"
                                                name="cnp_sotie"
                                                placeholder=""
                                                value="{{ old('cnp_sotie', $programare->cnp_sotie) }}"
                                                >
                                        </div>
                                    </div>
                                    <div class="row g-3 align-items-center mb-3">
                                        <div class="col-lg-3">
                                            <label for="telefon" class="col-form-label py-0">Telefon*:</label>
                                        </div>
                                        <div class="col-lg-8">
                                            <input
                                                type="text"
                                                class="form-control rounded-pill {{ $errors->has('telefon') ? 'is-invalid' : '' }}"
                                                name="telefon"
                                                placeholder=""
                                                value="{{ old('telefon', $programare->telefon) }}"
                                                >
                                        </div>
                                    </div>
                                @endif

                                <div class="row g-3 align-items-center mb-3">
                                    <div class="col-lg-3">
                                        <label for="email" class="col-form-label py-0">Email*:</label>
                                    </div>
                                    <div class="col-lg-8">
                                        <input
                                            type="text"
                                            class="form-control rounded-pill {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                            name="email"
                                            placeholder=""
                                            value="{{ old('email', $programare->email) }}"
                                            >
                                    </div>
                                </div>

                                <div class="row g-3">
                                    <div class="col-lg-12 border-start border-warning" style="border-width:5px !important"
                                    >
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input {{ $errors->has('gdpr') ? 'is-invalid' : '' }}" name="gdpr" id="gdpr" value="1" required
                                            {{ old('gdpr', ($programare->gdpr ?? "0")) === "1" ? 'checked' : '' }}>
                                            <label class="form-check-label" for="gdpr">
                                                * Sunt de acord cu prelucrarea datelor mele personale în conformitate cu Regulamentul (UE) 2016-679 - privind protecţia persoanelor fizice în ceea ce priveşte
                                                prelucrarea datelor cu caracter personal şi privind libera circulaţie a acestor date şi de abrogare a Directivei 95/46/CE ale cărei prevederi le-am citit şi le cunosc.
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-3">
                                    <div class="col-lg-12 border-start border-warning" style="border-width:5px !important"
                                    >
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input {{ $errors->has('acte_necesare') ? 'is-invalid' : '' }}" name="acte_necesare" id="acte_necesare" value="1" required
                                            {{ old('acte_necesare', ($programare->acte_necesare ?? "0")) === "1" ? 'checked' : '' }}>
                                            <label class="form-check-label" for="acte_necesare">
                                                * Am luat la cunoștinţă de ce acte sunt necesare. Toate informațiile se regăsesc pe site-ul <a href="https://evidentapersoanelorfocsani.ro/" target="_blank">www.evidentapersoanelorfocsani.ro</a>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-3 py-2 justify-content-center">
                                    <div class="col-lg-3 py-2 d-grid align-items-center">
                                        <a class="btn btn-primary text-white rounded-pill" href="/{{ $serviciu }}/programari/adauga-programare-pasul-2">Înapoi</a>
                                    </div>
                                    <div class="col-lg-6 py-2 d-grid">
                                        @if (session()->has($serviciu . '-programare-duplicat-in-DB'))
                                            <button type="submit" class="btn btn-warning text-white rounded-pill">
                                                Șterge programarea veche si salvează pe aceasta
                                            </button>
                                        @else
                                            <button type="submit" class="btn btn-success text-white rounded-pill">
                                                Înscrie programarea
                                            </button>
                                        @endif
                                    </div>
                                    <div class="col-lg-3 py-2 d-grid align-items-center">
                                        <a class="btn btn-primary text-white rounded-pill" href="https://evidentapersoanelorfocsani.ro/">Renunță</a>
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
