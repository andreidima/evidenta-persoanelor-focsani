<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\ZiNelucratoare;

use Carbon\Carbon;

class ZiNelucratoareController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $serviciu = null)
    {
        $request->session()->forget('zi_nelucratoare_return_url');

        $zile_nelucratoare = ZiNelucratoare::select('id', 'data')
            ->where(function($query) use ($serviciu) {
                switch ($serviciu) {
                    case 'toate-sediile':
                    case 'evidenta-persoanelor':
                        $query->where('serviciu', 1);
                        break;
                    case 'transcrieri-certificate':
                        $query->where('serviciu', 2);
                        break;
                    case 'casatorii': // serviciul a fost scos, transformandu-se in 4,5,6
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
                        $query->where('serviciu', -1);
                        break;
                }
            })
        ->orderBy('data')->simplePaginate(200);

        return view('zileNelucratoare.index', compact('zile_nelucratoare', 'serviciu'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $serviciu = null)
    {
        $request->session()->get('zi_nelucratoare_return_url') ?? $request->session()->put('zi_nelucratoare_return_url', url()->previous());

        $zi_nelucratoare = new ZiNelucratoare;

        return view('zileNelucratoare.create', compact('zi_nelucratoare', 'serviciu'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $serviciu = null)
    {
        if ($serviciu != 'toate-sediile'){
            $zi_nelucratoare = ZiNelucratoare::make($this->validateRequest($request));
            switch($serviciu) {
                case('evidenta-persoanelor'):
                    $zi_nelucratoare->serviciu = 1;
                    break;
                case('transcrieri-certificate'):
                    $zi_nelucratoare->serviciu = 2;
                    break;
                case('casatorii-oficieri-sediu'):
                    $zi_nelucratoare->serviciu = 4;
                    break;
                case('casatorii-oficieri-foisor'):
                    $zi_nelucratoare->serviciu = 5;
                    break;
                case('casatorii-oficieri-teatru'):
                    $zi_nelucratoare->serviciu = 6;
                    break;
            }
            $zi_nelucratoare->save();
        } else {
            $servicii = [1,2,4,5,6];
            foreach ($servicii as $serviciu) {
                $zi_nelucratoare = ZiNelucratoare::make($this->validateRequest($request));
                $zi_nelucratoare->serviciu = $serviciu;
                $zi_nelucratoare->save();
            }
        }

        return redirect($request->session()->get('zi_nelucratoare_return_url') ?? ('/' .  $serviciu . '/zile-nelucratoare'))
            ->with('status', 'Ziua nelucrătoare „' . ($zi_nelucratoare->data ? Carbon::parse($zi_nelucratoare->data)->isoFormat('DD.MM.YYYY') : '') . '” a fost adăugată cu succes!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ZiNelucratoare  $zi_nelucratoare
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $serviciu = null, ZiNelucratoare $zi_nelucratoare)
    {
        $request->session()->get('zi_nelucratoare_return_url') ?? $request->session()->put('zi_nelucratoare_return_url', url()->previous());

        return view('zileNelucratoare.show', compact('zi_nelucratoare'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App ZiNelucratoare  $zi_nelucratoare
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $serviciu = null, ZiNelucratoare $zi_nelucratoare)
    {
        $request->session()->get('zi_nelucratoare_return_url') ?? $request->session()->put('zi_nelucratoare_return_url', url()->previous());

        return view('zileNelucratoare.edit', compact('serviciu', 'zi_nelucratoare'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App ZiNelucratoare  $zi_nelucratoare
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $serviciu = null, ZiNelucratoare $zi_nelucratoare)
    {
        $zi_nelucratoare->update($this->validateRequest($request));

        return redirect($request->session()->get('zi_nelucratoare_return_url') ?? ('/zile-nelucratoare'))
            ->with('status', 'Ziua nelucrătoare „' . ($zi_nelucratoare->data ? Carbon::parse($zi_nelucratoare->data)->isoFormat('DD.MM.YYYY') : '') . '” a fost modificată cu succes!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App ZiNelucratoare  $zi_nelucratoare
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $serviciu = null, ZiNelucratoare $zi_nelucratoare)
    {
        if ($serviciu != 'toate-sediile'){
            $zi_nelucratoare->delete();
        } else {
            ZiNelucratoare::where('data', $zi_nelucratoare->data)->delete();
        }

        return back()->with('status', 'Ziua nelucrătoare „' . ($zi_nelucratoare->data ? Carbon::parse($zi_nelucratoare->data)->isoFormat('DD.MM.YYYY') : '') . '” a fost ștearsă cu succes!');
    }

    /**
     * Validate the request attributes.
     *
     * @return array
     */
    protected function validateRequest(Request $request)
    {
        return $request->validate(
            [
                'data' => 'required',
            ],
            [

            ]
        );
    }
}
