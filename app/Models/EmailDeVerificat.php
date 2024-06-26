<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailDeVerificat extends Model
{
    use HasFactory;

    protected $table = 'emailuri_de_verificat';
    protected $guarded = [];

    // public function path()
    // {
    //     return "programari/{$this->id}";
    // }
}
