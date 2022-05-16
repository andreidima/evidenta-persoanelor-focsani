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
                            @case('casatorii-oficieri-sediu')
                                Căsătorii Sediu
                                @break
                            @case('casatorii-oficieri-foisor')
                                Căsătorii Foisor
                                @break
                            @case('casatorii-oficieri-teatru')
                                Căsătorii Teatru
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
                                    Nume{{ ($programare->serviciu == 4 || $programare->serviciu == 5 || $programare->serviciu == 6) ? ' soț' : '' }}
                                </td>
                                <td>
                                    {{ $programare->nume }}
                                </td>
                            </tr>
                            <tr>
                                <td class="pe-4">
                                    Prenume{{ ($programare->serviciu == 4 || $programare->serviciu == 5 || $programare->serviciu == 6) ? ' soț' : '' }}
                                </td>
                                <td>
                                    {{ $programare->prenume }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    CNP{{ ($programare->serviciu == 4 || $programare->serviciu == 5 || $programare->serviciu == 6) ? ' soț' : '' }}
                                </td>
                                <td>
                                    {{ $programare->cnp }}
                                </td>
                            </tr>

                            @if ($programare->serviciu == 4 || $programare->serviciu == 5 || $programare->serviciu == 6)
                            <tr>
                                <td class="pe-4">
                                    Nume soție
                                </td>
                                <td>
                                    {{ $programare->nume_sotie }}
                                </td>
                            </tr>
                            <tr>
                                <td class="pe-4">
                                    Prenume soție
                                </td>
                                <td>
                                    {{ $programare->prenume_sotie }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    CNP soție
                                </td>
                                <td>
                                    {{ $programare->cnp_sotie }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Telefon
                                </td>
                                <td>
                                    {{ $programare->telefon }}
                                </td>
                            </tr>
                            @endif

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
