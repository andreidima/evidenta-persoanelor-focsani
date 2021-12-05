@extends ('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="shadow-lg" style="border-radius: 40px 40px 40px 40px;">
                <div class="border border-secondary p-2" style="border-radius: 40px 40px 0px 0px; background-color:#e66800">
                    <h6 class="ms-2 my-0" style="color:white">
                        <i class="fas fa-calendar-check me-1"></i>
                        @switch($serviciu)
                            @case('evidenta-persoanelor')
                                Evidența persoanelor
                                @break
                            @case('transcrieri-certificate')
                                Transcrieri certificate
                                @break
                            @case('casatorii')
                                Căsătorii
                                @break
                            @default
                        @endswitch
                        / Programări / {{ $programare->nume ?? '' }}</h6>
                </div>

                <div class="card-body py-2 border border-secondary"
                    style="border-radius: 0px 0px 40px 40px;"
                >

            @include ('errors')

                    <div class="table-responsive col-md-12 mx-auto">
                        <table class="table table-sm table-striped table-hover"
                        >
                            <tr>
                                <td class="pe-4">
                                    Nume
                                </td>
                                <td>
                                    {{ $programare->nume }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Email
                                </td>
                                <td>
                                    {{ $programare->email }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    CNP
                                </td>
                                <td>
                                    {{ $programare->cnp }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Data
                                </td>
                                <td>
                                    {{ $programare->data ? \Carbon\Carbon::parse($programare->data)->isoFormat('DD.MM.YYYY') : '' }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Ora
                                </td>
                                <td>
                                    {{ $programare->ora ? \Carbon\Carbon::parse($programare->ora)->isoFormat('HH:mm') : '' }}
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class="form-row mb-2 px-2">
                        <div class="col-lg-12 d-flex justify-content-center">
                            <a class="btn btn-primary text-white btn-sm rounded-pill" href="/{{ $serviciu }}/programari">Pagină programări</a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
