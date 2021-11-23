<?php

namespace App\Http\Controllers;

use App\Models\ProgramareOnline;

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
        return view('programari_online.guest_create.adauga_programare_online_pasul_1', compact('programare_online'));
    }

    /**
     * Post Request to store step1 info in session
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postAdaugaProgramareOnlinePasul1(Request $request)
    {
        if(empty($request->session()->get('programare_online'))){
            $programare_online = new ProgramareOnline();
        }else{
            $programare_online = $request->session()->get('programare_online');
        }

        $programare_online->fill(
            $request->validate([
                'data' => 'required|date'
            ])
        );

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
            return view('programari_online.guest_create.adauga_programare_online_pasul_2', compact('programare_online'));
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
                'ora' => 'required'
            ])
        );

        $request->session()->put('programare_online', $programare_online);

        return redirect('programari-online/adauga-programare-online-pasul-3');
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
        }else{
            $programare_online = $request->session()->get('programare_online');
            return view('programari_online.guest_create.adauga_programare_online_pasul_3', compact('programare_online'));
        }
    }

}
