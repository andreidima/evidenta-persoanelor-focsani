<?php

namespace App\Http\Controllers;

use App\Models\Programare;
use App\Models\ProgramareOraDeProgram;
use Illuminate\Support\Facades\DB;

use App\Mail\ProgramareEmail;

use Illuminate\Http\Request;

class ProgramareController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($serviciu = null)
    {
        $search_nume = \Request::get('search_nume');
        $search_prenume = \Request::get('search_prenume');
        $search_data = \Request::get('search_data');

        $programari = Programare::
            where (function($query) use ($serviciu) {
                switch ($serviciu) {
                    case 'evidenta-persoanelor':
                        $query->where('serviciu', 1);
                        break;
                    case 'transcrieri-certificate':
                        $query->where('serviciu', 2);
                        break;
                    case 'casatorii':
                        $query->where('serviciu', 3);
                        break;
                    default:
                        # code...
                        break;
                }
            })
            ->when($search_nume, function ($query, $search_nume) {
                return $query->where('nume', 'like', '%' . $search_nume . '%');
            })
            ->when($search_prenume, function ($query, $search_prenume) {
                return $query->where('prenume', 'like', '%' . $search_prenume . '%');
            })
            ->when($search_data, function ($query, $search_data) {
                return $query->whereDate('data', '=', $search_data)
                            ->orderBy('ora');
            }, function ($query) {
                return $query->latest();
            })
            ->simplePaginate(25);

        return view('programari.index', compact('programari', 'search_nume', 'search_prenume', 'search_data', 'serviciu'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($serviciu = null)
    {
        return view('programari.create', compact('serviciu'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $serviciu = null)
    {
        $programare = Programare::create($this->validateRequest($request, $serviciu));

        if (isset($programare->email)){
            \Mail::to($programare->email)
                ->send(
                    new ProgramareEmail($programare)
                );
        }

        return redirect('/' . $serviciu . '/programari')->with('status', 'Programarea pentru „' . ($programare->nume ?? '') . '” a fost adăugată cu succes!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Programare  $programare
     * @return \Illuminate\Http\Response
     */
    public function show($serviciu = null, Programare $programare)
    {
        return view('programari.show', compact('serviciu', 'programare'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Programare  $programare
     * @return \Illuminate\Http\Response
     */
    public function edit($serviciu = null, Programare $programare)
    {
        return view('programari.edit', compact('serviciu', 'programare'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Programare  $programare
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $serviciu = null, Programare $programare)
    {
        $programare->update($this->validateRequest($request, $serviciu, $programare));

        return redirect('/' . $serviciu . '/programari')->with('status', 'Programarea pentru „' . ($programare->nume ?? '') . '” a fost modificată cu succes!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Programare  $programare
     * @return \Illuminate\Http\Response
     */
    public function destroy($serviciu = null, Programare $programare)
    {
        $programare->delete();

        return redirect('/' . $serviciu . '/programari')->with('status', 'Programarea pentru „' . ($programare->nume ?? '') . '” a fost ștearsă cu succes!');
    }

    /**
     * Returnarea date axios
     */
    public function axios(Request $request)
    {
        switch ($_GET['request']) {
            case 'ore':
                $ore_disponibile = DB::table('programari_ore_de_program')
                    ->select(DB::raw('DATE_FORMAT(ora, "%H:%i") as ora'))
                    ->where('serviciu', $request->serviciu)
                    ->where('ziua_din_saptamana', '=', \Carbon\Carbon::parse($request->data)->dayOfWeekIso)
                    ->orderBy('ora')
                    ->pluck('ora')
                    ->all();

                $ora_initiala = $request->ora_initiala;
                $ore_indisponibile = DB::table('programari')
                    ->select(DB::raw('DATE_FORMAT(ora, "%H:%i") as ora'))
                    ->where('serviciu', $request->serviciu)
                    ->where('data', '=', $request->data)
                    // Daca data aleasa din calendar este aceeasi cu data programarii, se afiseaza si ora programarii ca fiind disponibila
                    ->when($request->data == $request->data_initiala, function ($query) use($ora_initiala) {
                        return $query->where('ora', '<>', $ora_initiala);
                    })
                    ->pluck('ora')
                    ->all();
                $ore_disponibile = array_diff($ore_disponibile, $ore_indisponibile);
                $raspuns = $ore_disponibile;
                break;
            default:
                break;
        }
        return response()->json([
            'raspuns' => $raspuns,
        ]);
    }


    /**
     * Validate the request attributes.
     *
     * @return array
     */
    protected function validateRequest(Request $request, $serviciu = null, Programare $programare = null)
    {
        switch ($serviciu) {
            case 'evidenta-persoanelor':
                $serviciu = 1;
                break;
            case 'transcrieri-certificate':
                $serviciu = 2;
                break;
            case 'casatorii':
                $serviciu = 3;
                break;
            default:
                # code...
                break;
            }

        $request->request->add(['serviciu' => $serviciu]);

        return $request->validate(
            [
                'nume' => 'required|max:500',
                'prenume' => 'required|max:500',
                'email' => 'nullable|max:500|email:rfc,dns',
                'cnp' => 'nullable|numeric|integer|digits:13',
                'data' => ['required',
                    'after:today',
                    'before:' . \Carbon\Carbon::today()->addMonth(1)->endOfMonth(),
                    function ($attribute, $value, $fail) use ($request) {
                        $data_selectata = \Carbon\Carbon::parse($value);
                        // dd($data_selectata, $value);
                        $zile_nelucratoare = DB::table('programari_zile_nelucratoare')->where('data', '>', \Carbon\Carbon::today())->pluck('data')->all();
                        if (
                            $data_selectata->isWeekend()
                            ||
                            // transcrieri-certificate: se lucreaza doar 2 zile pe saptamana (nu luni, joi sau vineri)
                            (($request->serviciu == 2) && ($data_selectata->isMonday() || $data_selectata->isThursday() || $data_selectata->isFriday()))
                            ||
                            (in_array($value, $zile_nelucratoare))) {
                            $fail('Data aleasă, ' . $data_selectata->isoFormat('DD.MM.YYYY') . ', nu este o zi lucrătoare');
                        }
                    },
                ],
                'ora' => [
                    'required',
                    function ($attribute, $value, $fail) use ($request, $programare) {

                        // Data se preia din:
                        // 1. Aplicatie angajati -> data se ia din request
                        // 2. Formular extern din site -> data se ia din sesiune
                        $data = $request->data ?? $request->session()->get('programare')->data;

                        $data_initiala = $request->data_initiala;
                        $ora_initiala = $request->ora_initiala;

                        $ore_disponibile = DB::table('programari_ore_de_program')
                            ->where('serviciu', $request->serviciu)
                            ->where('ziua_din_saptamana', '=', \Carbon\Carbon::parse($data)->dayOfWeekIso)
                            ->orderBy('ora')
                            ->pluck('ora')
                            ->all();
                        $ore_indisponibile = DB::table('programari')
                            ->where('serviciu', $request->serviciu)
                            ->where('data', '=', $data)
                            // Daca data aleasa din calendar este aceeasi cu data programarii, se afiseaza si ora programarii ca fiind disponibila
                            ->when($programare, function ($query) use($request, $programare) {
                                return $query->when($request->data == $programare->data, function ($query) use($programare) {
                                    return $query->where('ora', '<>', $programare->ora);
                                    });
                            })
                            ->pluck('ora')
                            ->all();
                        $ore_disponibile = array_diff($ore_disponibile, $ore_indisponibile);

                        if ((!in_array(\Carbon\Carbon::parse($value)->toTimeString(), $ore_disponibile))) {
                            $fail('Ora aleasă, ' . \Carbon\Carbon::parse($value)->isoFormat('HH:mm') . ', este indisponibilă');
                        }
                    },
                ],
                'serviciu' => 'required'
            ],
            [

            ]
        );
    }

    //
    // Functii pentru Multi Page Form pentru Clienti
    //
    /**
     * Show the step 1 Form for creating a new 'programare '.
     *
     * @return \Illuminate\Http\Response
     */
    public function adaugaProgramareNoua(Request $request, $serviciu = null)
    {
        if(!empty($request->session()->get($serviciu . '-programare'))){
            $programare = $request->session()->forget($serviciu . '-programare');
        }

        $programare = new Programare();

        switch ($serviciu) {
            case 'evidenta-persoanelor':
                $programare->serviciu = 1;
                break;
            case 'transcrieri-certificate':
                $programare->serviciu = 2;
                break;
            case 'casatorii':
                $programare->serviciu = 3;
                break;
            default:
                # code...
                break;
            }

        $request->session()->put($serviciu . '-programare', $programare);

        return redirect('/' . $serviciu . '/programari/adauga-programare-pasul-1');
    }

    /**
     * Show the step 1 Form for creating a new 'programare'.
     *
     * @return \Illuminate\Http\Response
     */
    public function adaugaProgramarePasul1(Request $request, $serviciu = null)
    {
        if(empty($request->session()->get($serviciu . '-programare'))){
            return redirect('/' . $serviciu . '/programari/adauga-programare-noua');
        } else {
            $programare = $request->session()->get($serviciu . '-programare');
        }

        $zile_nelucratoare = DB::table('programari_zile_nelucratoare')->where('data', '>', \Carbon\Carbon::today())->pluck('data')->all();

        $ore_disponibile = ProgramareOraDeProgram::all()->where('serviciu', $programare->serviciu);
        $ore_indisponibile = Programare::where('serviciu', $programare->serviciu)->where('data', '>=', \Carbon\Carbon::tomorrow()->toDateString())->get();
        // dd($ore_disponibile, $ore_indisponibile);
        $data = \Carbon\Carbon::tomorrow();
        $zile_pline = array();
        while ($data->lessThan(\Carbon\Carbon::today()->addMonth(1)->endOfMonth())){
            $ore_disponibile_la_data = $ore_disponibile->where('ziua_din_saptamana', $data->dayOfWeekIso)->pluck('ora')->toArray();
            $ore_indisponibile_la_data = $ore_indisponibile->where('data', $data->toDateString())->pluck('ora')->toArray();

            $ore_disponibile_ramase = array_diff($ore_disponibile_la_data, $ore_indisponibile_la_data);

            if (count($ore_disponibile_ramase) === 0){
                array_push($zile_pline, $data->toDateString());
            }

            // Se tot adauga cate o zi la data
            // Daca este weekend se sar 2 zile, sau daca sunt servicii la care nu se lucreaza in fiecare zi a saptamanii
            switch ($programare->serviciu) {
                case '1': // evidenta-persoanelor: au program in fiecare zi de lucru a saptamanii
                    switch ($data->dayOfWeekIso) {
                        case '5':
                            $data->addDay(3);
                            break;
                        case '6':
                            $data->addDay(2);
                            break;
                        default:
                            $data->addDay(1);
                            break;
                        }
                    break;
                case '2': // transcrieri-certificate: au program doar Marti si Miercuri
                    switch ($data->dayOfWeekIso) {
                        case '3':
                            $data->addDay(6);
                            break;
                        case '4':
                            $data->addDay(5);
                            break;
                        case '5':
                            $data->addDay(4);
                            break;
                        case '6':
                            $data->addDay(3);
                            break;
                        case '7':
                            $data->addDay(2);
                            break;
                        default:
                            $data->addDay(1);
                            break;
                        }
                    break;
                case '3': // casatorii: au program in fiecare zi de lucru a saptamanii
                    switch ($data->dayOfWeekIso) {
                        case '5':
                            $data->addDay(3);
                            break;
                        case '6':
                            $data->addDay(2);
                            break;
                        default:
                            $data->addDay(1);
                            break;
                        }
                    break;
                }
        }
        // dd($ore_disponibile, $ore_indisponibile, $zile_pline);

        return view('programari.guest_create.adauga_programare_pasul_1', compact('serviciu', 'programare', 'zile_nelucratoare', 'zile_pline'));
    }

    /**
     * Post Request to store step1 info in session
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postAdaugaProgramarePasul1(Request $request, $serviciu = null)
    {
        if(empty($request->session()->get($serviciu . '-programare'))){
            return redirect('/' . $serviciu . '/programari/adauga-programare-noua');
        } else {
            $programare = $request->session()->get($serviciu . '-programare');
        }

        $zile_nelucratoare = DB::table('programari_zile_nelucratoare')->where('data', '>', \Carbon\Carbon::today())->pluck('data')->all();

        $programare->fill(
            $request->validate([
                'data' => [
                    'required',
                    'date',
                    'after:today',
                    'before:' . \Carbon\Carbon::today()->addMonth(1)->endOfMonth(),
                    function ($attribute, $value, $fail) use ($request, $programare) {
                        $zile_nelucratoare = DB::table('programari_zile_nelucratoare')->where('data', '>', \Carbon\Carbon::today())->pluck('data')->all();
                        // dd($programare->serviciu);

                        $ziua = \Carbon\Carbon::parse($value);
                        if (
                            \Carbon\Carbon::parse($value)->isWeekend()
                            ||
                            // transcrieri-certificate: se lucreaza doar 2 zile pe saptamana (nu luni, joi sau vineri)
                            (($programare->serviciu == 2) && ($ziua->isMonday() || $ziua->isThursday() || $ziua->isFriday()))
                            ||
                            (in_array($value, $zile_nelucratoare))) {
                            $fail('Data aleasă, ' . \Carbon\Carbon::parse($value)->isoFormat('DD.MM.YYYY') . ', nu este o zi lucrătoare');
                        }
                    },
                ]
            ])
        );
        $programare->offsetUnset('ora');

        $request->session()->put($serviciu . '-programare', $programare);

        return redirect('/' . $serviciu . '/programari/adauga-programare-pasul-2');
    }

    /**
     * Show the step 2 Form for creating a new 'programare '.
     *
     * @return \Illuminate\Http\Response
     */
    public function adaugaProgramarePasul2(Request $request, $serviciu = null)
    {
        if(empty($request->session()->get($serviciu . '-programare'))){
            return redirect('/' . $serviciu . '/programari/adauga-programare-noua');
        } else {
            $programare = $request->session()->get($serviciu . '-programare');
        }

        $prima_ora_din_program = \Carbon\Carbon::parse(
            DB::table('programari_ore_de_program')->where('serviciu', $programare->serviciu)->where('ziua_din_saptamana', '=', \Carbon\Carbon::parse($programare->data)->dayOfWeekIso)->orderBy('ora')->pluck('ora')->first()
        );
        $ora_inceput = \Carbon\Carbon::today();
        $ora_inceput->hour = $prima_ora_din_program->hour;
        $ora_inceput->minute = $prima_ora_din_program->minute;

        $ultima_ora_din_program = \Carbon\Carbon::parse(
            DB::table('programari_ore_de_program')->where('serviciu', $programare->serviciu)->where('ziua_din_saptamana', '=', \Carbon\Carbon::parse($programare->data)->dayOfWeekIso)->orderBy('ora', 'desc')->pluck('ora')->first()
        );
        $ora_sfarsit = \Carbon\Carbon::today();
        $ora_sfarsit->hour = $ultima_ora_din_program->hour;
        $ora_sfarsit->minute = $ultima_ora_din_program->minute;

        // Programari evidenta persoanelor: la fiecare 15 minute
        // Programari transcrieri certificate sau casatorii: la fiecare 30 de minute
        $ora_sfarsit->addMinutes(($programare->serviciu == 1) ? 15 : 30);

        $ore_disponibile = DB::table('programari_ore_de_program')->where('serviciu', $programare->serviciu)->where('ziua_din_saptamana', '=', \Carbon\Carbon::parse($programare->data)->dayOfWeekIso)->orderBy('ora')->pluck('ora')->all();
        $ore_indisponibile = DB::table('programari')->where('serviciu', $programare->serviciu)->where('data', '=', $programare->data)->pluck('ora')->all();
        $ore_disponibile = array_diff($ore_disponibile, $ore_indisponibile);

        return view('programari.guest_create.adauga_programare_pasul_2', compact('serviciu', 'programare', 'ora_inceput', 'ora_sfarsit', 'ore_disponibile'));
    }

    /**
     * Post Request to store step2 info in session
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postAdaugaProgramarePasul2(Request $request, $serviciu = null)
    {
        if(empty($request->session()->get($serviciu . '-programare'))){
            return redirect('/' . $serviciu . '/programari/adauga-programare-noua');
        } else {
            $programare = $request->session()->get($serviciu . '-programare');
        }

        $programare->fill(
            $request->validate([
                'ora' => [
                    'required',
                    function ($attribute, $value, $fail) use ($request, $programare) {
                        $ore_disponibile = DB::table('programari_ore_de_program')->where('serviciu', $programare->serviciu)->where('ziua_din_saptamana', '=', \Carbon\Carbon::parse($programare->data)->dayOfWeekIso)->orderBy('ora')->pluck('ora')->all();
                        $ore_indisponibile = DB::table('programari')->where('serviciu', $programare->serviciu)->where('data', '=', $programare->data)->pluck('ora')->all();
                        $ore_disponibile = array_diff($ore_disponibile, $ore_indisponibile);

                        if ((!in_array(\Carbon\Carbon::parse($value)->toTimeString(), $ore_disponibile))) {
                            $fail('Ora aleasă, ' . \Carbon\Carbon::parse($value)->isoFormat('HH:mm') . ', este indisponibilă');
                        }
                    },
                ]
            ])
        );

        $request->session()->put($serviciu . '-programare', $programare);

        return redirect('/' . $serviciu . '/programari/adauga-programare-pasul-3');
    }

    /**
     * Show the step 3 Form for creating a new 'programare '.
     *
     * @return \Illuminate\Http\Response
     */
    public function adaugaProgramarePasul3(Request $request, $serviciu = null)
    {
        if (empty($request->session()->get($serviciu . '-programare'))){
            return redirect('/' . $serviciu . '/programari/adauga-programare-noua');
        } elseif (empty($request->session()->get($serviciu . '-programare')->ora)){
            return redirect('/' . $serviciu . '/programari/adauga-programare-pasul-2');
        } else {
            $programare = $request->session()->get($serviciu . '-programare');
        }

        return view('programari.guest_create.adauga_programare_pasul_3', compact('serviciu', 'programare'));
    }

    /**
     * Post Request to store step3 info in session
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postAdaugaProgramarePasul3(Request $request, $serviciu = null)
    {
        if(empty($request->session()->get($serviciu . '-programare'))){
            return redirect('/' . $serviciu . '/programari/adauga-programare-noua');
        } else {
            $programare = $request->session()->get($serviciu . '-programare');
        }

        $programare->fill(
            $request->validate([
                'nume' => 'required|max:500',
                'prenume' => 'nullable|max:500',
                'email' => 'required|max:500|email:rfc,dns',
                'cnp' => 'required|numeric|integer|digits:13',
                'gdpr' => 'required',
                'acte_necesare' => 'required'
            ])
        );


        // Verificare ca ora nu a fost aleasa intre timp de cand s-a inceput aceasta programare
        $ore_disponibile = DB::table('programari_ore_de_program')->where('serviciu', $programare->serviciu)->where('ziua_din_saptamana', '=', \Carbon\Carbon::parse($programare->data)->dayOfWeekIso)->orderBy('ora')->pluck('ora')->all();
        $ore_indisponibile = DB::table('programari')->where('serviciu', $programare->serviciu)->where('data', '=', $programare->data)->pluck('ora')->all();
        $ore_disponibile = array_diff($ore_disponibile, $ore_indisponibile);

        if ((!in_array(\Carbon\Carbon::parse($programare->ora)->toTimeString(), $ore_disponibile))) {
            // Se salveaza datele introduse pentru a nu mai fi nevoie de introdus incă odată
            $request->session()->put($serviciu . '-programare', $programare);

            // Este intors inapoi, pentru a selecta alte date
            return back()
                ->with('eroare', 'Ne pare rău, dar ora aleasă a fost deja înregistrată de altă persoană. Vă rugăm să faceți altă programare.');
        }


        unset($programare->gdpr, $programare->acte_necesare);

        $programare->save();

        if (isset($programare->email)){
            \Mail::to($programare->email)
                ->send(
                    new ProgramareEmail($programare)
                );
        }

        $request->session()->put($serviciu . '-programare', $programare);

        return redirect('/' . $serviciu . '/programari/adauga-programare-pasul-4');
    }

    /**
     * Show the step 4 Form for creating a new 'programare '.
     *
     * @return \Illuminate\Http\Response
     */
    public function adaugaProgramarePasul4(Request $request, $serviciu = null)
    {
        if(empty($request->session()->get($serviciu . '-programare'))){
            return redirect('/' . $serviciu . '/programari/adauga-programare-noua');
        } else {
            $programare = $request->session()->get($serviciu . '-programare');
        }

        $request->session()->forget($serviciu . '-programare');

        return view('programari.guest_create.adauga_programare_pasul_4', compact('serviciu', 'programare'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function afisare_saptamanal($serviciu = null)
    {
        $search_data = \Request::get('search_data') ? \Carbon\Carbon::parse(\Request::get('search_data')) : \Carbon\Carbon::today();
        $data_de_cautat = \Carbon\Carbon::parse($search_data);

        $ore_de_program = ProgramareOraDeProgram::select('ziua_din_saptamana', 'ora')
            ->where (function($query) use ($serviciu) {
                switch ($serviciu) {
                    case 'evidenta-persoanelor':
                        $query->where('serviciu', 1);
                        break;
                    case 'transcrieri-certificate':
                        $query->where('serviciu', 2);
                        break;
                    case 'casatorii':
                        $query->where('serviciu', 3);
                        break;
                    default:
                        # code...
                        break;
                }
            })
            ->orderBy('ziua_din_saptamana')
            ->orderBy('ora')
            ->get();

        $programari_din_saptamana_cautata = Programare::
            where (function($query) use ($serviciu) {
                switch ($serviciu) {
                    case 'evidenta-persoanelor':
                        $query->where('serviciu', 1);
                        break;
                    case 'transcrieri-certificate':
                        $query->where('serviciu', 2);
                        break;
                    case 'casatorii':
                        $query->where('serviciu', 3);
                        break;
                    default:
                        # code...
                        break;
                }
            })
            ->whereDate('data', '>=', $data_de_cautat->startOfWeek())
            ->whereDate('data', '<=', $data_de_cautat->endOfWeek())
            ->orderBy('ora')
            ->get();

        return view('programari.diverse.afisare_saptamanal', compact('programari_din_saptamana_cautata', 'ore_de_program', 'search_data', 'serviciu'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function afisare_zilnic($serviciu = null)
    {
        $search_data = \Request::get('search_data') ? \Carbon\Carbon::parse(\Request::get('search_data')) : \Carbon\Carbon::today();

        $ore_de_program = ProgramareOraDeProgram::select('ziua_din_saptamana', 'ora')
            ->where (function($query) use ($serviciu) {
                switch ($serviciu) {
                    case 'evidenta-persoanelor':
                        $query->where('serviciu', 1);
                        break;
                    case 'transcrieri-certificate':
                        $query->where('serviciu', 2);
                        break;
                    case 'casatorii':
                        $query->where('serviciu', 3);
                        break;
                    default:
                        # code...
                        break;
                }
            })
            ->where('ziua_din_saptamana', $search_data->dayOfWeekIso)
            ->orderBy('ora')
            ->get();

        $programari = Programare::
            where (function($query) use ($serviciu) {
                switch ($serviciu) {
                    case 'evidenta-persoanelor':
                        $query->where('serviciu', 1);
                        break;
                    case 'transcrieri-certificate':
                        $query->where('serviciu', 2);
                        break;
                    case 'casatorii':
                        $query->where('serviciu', 3);
                        break;
                    default:
                        # code...
                        break;
                }
            })
            ->whereDate('data', '=', $search_data)
            ->orderBy('ora')
            ->get();

        return view('programari.diverse.afisare_zilnic', compact('programari', 'ore_de_program', 'search_data', 'serviciu'));
    }

    public function pdfExportPeZi(Request $request, $serviciu = null, $data = null)
    {
        $data = \Carbon\Carbon::parse($data);

        $ore_de_program = ProgramareOraDeProgram::select('ziua_din_saptamana', 'ora')
            ->where (function($query) use ($serviciu) {
                switch ($serviciu) {
                    case 'evidenta-persoanelor':
                        $query->where('serviciu', 1);
                        break;
                    case 'transcrieri-certificate':
                        $query->where('serviciu', 2);
                        break;
                    case 'casatorii':
                        $query->where('serviciu', 3);
                        break;
                    default:
                        # code...
                        break;
                }
            })
            ->where('ziua_din_saptamana', $data->dayOfWeekIso)
            ->orderBy('ora')
            ->get();

        $programari = Programare::
            where (function($query) use ($serviciu) {
                switch ($serviciu) {
                    case 'evidenta-persoanelor':
                        $query->where('serviciu', 1);
                        break;
                    case 'transcrieri-certificate':
                        $query->where('serviciu', 2);
                        break;
                    case 'casatorii':
                        $query->where('serviciu', 3);
                        break;
                    default:
                        # code...
                        break;
                }
            })
            ->whereDate('data', '=', $data)
            ->orderBy('ora')
            ->get();

        if ($request->view_type === 'programari-html') {
            return view('programari.export.programari-pdf', compact('programari', 'ore_de_program', 'data', 'serviciu'));
        } elseif ($request->view_type === 'programari-pdf') {
            $pdf = \PDF::loadView('programari.export.programari-pdf', compact('programari', 'ore_de_program', 'data', 'serviciu'))
                ->setPaper('a4');
            return $pdf->download('Programari din data ' . $data->isoFormat('DD.MM.YYYY') . '.pdf');
        }
    }

}
