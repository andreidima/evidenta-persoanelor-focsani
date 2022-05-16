@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card" style="">
                <div class="card-header" style="">
                    <h6 class="ms-3 my-0" style="">
                        Pagina principală
                    </h6>
                </div>

                @include ('errors')

                <div class="card-body py-2"
                    style=""
                >
                    <div class="row mb-0 p-3 d-flex border-radius: 0px 0px 40px 40px" id="app">
                        <div class="col-lg-12 mb-4">
                            Bine ai venit <b>{{ auth()->user()->name ?? '' }}</b>!
                        </div>
                        <div class="col-lg-4 mx-auto mb-4 text-center">
                            <div class="shadow" style="border-radius: 40px 40px 40px 40px;">
                                <div class="border border-secondary p-2 text-white" style="border-radius: 40px 40px 0px 0px; background-color:#e66800">
                                    <i class="fas fa-print me-1"></i> Printează programări
                                </div>
                                <div class="card-body py-3 border border-secondary"
                                    style="border-radius: 0px 0px 40px 40px;"
                                >
                                    <a class="" href="/evidenta-persoanelor/programari/afisare-zilnic">
                                        <span class="badge bg-primary my-1 fs-5">
                                            Evidența persoanelor
                                        </span>
                                    </a>
                                    <br>
                                    <a class="" href="/transcrieri-certificate/programari/afisare-zilnic">
                                        <span class="badge bg-primary my-1 fs-5">
                                            Transcrieri certificate
                                        </span>
                                    </a>
                                    <br>
                                    <a class="" href="/casatorii/programari/afisare-zilnic">
                                        <span class="badge bg-primary my-1 fs-5">
                                            Căsătorii
                                        </span>
                                    </a>
                                    <br>
                                    <a class="" href="/casatorii-oficieri-sediu/programari/afisare-zilnic">
                                        <span class="badge bg-primary my-1 fs-5">
                                            Căsătorii Sediu
                                        </span>
                                    </a>
                                    <br>
                                    <a class="" href="/casatorii-oficieri-foisor/programari/afisare-zilnic">
                                        <span class="badge bg-primary my-1 fs-5">
                                            Căsătorii Foișor
                                        </span>
                                    </a>
                                    <br>
                                    <a class="" href="/casatorii-oficieri-teatru/programari/afisare-zilnic">
                                        <span class="badge bg-primary my-1 fs-5">
                                            Căsătorii Teatru
                                        </span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
