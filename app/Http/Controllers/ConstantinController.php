<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\ProdusConstantinContabilitate;
use App\Models\ProdusConstantinSite;
use Illuminate\Support\Facades\DB;

class ConstantinController extends Controller
{
    public function index()
    {
        // $produse_constantin_contabilitate_grupate_dupa_cod = ProdusConstantinContabilitate::select('cod', 'nume', 'locatie', 'cantitate')
        //     ->get()->groupBy('cod');

        // $produse_constantin_site = ProdusConstantinSite::select('*')
        //     ->get()->groupBy('sku');

        // $produse_lipsa = 0;

        // foreach ($produse_constantin_contabilitate_grupate_dupa_cod as $produse_grupa){
        //     $cantitate = 0;
        //     $locatie = '';

        //     foreach ($produse_grupa as $produs){
        //             $cantitate += $produs->cantitate;
        //             ($locatie == '') ? $locatie .= $produs->locatie : $locatie .= (', ' . $produs->locatie);
        //     }
        //     echo $produs->cod . ' ' . $cantitate . ' -> ' . ($produs->produs_site->stock ?? 'lipsa ' . ($produse_lipsa ++) );
        //     echo '<br>';
        // }
            // echo '<br><br><br><br><br>';

        // foreach ($produse_constantin_site as $produse_grupa){
        //     foreach ($produse_grupa as $produs){
        //         echo ($produs->sku ?? '') . ' ' . $produs->stock . ' -> ' . ($produs->produs_contabilitate->cantitate ?? 'lipsa ' . ($produse_lipsa ++) );
        //         echo '<br>';
        //     }
        // }


        $duplicates = DB::table('table 2')
            ->select('COL9', DB::raw('COUNT(*) as `count`'))
            ->groupBy('COL9')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        foreach($duplicates as $duplicat){
            echo $duplicat->COL9 . '<br>';

        }
        dd($duplicates);


        // dd($produse_constantin_site);
        // return view('constantin.index', compact('produse_constantin_contabilitate_grupate_dupa_cod', 'produse_constantin_site'));
    }

}
