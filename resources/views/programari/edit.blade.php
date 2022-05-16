@extends ('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="shadow-lg" style="border-radius: 40px 40px 40px 40px;">
                <div class="border border-secondary p-2" style="border-radius: 40px 40px 0px 0px; background-color:#e66800">
                    <h6 class="ms-3 my-0" style="color:white">
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
                        - Schimbă datele programării
                    </h6>
                </div>

                @include ('errors')

                <div class="card-body py-2 border border-secondary"
                    style="border-radius: 0px 0px 40px 40px;"
                >
                    <form  class="needs-validation" novalidate method="POST" action="/{{ $serviciu }}/{{ $programare->path() }}">
                        @method('PATCH')


                                @include ('programari.form', [
                                    'buttonText' => 'Modifică Programarea'
                                ])

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
