@extends ('layouts.app')

@section('content')
<div class="container card" style="border-radius: 40px 40px 40px 40px;">
    <div class="row card-header justify-content-between align-items-center" style="border-radius: 40px 40px 0px 0px;">
        <div class="col-lg-3">
            <h4 class="mb-0"><a href="{{ route('programari.afisare_saptamanal') }}"><i class="fas fa-calendar-check me-1"></i>Programări săptămânal</a></h4>
        </div>
        <div class="col-lg-6" id="app">
            <form class="needs-validation" novalidate method="GET" action="{{ route('programari.afisare_saptamanal') }}">
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
                        <a class="btn btn-sm bg-secondary text-white border border-dark rounded-pill" href="{{ route('programari.index') }}" role="button">
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
            @foreach ($ore_de_program->groupBy('ziua_din_saptamana') as $ore_zilnic)
                <div class="col-lg-2 table-responsive rounded">
                    <table class="table table-striped table-hover table-sm rounded">
                        <thead class="text-white rounded" style="background-color:#e66800;">
                            <tr class="" style="padding:2rem">
                                <th class="text-center">
                                    {{ \Carbon\Carbon::parse($search_data)->isoWeekday($ore_zilnic->first()->ziua_din_saptamana)->isoFormat('DD.MM.YYYY') }}
                                </th>
                            </tr>
                        </thead>

                        @foreach ($ore_zilnic as $ora)
                            <tr>
                                @forelse($programari_din_saptamana_cautata->where('data', \Carbon\Carbon::parse($search_data)->isoWeekday($ora->ziua_din_saptamana)->toDateString())->where('ora', '=', $ora->ora) as $programare)
                                    <td class="text-center text-white" style="background-color: #00b15e">
                                        <span class="px-1">
                                            {{ $ora->ora ? \Carbon\Carbon::parse($ora->ora)->isoFormat('HH:mm') : '' }}
                                        </span>
                                        -
                                                <span>
                                                    {{ $programare->nume }}
                                                </span>
                                    </td>
                                @empty
                                    <td class="text-center">
                                        <span class="px-1">
                                            {{ $ora->ora ? \Carbon\Carbon::parse($ora->ora)->isoFormat('HH:mm') : '' }}
                                        </span>
                                    </td>
                                @endforelse
                            </tr>
                        @endforeach
                    </table>
                </div>
            @endforeach
        </div>
    </div>
</div>

@endsection
