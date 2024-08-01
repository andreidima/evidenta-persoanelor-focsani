@csrf

<div class="row mb-0 p-3 d-flex border-radius: 0px 0px 40px 40px" id="app">
    <div class="col-lg-12 mb-0">

            <div class="col-lg-3 mb-5 mx-auto d-flex justify-content-start">
                <div>
                    <label for="data" class="mb-0 ps-3">Data</label>
                    <vue2-datepicker
                        data-veche="{{ old('data', ($zi_nelucratoare->data ?? '')) }}"
                        nume-camp-db="data"
                        tip="date"
                        value-type="YYYY-MM-DD"
                        format="DD-MM-YYYY"
                        :latime="{ width: '125px' }"
                    ></vue2-datepicker>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 mb-2 d-flex justify-content-center">
                <button type="submit" class="btn btn-primary text-white me-3 rounded-3">{{ $buttonText }}</button>
                <a class="btn btn-secondary rounded-3" href="{{ Session::get('zi_nelucratoare_return_url') }}">Renunță</a>
            </div>
        </div>
    </div>
</div>
