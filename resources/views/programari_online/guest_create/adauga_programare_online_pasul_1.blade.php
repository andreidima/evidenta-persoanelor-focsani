@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row my-4 justify-content-center">
        <div class="col-md-9 p-0">
            <div class="shadow-lg bg-white" style="border-radius: 40px 40px 40px 40px;">
                <div class="p-2"
                    style="
                        border-radius: 40px 40px 0px 0px;
                        border:5px solid #B0413E;
                        color: #ffffff;
                        background-color:#B0413E;
                    "
                >
                    <div class="row">
                        <div class="col-lg-12 mb-4 d-flex justify-content-between align-items-end">
                            <h3 class="ms-3 my-2" style="color:#ffffff"><i class="fas fa-users fa-lg me-1"></i>Evidența persoanelor Focșani</h3>
                            {{-- <img src="{{ asset('images/logo.png') }}" height="70" class="mr-3"> --}}
                        </div>
                        <div class="col-lg-12">
                            <h3 class="mb-0 text-center" style="color:#ffffff">
                                Depunerea cererii în vederea eliberării actului de identitate
                            </h3>
                        </div>
                    </div>
                </div>

                <div class="card-body py-4 text-start"
                    style="
                        color:rgb(0, 0, 0);
                        background-color:#ffffff;
                        border:5px solid #B0413E;
                        border-radius: 0px 0px 40px 40px
                    "
                >

                @include ('errors')

                {{-- <h3 class="mb-5 text-center" style="color:#B0413E">
                    Depunerea cererii în vederea eliberării actului de identitate
                </h3> --}}


                Pentru depunerea cererii în vederea eliberării actului de identitate/ înscrierea menţiunii de stabilire a reşedinţei pe actul de identitate.
                <br>
                <ul style="text-start">
                    <li>
                        <b>Art.1</b> Programarea la ghișeul online în vederea obținerii actelor de identitate se face pe pagina de internet a Direcţiei de Evidenţă a Persoanelor Focșani - https://evidentapersoanelorfocsani.ro/ de către persoana interesată. Nu se pot face programări prin email, fax sau telefonic.
                    </li>
                    <li>
                        <b>Art.2. Programarea este gratuită şi netransmisibilă</b>, iar înainte de completarea programării, solicitantul se asigură că deţine toate actele necesare depunerii cererii pentru obţinerea actului de identitate.
                    </li>
                    <li>
                        <b>Art.3.</b> Durata unui interval de depunere a cererii este de 15 minute pentru fiecare persoană, <b>un interval fiind alocat unei singure persoane şi nu unei familii</b>.
                    </li>
                    <li>
                        <b>Art.4.</b> Neconcordanţa dintre datele înscrise în formularul de programare (nume, prenume, CNP) şi datele de identificare ale persoanei care se prezintă la depunerea cererii <b>duce la anularea programării</b>. Neprezentarea la data şi ora programată, neconformitatea datelor din formularul de programare sau lipsa actelor necesare duc la <b>anularea programării</b>.
                    </li>
                    <li>
                        <b>Art.5.</b> Nu se fac programări pentru zilele nelucrătoare, stabilite sau anunţate ulterior prin acte normative, zile în care nu se desfăşoară activitate de lucru cu publicul.
                    </li>
                    <li>
                        <b>Art.6.</b> Prin continuarea procedurii de programare online, solicitantul este de accord cu prelucrarea datelor cu caracter personal, în conformitate cu prevederile <b>Regulamentului (UE) 2016/679</b> pentru protecţia persoanelor fizice în ceea ce priveşte prelucrarea datelor cu caracter personal privind libera circulație a acestor date şi de abrogare a Directivei 95/46/CE.
                    </li>
                    <li>
                        <b>Art.7.</b> După realizarea programării va fi verificată căsuța de e-mail. La adresa de e-mail indicată în formularul de înregistrare se va trimite de sistem o confirmare cu data și ora programării.
                    </li>
                    <li>
                        <b>Art.8. Programul de lucru cu publicul</b> aferent activităţii de primire a cererilor pentru eliberarea actului de identitate se desfăşoară în zilele lucrătoare de luni-vineri, conform intervalului orar:
                        <div class="row">
                            <div class="col-lg-3 offset-lg-1">
                                <table class="fs-6 table table-sm table-striped table-hover">
                                    <tr>
                                        <td class="">
                                            Luni
                                        </td>
                                        <td class="text-center">
                                            08:30 - 12:30
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Marți
                                        </td>
                                        <td class="text-center">
                                            08:30 - 12:30
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="pe-4">
                                            Miercuri
                                        </td>
                                        <td class="text-center">
                                            12:00 - 18:30
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Joi
                                        </td>
                                        <td class="text-center">
                                            08:30 - 12:30
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Vineri
                                        </td>
                                        <td class="text-center">
                                            08:30 - 12:30
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        {{-- <ul>
                            <li>
                                Luni 08:30 - 12:30
                            </li>
                            <li>
                                Marți 08:30 - 12:30
                            </li>
                            <li>
                                Miercuri 12:00 - 18:30
                            </li>
                            <li>
                                Joi 08:30 - 12:30
                            </li>
                            <li>
                                Vineri 08:30 - 12:30
                            </li>
                        </ul> --}}
                    </li>
                    <li>
                        <b>Art.9.</b> Cererile pentru eliberarea actului de identitate se depun personal sau pe bază de procură specială obţinută de la misiunile diplomatice ori oficiile consulare ale României din străinătate. Înainte de depunerea cererii se vor achita taxele necesare pentru eliberarea actelor de identitate la casieria unităţii.
                    </li>
                        Este <b>obligatoriu</b> ca persoana care depune cererea să prezinte toate documentele necesare eliberării actului de identitate, conform listei de documente afişate. Actele se prezintă în original şi copie.
                    <li>
                        <b>Art.10.</b> Termenul de soluţionare a cererilor stabilit de Direcţia de Evidenţă a Persoanelor Focșani este fixat la 4 zile lucrătoare, excepţie făcând situaţiile în care sunt necesare verificări prevăzute de lege, în acest caz termenul fiind de 30 de zile.Funcţionarul care primeşte cererea înmânează solicitantului o dovadă care cuprinde numărul de înregistrare şi data înregistrării, precum şi data prezentării pentru eliberarea actului de identitate.
                    </li>

                <b>Nu se fac programării online pentru eliberarea documentelor.</b>

                    <li>
                        <b><u>Art.11. Pentru cererile care vizează cetăţenii care nu au avut niciodată domiciliul în România(dobândire /redobândire cetăţenie), respectiv care solicită schimbarea domiciliului din străinătate în România nu se fac programări online, persoanele interesate urmând să se prezinte la sediul nostru.</u></b>
                    </li>
                </ul>


                <h5 class="ps-3 alert alert-warning">
                    Selectați o zi disponibilă din următoarele 2 luni calendaristice:
                </h5>

                @for ($luna = 0; $luna <= 1 ; $luna++)
                    @php
                        $luna_prima_zi = \Carbon\Carbon::today()->addMonth($luna)->startOfMonth();
                        $luna_ultima_zi = \Carbon\Carbon::today()->addMonth($luna)->endOfMonth();
                    @endphp

                    {{-- <div class="d-flex flex-wrap">
                            <div style="width:100px; border:1px solid white">
                                Luni
                            </div>
                            <div style="width:100px; border:1px solid white">
                                Marți
                            </div>
                            <div style="width:100px; border:1px solid white">
                                Miercuri
                            </div>
                            <div style="width:100px; border:1px solid white">
                                Joi
                            </div>
                            <div style="width:100px; border:1px solid white">
                                Vineri
                            </div>
                            <div style="width:100px; border:1px solid white">
                                Sâmbătă
                            </div>
                            <div style="width:100px; border:1px solid white">
                                Duminică
                            </div>
                    </div> --}}


                    <style>
                    #lunar {
                    border-collapse: collapse;
                    color: rgb(151, 0, 0);
                    margin: auto;
                    /* width: 100% !important; */
                    }

                    #lunar td, #lunar th {
                    border: 1px solid rgb(0, 0, 0);
                    padding: 8px;
                    text-align: center;
                    }

                    #lunar th {
                    padding-top: 12px;
                    padding-bottom: 12px;
                    }
                    </style>

                    <div class="row p-md-4 justify-content-center">
                        <div class="table-responsive px-0" style="background-color: rgb(255, 255, 255)">
                            <table class="table align-middle" id="lunar" style="width: 100%">
                                <tr>
                                    <th colspan="7">
                                        {{ ucfirst($luna_prima_zi->isoFormat('MMMM YYYY')) }}
                                    </th>
                                </tr>
                                <tr>
                                    <th width="13%">
                                        Luni
                                    </th>
                                    <th width="13%">
                                        Marți
                                    </th>
                                    <th width="13%">
                                        Miercuri
                                    </th>
                                    <th width="13%">
                                        Joi
                                    </th>
                                    <th width="13%">
                                        Vineri
                                    </th>
                                    <th width="13%">
                                        Sâmbătă
                                    </th>
                                    <th width="13%">
                                        Duminică
                                    </th>
                                </tr>
                                <tr>
                                    @for ($ziua = $luna_prima_zi->startOfWeek(); $ziua < $luna_ultima_zi->endOfWeek(); $ziua->addDay())
                                    @php
                                        // dd($ziua);
                                    @endphp
                                        @if (($ziua->isMonday() == true) && ($ziua->day > 1))
                                            <tr>
                                        @endif

                                        @if ($ziua->month != \Carbon\Carbon::today()->addMonth($luna)->month)
                                            <td class="" style="">
                                                {{ $ziua->isoFormat('DD') }}
                                            </td>
                                        @elseif (
                                            ($ziua->isWeekend() == true)
                                            ||
                                            ($ziua->lessThan(\Carbon\Carbon::tomorrow()))
                                            ||
                                            (in_array($ziua->toDateString(), $zile_nelucratoare))
                                        )
                                                <td class="" style="background-color:rgb(219, 219, 219)">
                                                    {{ $ziua->isoFormat('DD') }}
                                                </td>
                                        @else
                                            <td class="p-0" style="">
                                                <form class="needs-validation mb-0" novalidate method="POST" action="/programari-online/adauga-programare-online-pasul-1">
                                                    @csrf
                                                    <div class="d-grid" style="height:100%;width:100%;">
                                                        <input type="hidden" id="data" name="data" value="{{ $ziua->toDateString(); }}">
                                                        <button type="submit" name=""
                                                            class="btn btn-success text-white" style="">
                                                            <b>{{ $ziua->isoFormat('DD') }}</b>
                                                        </button>
                                                    </div>
                                                </form>
                                            </td>
                                        @endif

                                        @if ($ziua->isSunday() == true)
                                            </tr>
                                        @endif
                                    @endfor
                                </tr>
                            </table>
                        </div>
                    </div>
                @endfor


                </div>
            </div>
        </div>
    </div>
</div>