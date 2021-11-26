<?php

namespace App\Http\Controllers;

use App\Models\ProgramareOnline;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class ProgramareOnlineController extends Controller
{

    //
    // Functii pentru Multi Page Form pentru Clienti
    //
    /**
     * Show the step 1 Form for creating a new 'programare online'.
     *
     * @return \Illuminate\Http\Response
     */
    public function adaugaProgramareOnlineNoua(Request $request)
    {
        if(!empty($request->session()->get('programare_online'))){
            $programare_online = $request->session()->forget('programare_online');
        }

        return redirect('programari-online/adauga-programare-online-pasul-1');
    }

    /**
     * Show the step 1 Form for creating a new 'programare online'.
     *
     * @return \Illuminate\Http\Response
     */
    public function adaugaProgramareOnlinePasul1(Request $request)
    {
        $programare_online = $request->session()->get('programare_online');
        $zile_nelucratoare = DB::table('programari_online_zile_nelucratoare')->where('data', '>', \Carbon\Carbon::today())->pluck('data')->all();
        return view('programari_online.guest_create.adauga_programare_online_pasul_1', compact('programare_online', 'zile_nelucratoare'));
    }

    /**
     * Post Request to store step1 info in session
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postAdaugaProgramareOnlinePasul1(Request $request)
    {
        $zile_nelucratoare = DB::table('programari_online_zile_nelucratoare')->where('data', '>', \Carbon\Carbon::today())->pluck('data')->all();

        if(empty($request->session()->get('programare_online'))){
            $programare_online = new ProgramareOnline();
        }else{
            $programare_online = $request->session()->get('programare_online');
        }

        $programare_online->fill(
            $request->validate([
                'data' => [
                    'required',
                    'date',
                    'after:today',
                    function ($attribute, $value, $fail) use ($request) {
                        $zile_nelucratoare = DB::table('programari_online_zile_nelucratoare')->where('data', '>', \Carbon\Carbon::today())->pluck('data')->all();
                        // dd($value);
                        if (\Carbon\Carbon::parse($request->data)->isWeekend() || (in_array($value, $zile_nelucratoare))) {
                            $fail('Data aleasă, ' . $value . ', nu este o zi lucrătoare');
                        }
                    },
                ]
            ])
        );
        $programare_online->offsetUnset('ora');

        $request->session()->put('programare_online', $programare_online);

        return redirect('programari-online/adauga-programare-online-pasul-2');
    }

    /**
     * Show the step 2 Form for creating a new 'programare online'.
     *
     * @return \Illuminate\Http\Response
     */
    public function adaugaProgramareOnlinePasul2(Request $request)
    {
        if(empty($request->session()->get('programare_online'))){
            return redirect('/programari-online/adauga-programare-online-noua');
        }else{
            $programare_online = $request->session()->get('programare_online');

            $prima_ora_din_program = \Carbon\Carbon::parse(
                DB::table('programari_online_ore_de_program')->where('ziua_din_saptamana', '=', \Carbon\Carbon::parse($programare_online->data)->dayOfWeekIso)->orderBy('ora')->pluck('ora')->first()
            );
            $ora_inceput = \Carbon\Carbon::today();
            $ora_inceput->hour = $prima_ora_din_program->hour;
            $ora_inceput->minute = $prima_ora_din_program->minute;

            $ultima_ora_din_program = \Carbon\Carbon::parse(
                DB::table('programari_online_ore_de_program')->where('ziua_din_saptamana', '=', \Carbon\Carbon::parse($programare_online->data)->dayOfWeekIso)->orderBy('ora', 'desc')->pluck('ora')->first()
            );
            $ora_sfarsit = \Carbon\Carbon::today();
            $ora_sfarsit->hour = $ultima_ora_din_program->hour;
            $ora_sfarsit->minute = $ultima_ora_din_program->minute;
            $ora_sfarsit->addMinutes(15);

// dd($prima_ora_din_program, $ora_inceput, $ora_sfarsit);

            $ore_disponibile = DB::table('programari_online_ore_de_program')->where('ziua_din_saptamana', '=', \Carbon\Carbon::parse($programare_online->data)->dayOfWeekIso)->orderBy('ora')->pluck('ora')->all();
            $ore_indisponibile = DB::table('programari_online')->where('data', '=', $programare_online->data)->pluck('ora')->all();
            $ore_disponibile = array_diff($ore_disponibile, $ore_indisponibile);

            return view('programari_online.guest_create.adauga_programare_online_pasul_2', compact('programare_online', 'ora_inceput', 'ora_sfarsit', 'ore_disponibile'));
        }
    }

    /**
     * Post Request to store step2 info in session
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postAdaugaProgramareOnlinePasul2(Request $request)
    {
        $programare_online = $request->session()->get('programare_online');

        $programare_online->fill(
            $request->validate([
                'ora' => [
                    'required',
                    function ($attribute, $value, $fail) use ($request, $programare_online) {
                        $ore_disponibile = DB::table('programari_online_ore_de_program')->where('ziua_din_saptamana', '=', \Carbon\Carbon::parse($programare_online->data)->dayOfWeekIso)->orderBy('ora')->pluck('ora')->all();
                        $ore_indisponibile = DB::table('programari_online')->where('data', '=', $programare_online->data)->pluck('ora')->all();
                        $ore_disponibile = array_diff($ore_disponibile, $ore_indisponibile);

                        if ((!in_array(\Carbon\Carbon::parse($value)->toTimeString(), $ore_disponibile))) {
                            $fail('Ora aleasă, ' . \Carbon\Carbon::parse($value)->isoFormat('HH:mm') . ', este indisponibilă');
                        }
                    },
                ]
            ])
        );

        $request->session()->put('programare_online', $programare_online);

        return redirect('/programari-online/adauga-programare-online-pasul-3');
    }

    /**
     * Show the step 3 Form for creating a new 'programare online'.
     *
     * @return \Illuminate\Http\Response
     */
    public function adaugaProgramareOnlinePasul3(Request $request)
    {
        if(empty($request->session()->get('programare_online'))){
            return redirect('/programari-online/adauga-programare-online-noua');
        }elseif(empty($request->session()->get('programare_online')->ora)){
            return redirect('/programari-online/adauga-programare-online-pasul-2');
        }else{
            $programare_online = $request->session()->get('programare_online');
            return view('programari_online.guest_create.adauga_programare_online_pasul_3', compact('programare_online'));
        }
    }

    /**
     * Post Request to store step3 info in session
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postAdaugaProgramareOnlinePasul3(Request $request)
    {
        $request->validate([
            'nume' => 'required|max:500',
            'email' => 'required|max:500|email:rfc,dns',
            'cnp' => 'required|numeric|integer|digits:13',
            'gdpr' => 'required',
            'acte_necesare' => 'required'
        ]);

        $programare_online = $request->session()->get('programare_online');
        $programare_online->fill($request->except(['gdpr', 'acte_necesare']));

        $request->session()->put('programare_online', $programare_online);

        return redirect('/programari-online/adauga-programare-online-pasul-4');
    }

    /**
     * Show the step 4 Form for creating a new 'programare online'.
     *
     * @return \Illuminate\Http\Response
     */
    public function adaugaProgramareOnlinePasul4(Request $request)
    {
        if(empty($request->session()->get('programare_online'))){
            return redirect('/programari-online/adauga-programare-online-noua');
        }else{
            $programare_online = $request->session()->get('programare_online');
            $programare_online->save();

            $request->session()->forget('programare_online');

            return view('programari_online.guest_create.adauga_programare_online_pasul_4', compact('programare_online'));
        }
    }

}
