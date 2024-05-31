<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramareIstoric extends Model
{
    use HasFactory;

    protected $table = 'programari_istoric';
    protected $guarded = [];

    // public function path()
    // {
    //     return "programari/{$this->id}";
    // }
}
