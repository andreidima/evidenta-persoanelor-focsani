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
                        </div>
                        <div class="col-lg-12">
                            <h3 class="mb-0 text-center" style="color:#ffffff">
                                @switch($serviciu)
                                    @case('evidenta-persoanelor')
                                        Depunerea cererii în vederea eliberării actului de identitate
                                        @break
                                    @case('transcrieri-certificate')
                                        Transcrieri certificate
                                        <br>
                                        <small class="fs-5">
                                            Depunere acte/documente în vederea Transcrierii de certificate/extrase emise de autoritățile străine
                                        </small>
                                        @break
                                    @case('casatorii')
                                        Căsătorii
                                        <br>
                                        <small class="fs-5">
                                            Depunere acte necesare în vederea oficierii căsătoriei
                                        </small>
                                        @break
                                    @case('casatorii-oficieri')
                                        Căsătorii
                                        <br>
                                        <small class="fs-5">
                                            Programare online în vederea oficierii căsătoriei
                                        </small>
                                        @break
                                    @default
                                @endswitch
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


                @switch($serviciu)
                    @case('evidenta-persoanelor')
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
                            </li>
                            <li>
                                <b>Art.9.</b> Cererile pentru eliberarea actului de identitate se depun personal sau pe bază de procură specială obţinută de la misiunile diplomatice ori oficiile consulare ale României din străinătate.
                                Înainte de depunerea cererii se vor achita taxele necesare pentru eliberarea actelor de identitate.
                                Taxele se pot achita online la <a href="https://www.ghiseul.ro/" target="_blank">Ghiseul.ro</a> sau la caseriile Primăriei Municipiului Focșani, din strada Dimitrie Cantemir nr. 1 bis.

                            </li>
                                Este <b>obligatoriu</b> ca persoana care depune cererea să prezinte toate documentele necesare eliberării actului de identitate, conform listei de documente afişate. Actele se prezintă în original şi copie.
                            <li>
                                <b>Art.10.</b> Termenul de soluţionare a cererilor stabilit de Direcţia de Evidenţă a Persoanelor Focșani este intre 1 și 30 de zile lucrătoare, în funcție de numărul cererilor aflate în lucru. excepţie făcând situaţiile în care sunt necesare verificări prevăzute de lege, în acest caz termenul fiind de 30 de zile. Funcţionarul care primeşte cererea înmânează solicitantului o dovadă care cuprinde numărul de înregistrare şi data înregistrării, precum şi data prezentării pentru eliberarea actului de identitate.
                            </li>

                        <b>Nu se fac programării online pentru eliberarea documentelor.</b>

                            <li>
                                <b><u>Art.11. Pentru cererile care vizează cetăţenii care nu au avut niciodată domiciliul în România(dobândire /redobândire cetăţenie), respectiv care solicită schimbarea domiciliului din străinătate în România nu se fac programări online, persoanele interesate urmând să se prezinte la sediul nostru.</u></b>
                            </li>
                        </ul>

                        @break
                    @case('transcrieri-certificate')
                        {{-- Vă rugăm selectați o zi de:
                            <ul>
                                <li>
                                    <b>Marți</b>, dacă sunteți cetățean român cu domiciliul în municipiul Focșani, Vrancea;
                                </li>
                                <li>
                                    <b>Miercuri</b>, dacă sunteți cetățean străin.
                                </li>
                            </ul> --}}

                        @break
                    @case('casatorii')
                        <p class="">
                        Căsătoria se va oficia începând cu a 11-a zi de la data depunerii actelor până la 14 zile
                        </p>

                        @break
                    @default
                @endswitch

                @switch($serviciu)
                    @case('evidenta-persoanelor')
                    @case('transcrieri-certificate')
                    @case('casatorii')
                        <h5 class="ps-3 alert alert-warning">
                            Selectați o zi disponibilă din următoarele 2 luni calendaristice:
                        </h5>
                        @break
                    @case('casatorii-oficieri')
                        <h5 class="ps-4 mb-4 text-center">
                            Programări disponibile pentru:
                            @switch($programare->serviciu)
                                @case(4)
                                    <b>Sediul S.P.C.L.E.P. Focșani</b>
                                    @break
                                @case(5)
                                    <b>Foișorul central din Grădina Publică</b>
                                    @break
                                @case(6)
                                    <b>Teatrul Municipal Focșani „Mr. Gheorghe Pastia”</b>
                                    @break
                                @default

                            @endswitch
                        </h5>

                        <h5 class="ps-3 alert alert-warning">
                            Selectați o zi disponibilă din următoarele 12 luni calendaristice:
                        </h5>
                    @break
                    @default
                @endswitch

                <div class="row mb-0">
                    <div class="col-lg-12 ps-4 mb-0">
                        Legendă:
                        <span class="badge bg-success"><h6 class="mb-0">Zile cu ore disponibile</h6></span>
                        <span class="badge" style="background-color:rgb(219, 107, 107)"><h6 class="mb-0">Zile ocupate complet</h6></span>
                        <span class="badge" style="background-color:rgb(219, 219, 219); color:rgb(151, 0, 0)"><h6 class="mb-0">Zile indisponibile</h6></span>
                    </div>
                </div>

                @switch($serviciu)
                    @case('evidenta-persoanelor')
                    @case('transcrieri-certificate')
                    @case('casatorii')
                        @php
                            $nr_luni_disponibile = 2;
                        @endphp
                        @break
                    @case('casatorii-oficieri')
                        @php
                            $nr_luni_disponibile = 12;
                        @endphp
                        @break
                    @default
                @endswitch

                @for ($luna = 0; $luna < $nr_luni_disponibile ; $luna++)
                    @php
                        $luna_prima_zi = \Carbon\Carbon::today()->addMonthsNoOverflow($luna)->startOfMonth();
                        $luna_ultima_zi = \Carbon\Carbon::today()->addMonthsNoOverflow($luna)->endOfMonth();
                    @endphp



                    <style>
                    #lunar {
                    border-collapse: collapse;
                    color: rgb(151, 0, 0);
                    margin: auto;
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
                                        <h5 class="mb-0">
                                            {{ ucfirst($luna_prima_zi->isoFormat('MMMM YYYY')) }}
                                        </h5>
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

                                        @if ($ziua->month != \Carbon\Carbon::today()->addMonthsNoOverflow($luna)->month)
                                            <td class="" style="">
                                                {{ $ziua->isoFormat('DD') }}
                                            </td>
                                        @elseif (
                                            // evidenta-persoanelor: nu se lucreaza in weekend
                                            (($programare->serviciu == 1) && $ziua->isWeekend())
                                            ||

                                            // transcrieri-certificate: se lucreaza doar 1 zi pe saptamana, miercurea
                                            (($programare->serviciu == 2) && (!$ziua->isWednesday()))
                                            ||

                                            // casatorii: nu se lucreaza in weekend
                                            (($programare->serviciu == 3) && $ziua->isWeekend())
                                            ||

                                            // casatorii-oficieri - sediu - se lucreaza Luni - Vineri fara weekend
                                            (($programare->serviciu == 4) && ($ziua->isWeekend() == true))
                                            ||

                                            // casatorii-oficieri - foisor - se lucreaza doar in weekend
                                            (($programare->serviciu == 5) && ($ziua->isWeekDay() == true))
                                            ||

                                            // evidenta-persoanelor, transcrieri-certificate, casatorii - programarile se pot face incepand de maine
                                            (($programare->serviciu == 1 || $programare->serviciu == 2 || $programare->serviciu == 3) && $ziua->lessThan(\Carbon\Carbon::tomorrow()))
                                            ||

                                            // casatorii-oficieri - programarile se pot face cu cel putin 12 zile inainte
                                            (($programare->serviciu == 4 || $programare->serviciu == 5 || $programare->serviciu == 6) && $ziua->lessThan(\Carbon\Carbon::today()->addDays(12)))
                                            ||

                                            (in_array($ziua->toDateString(), $zile_nelucratoare))
                                        )
                                                <td class="" style="background-color:rgb(219, 219, 219)">
                                                    {{ $ziua->isoFormat('DD') }}
                                                </td>
                                        @elseif (in_array($ziua->toDateString(), $zile_pline))
                                                <td class="text-white" style="background-color:rgb(219, 107, 107)">
                                                    {{ $ziua->isoFormat('DD') }}
                                                </td>
                                        @else
                                            <td class="p-0" style="">
                                                <form class="needs-validation mb-0" novalidate method="POST" action="/{{ $serviciu }}/programari/adauga-programare-pasul-1">
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

                @if ($programare->serviciu == 4 || $programare->serviciu == 5 || $programare->serviciu == 6)
                    <div class="row py-2 g-3 justify-content-center">
                        <div class="col-lg-4 d-grid">
                            <a class="btn btn-primary text-white rounded-pill" href="/{{ $serviciu }}/programari/adauga-programare-pasul-0">Înapoi</a>
                        </div>
                    </div>
                @endif


                </div>
            </div>
        </div>
    </div>
</div>
@endsection
