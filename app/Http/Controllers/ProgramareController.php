<?php

namespace App\Http\Controllers;

use App\Models\Programare;
use App\Models\ProgramareOraDeProgram;
use Illuminate\Support\Facades\DB;

use App\Mail\ProgramareEmail;

use Illuminate\Http\Request;

use Carbon\Carbon;

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
                    case 'casatorii-oficieri-sediu':
                        $query->where('serviciu', 4);
                        break;
                    case 'casatorii-oficieri-foisor':
                        $query->where('serviciu', 5);
                        break;
                    case 'casatorii-oficieri-teatru':
                        $query->where('serviciu', 6);
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
        $programare = Programare::make($this->validateRequest($request, $serviciu));
        $programare->cheie_unica = uniqid();
        $programare->save();

        if (isset($programare->email)){
            \Mail::to($programare->email)
                ->send(
                    new ProgramareEmail($programare)
                );
        }

        return redirect('/' . $serviciu . '/programari')->with('status', 'Programarea pentru „' . ($programare->nume ?? '') . ' ' . ($programare->prenume ?? '') . '” a fost adăugată cu succes!');
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

        return redirect('/' . $serviciu . '/programari')->with('status', 'Programarea pentru „' . ($programare->nume ?? '') . ' ' . ($programare->prenume ?? '') . '” a fost modificată cu succes!');
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

        return redirect('/' . $serviciu . '/programari')->with('status', 'Programarea pentru „' . ($programare->nume ?? '') . ' ' . ($programare->prenume ?? '') . '” a fost ștearsă cu succes!');
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
            case 'casatorii-oficieri-sediu':
                $serviciu = 4;
                break;
            case 'casatorii-oficieri-foisor':
                $serviciu = 5;
                break;
            case 'casatorii-oficieri-teatru':
                $serviciu = 6;
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
                'cnp' => 'nullable|numeric|integer|digits:13',

                // Doar pentru casatorii-oficieri
                'nume_sotie' => ($serviciu == 4 || $serviciu == 5 || $serviciu == 6) ? 'required|max:500' : '',
                'prenume_sotie' => ($serviciu == 4 || $serviciu == 5 || $serviciu == 6) ? 'required|max:500' : '',
                'cnp_sotie' => ($serviciu == 4 || $serviciu == 5 || $serviciu == 6) ? 'required|numeric|integer|digits:13' : '',
                'telefon' => ($serviciu == 4 || $serviciu == 5 || $serviciu == 6) ? 'required|max:500' : '',

                'email' => 'nullable|max:500|email:rfc,dns',
                'data' => ['required',
                    'after:today',
                    // 'before:' . \Carbon\Carbon::today()->addMonthsNoOverflow(1)->endOfMonth(),
                    // function ($attribute, $value, $fail) use ($request) {
                    //     $data_selectata = \Carbon\Carbon::parse($value);
                    //     // dd($data_selectata, $value);
                    //     $zile_nelucratoare = DB::table('programari_zile_nelucratoare')->where('data', '>', \Carbon\Carbon::today())->pluck('data')->all();
                    //     if (
                    //         $data_selectata->isWeekend()
                    //         ||
                    //         // transcrieri-certificate: se lucreaza doar 2 zile pe saptamana (nu luni, joi sau vineri)
                    //         (($request->serviciu == 2) && ($data_selectata->isMonday() || $data_selectata->isThursday() || $data_selectata->isFriday()))
                    //         ||
                    //         (in_array($value, $zile_nelucratoare))) {
                    //         $fail('Data aleasă, ' . $data_selectata->isoFormat('DD.MM.YYYY') . ', nu este o zi lucrătoare');
                    //     }
                    // },
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
            $request->session()->forget($serviciu . '-programare');
            $request->session()->forget($serviciu . '-programare-duplicat-in-DB');
        }

        $programare = new Programare();

        switch ($serviciu) {
            case 'evidenta-persoanelor':
                $programare->serviciu = 1;
                $request->session()->put($serviciu . '-programare', $programare);
                return redirect('/' . $serviciu . '/programari/adauga-programare-pasul-1');
                break;
            case 'transcrieri-certificate':
                $programare->serviciu = 2;
                $request->session()->put($serviciu . '-programare', $programare);
                return redirect('/' . $serviciu . '/programari/adauga-programare-pasul-1');
                break;
            // Nu se mai doreste acest serviciu, asa ca a fost scos
            // case 'casatorii':
            //     $programare->serviciu = 3;
            //     $request->session()->put($serviciu . '-programare', $programare);
            //     return redirect('/' . $serviciu . '/programari/adauga-programare-pasul-1');
            //     break;
            case 'casatorii-oficieri':
                $programare->serviciu = 456;
                $request->session()->put($serviciu . '-programare', $programare);
                return redirect('/' . $serviciu . '/programari/adauga-programare-pasul-0'); // Doar pentru „Casatorii oficieri”, care au 3 locatii diferite
                break;
            default:
                return redirect()->away('https://evidentapersoanelorfocsani.ro/');
                break;
            }

    }

    // Doar pentru „Casatorii oficieri”, care au 3 locatii diferite
    public function adaugaProgramarePasul0(Request $request, $serviciu = null)
    {
        if(empty($request->session()->get($serviciu . '-programare'))){
            return redirect('/' . $serviciu . '/programari/adauga-programare-noua');
        } else {
            $programare = $request->session()->get($serviciu . '-programare');
            // Se sterge programarea duplicat, pentru situatiile cand se foloseste butonul „Inapoi”
            $request->session()->forget($serviciu . '-programare-duplicat-in-DB');
        }

        return view('programari.guest_create.adauga_programare_pasul_0', compact('serviciu'));
    }

    // Doar pentru „Casatorii oficieri”, care au 3 locatii diferite
    public function postAdaugaProgramarePasul0(Request $request, $serviciu = null)
    {
        if(empty($request->session()->get($serviciu . '-programare'))){
            return redirect('/' . $serviciu . '/programari/adauga-programare-noua');
        } else {
            $programare = $request->session()->get($serviciu . '-programare');
        }

        $request->validate([
            'serviciu' => 'integer|between:4,6'
        ]);

        $programare->serviciu = $request->serviciu;

        // Se sterge data si ora, de siguranta, pentru situatiile cand se foloseste butonul „Inapoi”
        $programare->offsetUnset('data', 'ora');

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
            // Se sterge programarea duplicat, pentru situatiile cand se foloseste butonul „Inapoi”
            $request->session()->forget($serviciu . '-programare-duplicat-in-DB');
        }

        $zile_nelucratoare = DB::table('programari_zile_nelucratoare')->where('serviciu', $programare->serviciu)->where('data', '>', \Carbon\Carbon::today())->pluck('data')->all();

        $ore_disponibile = ProgramareOraDeProgram::all()->where('serviciu', $programare->serviciu);
        $ore_indisponibile = Programare::where('serviciu', $programare->serviciu)->where('data', '>=', \Carbon\Carbon::tomorrow()->toDateString())->get();
        // dd($ore_disponibile, $ore_indisponibile);
        $data = \Carbon\Carbon::tomorrow();
        $zile_pline = array();
        while ($data->lessThan(\Carbon\Carbon::today()->addMonthsNoOverflow(1)->endOfMonth())){
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
                case '2': // transcrieri-certificate: au program doar Miercuri
                    switch ($data->dayOfWeekIso) {
                        case '1':
                            $data->addDay(2);
                            break;
                        case '3':
                            $data->addDay(7);
                            break;
                        case '4':
                            $data->addDay(6);
                            break;
                        case '5':
                            $data->addDay(5);
                            break;
                        case '6':
                            $data->addDay(4);
                            break;
                        case '7':
                            $data->addDay(3);
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
                case '4': // casatorii-oficieri-sediu: au program in fiecare zi de lucru a saptamanii
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
                case '5': // casatorii-oficieri-foisor: au program doar sambata si duminica
                    switch ($data->dayOfWeekIso) {
                        case '1':
                            $data->addDay(5);
                            break;
                        case '2':
                            $data->addDay(4);
                            break;
                        case '3':
                            $data->addDay(3);
                            break;
                        case '4':
                            $data->addDay(2);
                            break;
                        case '5':
                            $data->addDay(1);
                            break;
                        case '6':
                            $data->addDay(1);
                            break;
                        case '7':
                            $data->addDay(6);
                            break;
                        default:
                            $data->addDay(1);
                            break;
                        }
                    break;
                case '6': // casatorii-oficieri-teatru: au program in fiecare zi a saptamanii
                    switch ($data->dayOfWeekIso) {
                        // case '1':
                        //     $data->addDay(1);
                        //     break;
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
// dd(\Carbon\Carbon::today()->addMonthsNoOverflow(2)->endOfMonth(), Carbon::today()->addDays(12));
        $zile_nelucratoare = DB::table('programari_zile_nelucratoare')->where('serviciu', $programare->serviciu)->where('data', '>', \Carbon\Carbon::today())->pluck('data')->all();
// dd($request);
        $programare->fill(
            $request->validate([
                'data' => [
                    'required',
                    'date',
                    'after:' . ( ($programare->serviciu == 4 || $programare->serviciu == 5 || $programare->serviciu == 6) ? Carbon::today()->addDays(11)->isoFormat('DD.MM.YYYY') : 'today'),
                    'before:' .
                        // Pentru evidenta-persoanelor, transcrieri-certificate, casatorii: 2 luni
                        // Pentru casatorii-oficieri: 12 luni
                        \Carbon\Carbon::today()->addMonthsNoOverflow(
                                ( ($programare->serviciu == 1) || ($programare->serviciu == 2) || ($programare->serviciu == 3) ) ?
                                    1 // se adauga inca o luna
                                    :
                                    (
                                        ( ($programare->serviciu == 4) || ($programare->serviciu == 5) || ($programare->serviciu == 6) ) ?
                                        11 // se adauga inca 11 luni
                                        :
                                        0 // se adauga inca 0 luni - nu ar trebui sa se ajunga niciodata aici
                                    )
                            )->endOfMonth(),
                    function ($attribute, $value, $fail) use ($request, $programare) {
                        $zile_nelucratoare = DB::table('programari_zile_nelucratoare')->where('serviciu', $programare->serviciu)->where('data', '>', \Carbon\Carbon::today())->pluck('data')->all();
                        // dd($programare->serviciu);

                        $ziua = \Carbon\Carbon::parse($value);
                        if (
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

                            // casatorii-oficieri - teatru - se lucreaza in fiecare zi

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
            // Se sterge programarea duplicat, pentru situatiile cand se foloseste butonul „Inapoi”
            $request->session()->forget($serviciu . '-programare-duplicat-in-DB');
        }

        $prima_ora_din_program = \Carbon\Carbon::parse(
            DB::table('programari_ore_de_program')->where('serviciu', $programare->serviciu)->where('ziua_din_saptamana', '=', \Carbon\Carbon::parse($programare->data)->dayOfWeekIso)->orderBy('ora')->pluck('ora')->first()
        );
        $ultima_ora_din_program = \Carbon\Carbon::parse(
            DB::table('programari_ore_de_program')->where('serviciu', $programare->serviciu)->where('ziua_din_saptamana', '=', \Carbon\Carbon::parse($programare->data)->dayOfWeekIso)->orderBy('ora', 'desc')->pluck('ora')->first()
        );

        $ore_disponibile = DB::table('programari_ore_de_program')->where('serviciu', $programare->serviciu)->where('ziua_din_saptamana', '=', \Carbon\Carbon::parse($programare->data)->dayOfWeekIso)->orderBy('ora')->pluck('ora')->all();
        $ore_indisponibile = DB::table('programari')->where('serviciu', $programare->serviciu)->where('data', '=', $programare->data)->orderBy('ora')->pluck('ora')->all();
        $ore_disponibile = array_diff($ore_disponibile, $ore_indisponibile);

        if ($programare->serviciu == 5 || $programare->serviciu == 6){ // Programarile pentru foisor si teatru se pot face la maxim 60 minute distanta una de alta, pentru a nu fi goluri mari
            if (count($ore_indisponibile) > 0){ // Daca exista macar o programare, ca altfel utilizatorul poate alege orice din program
                // In varianta aceasta, intervalele sunt marcate ca „Perioada in afara programului”
                // if ($prima_ora_din_program < Carbon::parse($ore_indisponibile[0])->subMinutes(60)){ // maxim 60 de minute inaintea primei programari
                //     $prima_ora_din_program = Carbon::parse($ore_indisponibile[0])->subMinutes(60);
                // }
                // if ($ultima_ora_din_program > Carbon::parse($ore_indisponibile[count($ore_indisponibile)-1])->addMinutes(60)){ // maxim 60 de minute inaintea primei programari
                //     $ultima_ora_din_program = Carbon::parse($ore_indisponibile[count($ore_indisponibile)-1])->addMinutes(60);
                // }

                // In varianta aceasta, intervalele sunt marcate ca „Perioada indisponibila”
                $ore_disponibile = array_filter($ore_disponibile,function($ora) use($ore_indisponibile){
                    return (
                        ($ora) >= Carbon::parse($ore_indisponibile[0])->subMinutes(60)->toTimeString() // maxim 60 de minute inaintea primei programari
                        &&
                        ($ora) <= Carbon::parse($ore_indisponibile[count($ore_indisponibile)-1])->addMinutes(60)->toTimeString() // maxim 60 de minute dupa ultima programare
                    );
                });
            }
        }

        $ora_inceput = \Carbon\Carbon::today();
        $ora_inceput->hour = $prima_ora_din_program->hour;
        $ora_inceput->minute = $prima_ora_din_program->minute;

        $ora_sfarsit = \Carbon\Carbon::today();
        $ora_sfarsit->hour = $ultima_ora_din_program->hour;
        $ora_sfarsit->minute = $ultima_ora_din_program->minute;

        // Programari evidenta persoanelor: la fiecare 15 minute
        // Programari transcrieri certificate: la fiecare 40 de minute
        // Programari casatorii: la fiecare 30 de minute
        // Programari casatorii-oficieri: la fiecare 15 de minute
        $ora_sfarsit->addMinutes(($programare->serviciu == 1 || $programare->serviciu == 4 || $programare->serviciu == 5 || $programare->serviciu == 6) ? 15 : (($programare->serviciu == 2) ? 40 : 30));

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
                        $ore_indisponibile = DB::table('programari')->where('serviciu', $programare->serviciu)->where('data', '=', $programare->data)->orderBy('ora')->pluck('ora')->all();
                        $ore_disponibile = array_diff($ore_disponibile, $ore_indisponibile);

                        if ($programare->serviciu == 5){ // Programarile pentru foisor se pot face la maxim 60 minute distanta una de alta, pentru a nu fi goluri mari
                            if (count($ore_indisponibile) > 0){ // Daca exista macar o programare, ca altfel utilizatorul poate alege orice din program
                                $ore_disponibile = array_filter($ore_disponibile,function($ora) use($ore_indisponibile){
                                    return (
                                        ($ora) >= Carbon::parse($ore_indisponibile[0])->subMinutes(60)->toTimeString() // maxim 60 de minute inaintea primei programari
                                        &&
                                        ($ora) <= Carbon::parse($ore_indisponibile[count($ore_indisponibile)-1])->addMinutes(60)->toTimeString() // maxim 60 de minute dupa ultima programare
                                    );
                                });
                            }
                        }

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
                'prenume' => 'required|max:500',

                // CNP nu este obligatoriu pentru: Transcrieri certificate in zilele de miercuri (pentru cetateni straini, moldoveni, ce nu au inca buletin, cnp)
                'cnp' => (($programare->serviciu == 2) && (Carbon::parse($programare->data)->dayOfWeekIso == 3)) ? 'nullable' : 'required'
                    . '|numeric|integer|digits:13',

                // Doar pentru casatorii-oficieri
                'nume_sotie' => ($programare->serviciu == 4 || $programare->serviciu == 5 || $programare->serviciu == 6) ? 'required|max:500' : '',
                'prenume_sotie' => ($programare->serviciu == 4 || $programare->serviciu == 5 || $programare->serviciu == 6) ? 'required|max:500' : '',
                'cnp_sotie' => ($programare->serviciu == 4 || $programare->serviciu == 5 || $programare->serviciu == 6) ? 'required|numeric|integer|digits:13' : '',
                'telefon' => ($programare->serviciu == 4 || $programare->serviciu == 5 || $programare->serviciu == 6) ? 'required|max:500' : '',

                'email' => 'required|max:500|email',

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


        // Verificare daca nu cumva exista deja o viitoare programare pentru acest CNP, caz in care i se ofera alternativa sa pastreze programarea sau sa o modifice
        if ($programare->cnp){ //Daca programarea nu are CNP, este pentru transcrieri acte de miercuri, si se sare peste
            if ($request->session()->has($serviciu . '-programare-duplicat-in-DB')){
                // Daca exista deja in sesiune variabila programare_duplicat, inseamna ca utilizatorul a fost de acord sa o stearga pe cea veche
                $programare_duplicat_in_DB = $request->session()->get($serviciu . '-programare-duplicat-in-DB');
                $programare_duplicat_in_DB->delete();
            } else{
                // Daca nu exista in sesiune variabila programare_duplicat, o incarcam acum si ne intoarcem in formular sa atentionam utilizatorul

                // Pentru evidenta-persoanelor, transcrieri-certificate, casatorii, se verifica doar in serviciul respectiv
                // Pentru casatorii-oficieri, se verifica in 3 servicii, pentru sediu, foisor si teatru
                if ($programare->serviciu == 1 || $programare->serviciu == 2 || $programare->serviciu == 3) {
                    $programare_duplicat_in_DB = Programare::where('cnp', $programare->cnp)->where('serviciu', $programare->serviciu)->whereDate('data', '>', Carbon::today())->first();
                } else if ($programare->serviciu == 4 || $programare->serviciu == 5 || $programare->serviciu == 6) {
                    $programare_duplicat_in_DB = Programare::where('cnp', $programare->cnp)->whereIn('serviciu', [4,5,6])->whereDate('data', '>', Carbon::today())->first();
                }

                if (!is_null($programare_duplicat_in_DB)){
                    $request->session()->put($serviciu . '-programare-duplicat-in-DB', $programare_duplicat_in_DB);
                    return back()->with('warning', 'Există deja o programare pentru acest CNP: ' . $programare_duplicat_in_DB->cnp . '
                        pe numele ' . $programare_duplicat_in_DB->nume . ' ' . $programare_duplicat_in_DB->prenume . ' ,
                        în data de '. Carbon::parse($programare_duplicat_in_DB->data)->dayName . ', ' . Carbon::parse($programare_duplicat_in_DB->data)->isoFormat('DD MMMM YYYY') .
                        ', ora ' . Carbon::parse($programare_duplicat_in_DB->ora)->isoFormat('HH:mm') . '.
                        Doriți să ștergeți vechea programarea, și să o înlocuiți cu cea de acum?' );
                }
            }
        }


        unset($programare->gdpr, $programare->acte_necesare);

        $programare->cheie_unica = uniqid();

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
        $request->session()->forget($serviciu . '-programare-duplicat-in-DB');

        return view('programari.guest_create.adauga_programare_pasul_4', compact('serviciu', 'programare'));
    }


    /**
     *
     */
    public function stergeProgramarePasul1(Request $request, $serviciu = null, $cheie_unica = null)
    {
        $programare = Programare::where('cheie_unica', $cheie_unica)->first();

        // if (!is_null($programare)){
            return view('programari.guest_delete.sterge_programare_pasul_1', compact('serviciu', 'cheie_unica', 'programare'));
        // } else {
        //     return redirect ('https://evidentapersoanelorfocsani.ro/');
        // }
    }

    /**
     *
     */
    public function postStergeProgramarePasul1(Request $request, $serviciu = null, $cheie_unica = null)
    {
        $programare = Programare::where('cheie_unica', $cheie_unica)->first();

        if (!is_null($programare) && (\Carbon\Carbon::parse($programare->data)->greaterThan(\Carbon\Carbon::today()))){
            $programare->delete();
            return redirect('/' . $serviciu . '/programari/sterge-programare-pasul-2/' . $cheie_unica);
        } else {
            return redirect ('https://evidentapersoanelorfocsani.ro/');
        }
    }

    /**
     *
     */
    public function stergeProgramarePasul2(Request $request, $serviciu = null, $cheie_unica = null)
    {
        return view('programari.guest_delete.sterge_programare_pasul_2', compact('serviciu'));
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
                    case 'casatorii-oficieri-sediu':
                        $query->where('serviciu', 4);
                        break;
                    case 'casatorii-oficieri-foisor':
                        $query->where('serviciu', 5);
                        break;
                    case 'casatorii-oficieri-teatru':
                        $query->where('serviciu', 6);
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
                    case 'casatorii-oficieri-sediu':
                        $query->where('serviciu', 4);
                        break;
                    case 'casatorii-oficieri-foisor':
                        $query->where('serviciu', 5);
                        break;
                    case 'casatorii-oficieri-teatru':
                        $query->where('serviciu', 6);
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
                    case 'casatorii-oficieri-sediu':
                        $query->where('serviciu', 4);
                        break;
                    case 'casatorii-oficieri-foisor':
                        $query->where('serviciu', 5);
                        break;
                    case 'casatorii-oficieri-teatru':
                        $query->where('serviciu', 6);
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
                    case 'casatorii-oficieri-sediu':
                        $query->where('serviciu', 4);
                        break;
                    case 'casatorii-oficieri-foisor':
                        $query->where('serviciu', 5);
                        break;
                    case 'casatorii-oficieri-teatru':
                        $query->where('serviciu', 6);
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
                    case 'casatorii-oficieri-sediu':
                        $query->where('serviciu', 4);
                        break;
                    case 'casatorii-oficieri-foisor':
                        $query->where('serviciu', 5);
                        break;
                    case 'casatorii-oficieri-teatru':
                        $query->where('serviciu', 6);
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
                    case 'casatorii-oficieri-sediu':
                        $query->where('serviciu', 4);
                        break;
                    case 'casatorii-oficieri-foisor':
                        $query->where('serviciu', 5);
                        break;
                    case 'casatorii-oficieri-teatru':
                        $query->where('serviciu', 6);
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
