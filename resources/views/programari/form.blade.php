@csrf

<div class="row mb-0 p-3 d-flex border-radius: 0px 0px 40px 40px" id="app1">
    <div class="col-lg-12 mb-0">
        <div class="row p-2 mb-0">
            <div class="col-lg-10 mb-2 mx-auto">
                <label for="nume" class="mb-0 ps-3">Nume*:</label>
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
        <div class="row p-2 mb-0">
            <div class="col-lg-10 mb-2 mx-auto">
                <label for="cnp" class="mb-0 ps-3">CNP:</label>
                <input
                    type="text"
                    class="form-control form-control-sm rounded-pill {{ $errors->has('cnp') ? 'is-invalid' : '' }}"
                    name="cnp"
                    placeholder=""
                    value="{{ old('cnp', $programare->cnp) }}"
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
                <a class="btn btn-secondary btn-sm rounded-pill" href="/programari">Renunță</a>
            </div>
        </div>
    </div>
</div>
