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
    public function index()
    {
        $search_nume = \Request::get('search_nume');
        $search_data = \Request::get('search_data');

        $programari = Programare::
            when($search_nume, function ($query, $search_nume) {
                return $query->where('nume', 'like', '%' . $search_nume . '%');
            })
            ->when($search_data, function ($query, $search_data) {
                return $query->whereDate('data', '=', $search_data);
            })
            ->latest()
            ->simplePaginate(25);

        return view('programari.index', compact('programari', 'search_nume', 'search_data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('programari.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $programare = Programare::create($this->validateRequest($request));

        if (isset($programare->email)){
            \Mail::to($programare->email)
                ->send(
                    new ProgramareEmail($programare)
                );
        }

        return redirect('/programari')->with('status', 'Programarea pentru „' . ($programare->nume ?? '') . '” a fost adăugată cu succes!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Programare  $programare
     * @return \Illuminate\Http\Response
     */
    public function show(Programare $programare)
    {
        return view('programari.show', compact('programare'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Programare  $programare
     * @return \Illuminate\Http\Response
     */
    public function edit(Programare $programare)
    {
        return view('programari.edit', compact('programare'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Programare  $programare
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Programare $programare)
    {
        $programare->update($this->validateRequest($request));

        return redirect('/programari')->with('status', 'Programarea pentru „' . ($programare->nume ?? '') . '” a fost modificată cu succes!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Programare  $programare
     * @return \Illuminate\Http\Response
     */
    public function destroy(Programare $programare)
    {
        $programare->delete();

        return redirect('/programari')->with('status', 'Programarea pentru „' . ($programare->nume ?? '') . '” a fost ștearsă cu succes!');
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
                    ->where('ziua_din_saptamana', '=', \Carbon\Carbon::parse($request->data)->dayOfWeekIso)
                    ->orderBy('ora')
                    ->pluck('ora')
                    ->all();

                $ora_initiala = $request->ora_initiala;
                $ore_indisponibile = DB::table('programari')
                    ->select(DB::raw('DATE_FORMAT(ora, "%H:%i") as ora'))
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
    protected function validateRequest(Request $request)
    {
        return request()->validate(
            [
                'nume' => 'required|max:500',
                'email' => 'nullable|max:500|email:rfc,dns',
                'cnp' => 'nullable|numeric|integer|digits:13',
                'data' => 'required',
                'ora' => 'required'
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
    public function adaugaProgramareNoua(Request $request)
    {
        if(!empty($request->session()->get('programare'))){
            $programare = $request->session()->forget('programare');
        }

        return redirect('programari/adauga-programare-pasul-1');
    }

    /**
     * Show the step 1 Form for creating a new 'programare '.
     *
     * @return \Illuminate\Http\Response
     */
    public function adaugaProgramarePasul1(Request $request)
    {
        $programare = $request->session()->get('programare');
        $zile_nelucratoare = DB::table('programari_zile_nelucratoare')->where('data', '>', \Carbon\Carbon::today())->pluck('data')->all();


        $ore_disponibile = ProgramareOraDeProgram::all();
        $ore_indisponibile = Programare::where('data', '>=', \Carbon\Carbon::tomorrow()->toDateString())->get();

        // $ore_disponibile = array_diff($ore_disponibile, $ore_indisponibile);
        $data = \Carbon\Carbon::tomorrow();
        $zile_pline = array();
        while ($data->lessThan(\Carbon\Carbon::today()->addMonth(1)->endOfMonth())){

            $ore_disponibile_la_data = $ore_disponibile->where('ziua_din_saptamana', $data->dayOfWeekIso)->pluck('ora')->toArray();
            $ore_indisponibile_la_data = $ore_indisponibile->where('data', $data->toDateString())->pluck('ora')->toArray();
            // dd($data->toDateString(), $ore_disponibile, $ore_disponibile_la_data, $ore_indisponibile, $ore_indisponibile_la_data);
            $ore_disponibile_ramase = array_diff($ore_disponibile_la_data, $ore_indisponibile_la_data);
            // dd(count($ore_disponibile));
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


            // dd($zile_pline);
        }
            // dd($zile_pline);
        // dd($zile_nelucratoare);

        return view('programari.guest_create.adauga_programare_pasul_1', compact('programare', 'zile_nelucratoare', 'zile_pline'));
    }

    /**
     * Post Request to store step1 info in session
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postAdaugaProgramarePasul1(Request $request)
    {
        $zile_nelucratoare = DB::table('programari_zile_nelucratoare')->where('data', '>', \Carbon\Carbon::today())->pluck('data')->all();

        if(empty($request->session()->get('programare'))){
            $programare = new Programare();
        }else{
            $programare = $request->session()->get('programare');
        }

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

        return redirect('programari/adauga-programare-pasul-2');
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

        return redirect('/programari/adauga-programare-pasul-3');
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

        return redirect('/programari/adauga-programare-pasul-4');
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
        // return view('programari.diverse.afisare_saptamanala', compact('search_data'));
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

    public function pdfExportPeZi(Request $request, $data = null)
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
