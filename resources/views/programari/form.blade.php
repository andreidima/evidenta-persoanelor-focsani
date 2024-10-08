@csrf

<div class="row mb-0 p-3 d-flex border-radius: 0px 0px 40px 40px" id="app1">
    <div class="col-lg-12 mb-0">

        {{-- Trimiterea tipului de serviciu --}}
        @switch($serviciu)
            @case('evidenta-persoanelor')
                    <script type="application/javascript">
                        serviciu=1
                    </script>
                    @php
                        $programare->serviciu = 1;
                    @endphp
                @break
            @case('transcrieri-certificate')
                    <script type="application/javascript">
                        serviciu=2
                    </script>
                    @php
                        $programare->serviciu = 2;
                    @endphp
                @break
            @case('casatorii')
                    <script type="application/javascript">
                        serviciu=3
                    </script>
                    @php
                        $programare->serviciu = 3;
                    @endphp
                @break
            @case('casatorii-oficieri-sediu')
                    <script type="application/javascript">
                        serviciu=4
                    </script>
                    @php
                        $programare->serviciu = 4;
                    @endphp
                @break
            @case('casatorii-oficieri-foisor')
                    <script type="application/javascript">
                        serviciu=5
                    </script>
                    @php
                        $programare->serviciu = 5;
                    @endphp
                @break
            @case('casatorii-oficieri-teatru')
                    <script type="application/javascript">
                        serviciu=6
                    </script>
                    @php
                        $programare->serviciu = 6;
                    @endphp
                @break
            @default
        @endswitch

        <div class="row p-2 mb-0">
            <div class="col-lg-10 mb-2 mx-auto">
                <label for="nume" class="mb-0 ps-3">Nume{{ ($programare->serviciu == 4 || $programare->serviciu == 5 || $programare->serviciu == 6) ? ' soț' : '' }}*:</label>
                <input
                    type="text"
                    class="form-control form-control-sm rounded-pill {{ $errors->has('nume') ? 'is-invalid' : '' }}"
                    name="nume"
                    placeholder=""
                    value="{{ old('nume', $programare->nume) }}">
            </div>
        </div>
        <div class="row p-2 mb-0">
            <div class="col-lg-10 mb-2 mx-auto">
                <label for="prenume" class="mb-0 ps-3">Prenume{{ ($programare->serviciu == 4 || $programare->serviciu == 5 || $programare->serviciu == 6) ? ' soț' : '' }}*:</label>
                <input
                    type="text"
                    class="form-control form-control-sm rounded-pill {{ $errors->has('prenume') ? 'is-invalid' : '' }}"
                    name="prenume"
                    placeholder=""
                    value="{{ old('prenume', $programare->prenume) }}">
            </div>
        </div>
        <div class="row p-2 mb-0">
            <div class="col-lg-10 mb-2 mx-auto">
                <label for="cnp" class="mb-0 ps-3">CNP{{ ($programare->serviciu == 4 || $programare->serviciu == 5 || $programare->serviciu == 6) ? ' soț' : '' }}:</label>
                <input
                    type="text"
                    class="form-control form-control-sm rounded-pill {{ $errors->has('cnp') ? 'is-invalid' : '' }}"
                    name="cnp"
                    placeholder=""
                    value="{{ old('cnp', $programare->cnp) }}"
                    required>
            </div>
        </div>

        @if ($programare->serviciu == 4 || $programare->serviciu == 5 || $programare->serviciu == 6)
            <div class="row p-2 mb-0">
                <div class="col-lg-10 mb-2 mx-auto">
                    <label for="nume_sotie" class="mb-0 ps-3">Nume soție:</label>
                    <input
                        type="text"
                        class="form-control form-control-sm rounded-pill {{ $errors->has('nume_sotie') ? 'is-invalid' : '' }}"
                        name="nume_sotie"
                        placeholder=""
                        value="{{ old('nume_sotie', $programare->nume_sotie) }}">
                </div>
            </div>
            <div class="row p-2 mb-0">
                <div class="col-lg-10 mb-2 mx-auto">
                    <label for="prenume_sotie" class="mb-0 ps-3">Prenume soție:</label>
                    <input
                        type="text"
                        class="form-control form-control-sm rounded-pill {{ $errors->has('prenume_sotie') ? 'is-invalid' : '' }}"
                        name="prenume_sotie"
                        placeholder=""
                        value="{{ old('prenume_sotie', $programare->prenume_sotie) }}">
                </div>
            </div>
            <div class="row p-2 mb-0">
                <div class="col-lg-10 mb-2 mx-auto">
                    <label for="cnp_sotie" class="mb-0 ps-3">CNP soție:</label>
                    <input
                        type="text"
                        class="form-control form-control-sm rounded-pill {{ $errors->has('cnp_sotie') ? 'is-invalid' : '' }}"
                        name="cnp_sotie"
                        placeholder=""
                        value="{{ old('cnp_sotie', $programare->cnp_sotie) }}"
                        required>
                </div>
            </div>
            <div class="row p-2 mb-0">
                <div class="col-lg-10 mb-2 mx-auto">
                    <label for="telefon" class="mb-0 ps-3">Telefon:</label>
                    <input
                        type="text"
                        class="form-control form-control-sm rounded-pill {{ $errors->has('telefon') ? 'is-invalid' : '' }}"
                        name="telefon"
                        placeholder=""
                        value="{{ old('telefon', $programare->telefon) }}"
                        required>
                </div>
            </div>
        @endif

        <div class="row p-2 mb-0">
            <div class="col-lg-10 mb-2 mx-auto">
                <label for="email" class="mb-0 ps-3">Email:</label>
                <input
                    type="text"
                    class="form-control form-control-sm rounded-pill {{ $errors->has('email') ? 'is-invalid' : '' }}"
                    name="email"
                    placeholder=""
                    value="{{ old('email', $programare->email) }}"
                    required>
            </div>
        </div>
        <div class="row p-2 mb-0 justify-content-center">
            <div class="col-lg-5">
                <script type="application/javascript">
                    dataVeche={!! json_encode(old('data', ($programare->data ?? ''))) !!}
                    dataVecheInitiala={!! json_encode($programare->data) !!}
                </script>
                <label for="data" class="mb-0 ps-3">Data:</label>
                    <vue2-datepicker
                        data-veche="{{ old('data', ($programare->data ?? '')) }}"
                        nume-camp-db="data"
                        tip="date"
                        value-type="YYYY-MM-DD"
                        format="DD-MM-YYYY"
                        :latime="{ width: '125px' }"
                        @dataprogramare="dataProgramareTrimisa"
                        {{-- @change='getOre()' --}}
                    ></vue2-datepicker>
            </div>
            <div class="col-lg-5 text-lg-center">
                <label for="ora_inceput" class="mb-0 ps-1">Ora:</label>
                <script type="application/javascript">
                    oraVeche={!! json_encode(old('ora', $programare->ora ? \Carbon\Carbon::parse($programare->ora)->isoFormat('HH:mm') : '')) !!}
                    oraVecheInitiala={!! json_encode($programare->ora) !!}
                </script>
                    <select class="form-select form-select-sm {{ $errors->has('ora') ? 'is-invalid' : '' }}"
                        name="ora"
                        v-model="ora"
                        >
                        {{-- <option disabled value="">Selectează o oră</option> --}}

                        </option>

                        <option
                            v-for='ora in ore'
                            :value='ora'
                            >
                                @{{ora}}
                        </option>
                    </select>

            </div>
        </div>
        <div class="row p-2">
            <div class="col-lg-12 py-3 d-flex justify-content-center">
                <button type="submit" class="btn btn-primary text-white btn-sm me-2 rounded-pill">{{ $buttonText }}</button>
                <a class="btn btn-secondary btn-sm rounded-pill" href="/{{ $serviciu }}/programari">Renunță</a>
            </div>
        </div>
    </div>
</div>
