@extends ('layouts.app')

@section('content')
<div class="container card" style="border-radius: 40px 40px 40px 40px;">
    <div class="row card-header justify-content-between align-items-center" style="border-radius: 40px 40px 0px 0px;">
        <div class="col-lg-3">
            <h4 class="mb-0"><a href="/evidenta-persoanelor/programari/afisare-zilnic"><i class="fas fa-print me-1"></i>Printează programări</a></h4>
        </div>
        <div class="col-lg-6" id="app">
            <form class="needs-validation" novalidate method="GET" action="/evidenta-persoanelor/programari/afisare-zilnic">
                @csrf
                <div class="row mb-1 input-group custom-search-form justify-content-center">
                    <div class="col-lg-4 d-flex">
                        <label for="search_data" class="mb-0 align-self-center me-1">Data:</label>
                        <vue2-datepicker
                            data-veche="{{ $search_data }}"
                            nume-camp-db="search_data"
                            tip="date"
                            value-type="YYYY-MM-DD"
                            format="DD-MM-YYYY"
                            :latime="{ width: '125px' }"
                        ></vue2-datepicker>
                    </div>
                    <div class="col-lg-4 d-grid">
                        <button class="btn btn-sm btn-primary text-white me-1 border border-dark rounded-pill" type="submit">
                            <i class="fas fa-search text-white me-1"></i>Caută
                        </button>
                    </div>
                    <div class="col-lg-4 d-grid">
                        <a class="btn btn-sm bg-secondary text-white border border-dark rounded-pill" href="/evidenta-persoanelor/programari/afisare-zilnic" role="button">
                            <i class="far fa-trash-alt text-white me-1"></i>Resetează căutarea
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card-body px-0 py-3">

        @include ('errors')

        <div class="row d-flex justify-content-center">
            <div class="col-lg-5 table-responsive rounded">
                <table class="table table-hover table-sm rounded">
                    <thead class="text-white rounded" style="background-color:#e66800;">
                        <tr class="" style="padding:2rem">
                            <th class="text-center fs-5" colspan="3">
                                {{ \Carbon\Carbon::parse($search_data)->isoFormat('DD.MM.YYYY') }}
                            </th>
                        </tr>
                        <tr class="" style="padding:2rem">
                            <th class="text-center">
                                Ora
                            </th>
                            <th class="text-center">
                                Nume
                            </th>
                            <th class="text-center">
                                CNP
                            </th>
                        </tr>
                    </thead>
                    @foreach ($ore_de_program as $ora)
                        @forelse($programari->where('ora', '=', $ora->ora) as $programare)
                            <tr class="text-white" style="background-color: #00b15e">
                                <td class="text-center">
                                    <span class="px-1">
                                        {{ $ora->ora ? \Carbon\Carbon::parse($ora->ora)->isoFormat('HH:mm') : '' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    {{ $programare->nume }}
                                </td>
                                <td style="text-align: center">
                                    {{ $programare->cnp }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center">
                                    <span class="px-1">
                                        {{ $ora->ora ? \Carbon\Carbon::parse($ora->ora)->isoFormat('HH:mm') : '' }}
                                    </span>
                                </td>
                                <td></td>
                                <td></td>
                            </tr>
                        @endforelse
                    @endforeach
                </table>
            </div>
            <div class="col-lg-12 d-flex justify-content-center">
                <a href="/evidenta-persoanelor/programari/export/{{ $search_data->toDateString() }}/programari-pdf" class="btn bg-primary text-white border border-light rounded-pill">
                    <i class="fas fa-print text-white me-1"></i>Printează programările
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
