<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\ProgramareIstoric;
use App\Models\EmailDeVerificat;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AxiosController extends Controller
{
    public function trimitereCodValidareEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => [
                'required', 'max:255', 'email:rfc,dns',
                function ($attribute, $value, $fail) use ($request) {

                        if (!str_contains($value, 'gmail.com')){ // Just gmail.com
                            $fail('Este necesar un email din domeniul Google, de forma „user@gmail.com”');
                        } else {
                            // Just 1 email adress ever
                            $programariIstoric = ProgramareIstoric::where('email', $value)->get();
                            if ($programariIstoric->count() > 0){
                                $fail('De pe această adresă de email au mai fost făcute programări. Nu se pot face mai mult de o programare de pe aceași adresă de email.');
                            }
                        }

                    }
            ]
        ]);
        if ($validator->fails()) {
            // The email is saved in table „emailuri_de_verificat” just to see peoples attempts
            EmailDeVerificat::make(['email' => $request->email])->save();

            $raspuns = "<span class='text-danger' style='font-size:100%'>";
            foreach ($validator->errors()->all() as $error) {
                $raspuns .= $error;
                $raspuns .= '<br>';
            }
            $raspuns .= "</span>";
            return response()->json([
                'raspuns' => $raspuns,
            ]);
        }

        $emailDeVerificat = EmailDeVerificat::make(['email' => $request->email, 'cod_validare' => rand(10000, 99999)]);
        $emailDeVerificat->save();

        \Mail::to($request->email)->send(new \App\Mail\TrimitereCodValidareEmail($emailDeVerificat));

        $raspuns = "<span class='text-success' style='font-size:100%'>Un cod valabil pentru 15 minute a fost trimis prin email.</span>";

        return response()->json([
            'raspuns' => $raspuns,
        ]);
    }
}
