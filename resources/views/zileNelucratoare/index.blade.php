@extends ('layouts.app')

@php
    use \Carbon\Carbon;
@endphp

@section('content')
<div class="container card" style="border-radius: 40px 40px 40px 40px;">
        <div class="row card-header align-items-center" style="border-radius: 40px 40px 0px 0px;">
            <div class="col-lg-3">
                <span class="badge text-black fs-5">
                    Zile nelucrătoare
                </span>
            </div>
            <div class="col-lg-6">
            </div>
            <div class="col-lg-3 text-end">
                <a class="btn btn-sm bg-success text-white border border-dark rounded-3 col-md-8" href="/{{ $serviciu }}/zile-nelucratoare/adauga" role="button">
                    <i class="fas fa-plus-square text-white me-1"></i>Adaugă zi nelucrătoare
                </a>
            </div>
        </div>

        <div class="card-body px-0 py-3">

            @include ('errors')

            <div class="table-responsive rounded">
                <table class="table table-striped table-hover rounded">
                    <tbody>
                        @php
                            $columns = 3; // Number of columns
                            $rows = ceil($zile_nelucratoare->count() / $columns); // Calculate number of rows needed
                        @endphp

                        {{-- Loop through rows --}}
                        @for ($row = 0; $row < $rows; $row++)
                            <tr>
                                @for ($col = 0; $col < $columns; $col++)
                                    <td class="text-center">
                                        @if (($counter = intval(($rows*$col + $row))) < $zile_nelucratoare->count())
                                        @php
                                            $zi_nelucratoare = $zile_nelucratoare[$counter];
                                        @endphp
                                        <div class="d-flex justify-content-end">
                                            {{ Carbon::parse($zi_nelucratoare->data)->isoFormat('DD.MM.YYYY') }}
                                            &nbsp;
                                            {{-- <a href="{{ $zi_nelucratoare->path() }}/modifica" class="flex me-1">
                                                <span class="badge bg-primary"><i class="fas fa-pen"></i></span>
                                            </a> --}}
                                            <div style="flex" class="">
                                                <a
                                                    href="#"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#stergeZiNelucrătoare{{ $zi_nelucratoare->id }}"
                                                    title="Șterge Ziua Nelucrătoare"
                                                    >
                                                    <span class="badge bg-danger"><i class="far fa-trash-alt text-white"></i></span>
                                                </a>
                                            </div>
                                        </div>
                                        @endif
                                    </td>
                                @endfor
                            </tr>
                        @endfor
                    </tbody>
                </table>
            </div>

                <nav>
                    <ul class="pagination justify-content-center">
                        {{$zile_nelucratoare->appends(Request::except('page'))->links()}}
                    </ul>
                </nav>
        </div>
    </div>

    {{-- Modalele pentru stergere zi nelucratoare --}}
    @foreach ($zile_nelucratoare as $zi_nelucratoare)
        <div class="modal fade text-dark" id="stergeZiNelucrătoare{{ $zi_nelucratoare->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title text-white" id="exampleModalLabel">Ziua Nelucrătoare: <b>{{ \Carbon\Carbon::make($zi_nelucratoare->data)->isoFormat('DD.MM.YYYY') }}</b></h5>
                    <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="text-align:left;">
                    Ești sigur ca vrei să ștergi Ziua Nelucrătoare?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Renunță</button>

                    <form method="POST" action="/{{ $serviciu }}/{{ $zi_nelucratoare->path() }}">
                        @method('DELETE')
                        @csrf
                        <button
                            type="submit"
                            class="btn btn-danger text-white"
                            >
                            Șterge Ziua Nelucrătoare
                        </button>
                    </form>

                </div>
                </div>
            </div>
        </div>
    @endforeach


@endsection
