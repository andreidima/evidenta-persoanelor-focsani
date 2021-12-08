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
            ->when($search_data, function ($query, $search_data) {
                return $query->whereDate('data', '=', $search_data);
            })
            ->latest()
            ->simplePaginate(25);

        return view('programari.index', compact('programari', 'search_nume', 'search_data', 'serviciu'));
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
        $programare->update($this->validateRequest($request, $serviciu));

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
    protected function validateRequest(Request $request, $serviciu = null)
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
                'email' => 'nullable|max:500|email:rfc,dns',
                'cnp' => 'nullable|numeric|integer|digits:13',
                'data' => ['required',
                    'after:today',
                    function ($attribute, $value, $fail) {
                        $zile_nelucratoare = DB::table('programari_zile_nelucratoare')->where('data', '>', \Carbon\Carbon::today())->pluck('data')->all();
                        if (\Carbon\Carbon::parse($value)->isWeekend() || (in_array($value, $zile_nelucratoare))) {
                            $fail('Data aleasă, ' . \Carbon\Carbon::parse($value)->isoFormat('DD.MM.YYYY') . ', nu este o zi lucrătoare');
                        }
                    },
                ],
                'ora' => [
                    'required',
                    function ($attribute, $value, $fail) use ($request) {

                        // Data se preia din:
                        // 1. Aplicatie angajati -> data se ia din request
                        // 2. Formular extern din site -> data se ia din sesiune
                        $data = $request->data ?? $request->session()->get('programare')->data;

                        $ore_disponibile = DB::table('programari_ore_de_program')
                            ->where('serviciu', $request->serviciu)
                            ->where('ziua_din_saptamana', '=', \Carbon\Carbon::parse($data)->dayOfWeekIso)
                            ->orderBy('ora')
                            ->pluck('ora')
                            ->all();
                        $ore_indisponibile = DB::table('programari')
                            ->where('serviciu', $request->serviciu)
                            ->where('data', '=', $data)
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
            // $programare = $request->session()->forget($serviciu . '-programare');
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
        dd($request->session(), $request->session()->get($serviciu . '-programare'));
        if(empty($request->session()->get('programare'))){
            return redirect('/' . $serviciu . '/programari/adauga-programare-noua');
        } else {
            $programare = $request->session()->get('programare');
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

            if($data->dayOfWeekIso === 5){
                $data->addDay(3);
            } elseif ($data->dayOfWeekIso === 6){
                $data->addDay(2);
            } else{
                $data->addDay(1);
            }
        }
        dd($zile_pline);

        return view('programari.guest_create.adauga_programare_pasul_1', compact('programare', 'zile_nelucratoare', 'zile_pline', 'serviciu'));
    }

    /**
     * Post Request to store step1 info in session
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postAdaugaProgramarePasul1(Request $request, $serviciu = null)
    {
        if(empty($request->session()->get('programare'))){
            $programare = new Programare();
        }else{
            $programare = $request->session()->get('programare');
        }

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

        $zile_nelucratoare = DB::table('programari_zile_nelucratoare')->where('data', '>', \Carbon\Carbon::today())->pluck('data')->all();

        $programare->fill(
            $request->validate([
                'data' => [
                    'required',
                    'date',
                    'after:today',
                    function ($attribute, $value, $fail) use ($request) {
                        $zile_nelucratoare = DB::table('programari_zile_nelucratoare')->where('data', '>', \Carbon\Carbon::today())->pluck('data')->all();
                        // dd($value);
                        if (\Carbon\Carbon::parse($request->data)->isWeekend() || (in_array($value, $zile_nelucratoare))) {
                            $fail('Data aleasă, ' . $value . ', nu este o zi lucrătoare');
                        }
                    },
                ]
            ])
        );
        $programare->offsetUnset('ora');

        $request->session()->put('programare', $programare);

        return redirect('/' . $serviciu . '/programari/adauga-programare-pasul-2');
    }

    /**
     * Show the step 2 Form for creating a new 'programare '.
     *
     * @return \Illuminate\Http\Response
     */
    public function adaugaProgramarePasul2(Request $request)
    {
        if(empty($request->session()->get('programare'))){
            return redirect('/programari/adauga-programare-noua');
        }else{
            $programare = $request->session()->get('programare');

            $prima_ora_din_program = \Carbon\Carbon::parse(
                DB::table('programari_ore_de_program')->where('ziua_din_saptamana', '=', \Carbon\Carbon::parse($programare->data)->dayOfWeekIso)->orderBy('ora')->pluck('ora')->first()
            );
            $ora_inceput = \Carbon\Carbon::today();
            $ora_inceput->hour = $prima_ora_din_program->hour;
            $ora_inceput->minute = $prima_ora_din_program->minute;

            $ultima_ora_din_program = \Carbon\Carbon::parse(
                DB::table('programari_ore_de_program')->where('ziua_din_saptamana', '=', \Carbon\Carbon::parse($programare->data)->dayOfWeekIso)->orderBy('ora', 'desc')->pluck('ora')->first()
            );
            $ora_sfarsit = \Carbon\Carbon::today();
            $ora_sfarsit->hour = $ultima_ora_din_program->hour;
            $ora_sfarsit->minute = $ultima_ora_din_program->minute;
            $ora_sfarsit->addMinutes(15);

            $ore_disponibile = DB::table('programari_ore_de_program')->where('ziua_din_saptamana', '=', \Carbon\Carbon::parse($programare->data)->dayOfWeekIso)->orderBy('ora')->pluck('ora')->all();
            $ore_indisponibile = DB::table('programari')->where('data', '=', $programare->data)->pluck('ora')->all();
            $ore_disponibile = array_diff($ore_disponibile, $ore_indisponibile);

            return view('programari.guest_create.adauga_programare_pasul_2', compact('programare', 'ora_inceput', 'ora_sfarsit', 'ore_disponibile'));
        }
    }

    /**
     * Post Request to store step2 info in session
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postAdaugaProgramarePasul2(Request $request)
    {
        $programare = $request->session()->get('programare');

        $programare->fill(
            $request->validate([
                'ora' => [
                    'required',
                    function ($attribute, $value, $fail) use ($request, $programare) {
                        $ore_disponibile = DB::table('programari_ore_de_program')->where('ziua_din_saptamana', '=', \Carbon\Carbon::parse($programare->data)->dayOfWeekIso)->orderBy('ora')->pluck('ora')->all();
                        $ore_indisponibile = DB::table('programari')->where('data', '=', $programare->data)->pluck('ora')->all();
                        $ore_disponibile = array_diff($ore_disponibile, $ore_indisponibile);

                        if ((!in_array(\Carbon\Carbon::parse($value)->toTimeString(), $ore_disponibile))) {
                            $fail('Ora aleasă, ' . \Carbon\Carbon::parse($value)->isoFormat('HH:mm') . ', este indisponibilă');
                        }
                    },
                ]
            ])
        );

        $request->session()->put('programare', $programare);

        return redirect('/' . $serviciu . '/programari/adauga-programare-pasul-3');
    }

    /**
     * Show the step 3 Form for creating a new 'programare '.
     *
     * @return \Illuminate\Http\Response
     */
    public function adaugaProgramarePasul3(Request $request)
    {
        if(empty($request->session()->get('programare'))){
            return redirect('/programari/adauga-programare-noua');
        }elseif(empty($request->session()->get('programare')->ora)){
            return redirect('/programari/adauga-programare-pasul-2');
        }else{
            $programare = $request->session()->get('programare');
            return view('programari.guest_create.adauga_programare_pasul_3', compact('programare'));
        }
    }

    /**
     * Post Request to store step3 info in session
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postAdaugaProgramarePasul3(Request $request)
    {
        $request->validate([
            'nume' => 'required|max:500',
            'email' => 'required|max:500|email:rfc,dns',
            'cnp' => 'required|numeric|integer|digits:13',
            'gdpr' => 'required',
            'acte_necesare' => 'required'
        ]);

        $programare = $request->session()->get('programare');
        $programare->fill($request->except(['gdpr', 'acte_necesare']));

        $request->session()->put('programare', $programare);

        return redirect('/' . $serviciu . '/programari/adauga-programare-pasul-4');
    }

    /**
     * Show the step 4 Form for creating a new 'programare '.
     *
     * @return \Illuminate\Http\Response
     */
    public function adaugaProgramarePasul4(Request $request)
    {
        if(empty($request->session()->get('programare'))){
            return redirect('/programari/adauga-programare-noua');
        }else{
            $programare = $request->session()->get('programare');
            $programare->save();

            if (isset($programare->email)){
                \Mail::to($programare->email)
                    ->send(
                        new ProgramareEmail($programare)
                    );
            }

            $request->session()->forget('programare');

            return view('programari.guest_create.adauga_programare_pasul_4', compact('programare'));
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function afisare_saptamanal()
    {
        $search_data = \Request::get('search_data') ? \Carbon\Carbon::parse(\Request::get('search_data')) : \Carbon\Carbon::today();
        $data_de_cautat = \Carbon\Carbon::parse($search_data);

        $ore_de_program = ProgramareOraDeProgram::select('ziua_din_saptamana', 'ora')
            ->orderBy('ziua_din_saptamana')
            ->orderBy('ora')
            ->get();

        $programari_din_saptamana_cautata = Programare::
            whereDate('data', '>=', $data_de_cautat->startOfWeek())
            ->whereDate('data', '<=', $data_de_cautat->endOfWeek())
            ->orderBy('ora')
            ->get();

        return view('programari.diverse.afisare_saptamanal', compact('programari_din_saptamana_cautata', 'ore_de_program', 'search_data'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function afisare_zilnic()
    {
        $search_data = \Request::get('search_data') ? \Carbon\Carbon::parse(\Request::get('search_data')) : \Carbon\Carbon::today();

        $ore_de_program = ProgramareOraDeProgram::select('ziua_din_saptamana', 'ora')
            ->where('ziua_din_saptamana', $search_data->dayOfWeekIso)
            ->orderBy('ora')
            ->get();

        $programari = Programare::
            whereDate('data', '=', $search_data)
            ->orderBy('ora')
            ->get();

        return view('programari.diverse.afisare_zilnic', compact('programari', 'ore_de_program', 'search_data'));
    }

    public function pdfExportPeZi(Request $request, $serviciu = null, $data = null)
    {
        $data = \Carbon\Carbon::parse($data);

        $ore_de_program = ProgramareOraDeProgram::select('ziua_din_saptamana', 'ora')
            ->where('ziua_din_saptamana', $data->dayOfWeekIso)
            ->orderBy('ora')
            ->get();

        $programari = Programare::
            whereDate('data', '=', $data)
            ->orderBy('ora')
            ->get();

        if ($request->view_type === 'programari-html') {
            return view('programari.export.programari-pdf', compact('programari', 'ore_de_program', 'data'));
        } elseif ($request->view_type === 'programari-pdf') {
            $pdf = \PDF::loadView('programari.export.programari-pdf', compact('programari', 'ore_de_program', 'data'))
                ->setPaper('a4');
            return $pdf->download('Programari din data ' . $data->isoFormat('DD.MM.YYYY') . '.pdf');
        }
    }

}
