<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramareOraDeProgram extends Model
{
    use HasFactory;

    protected $table = 'programari_ore_de_program';
    protected $guarded = [];

    public function path()
    {
        return "/programari/{$this->id}";
    }

}
