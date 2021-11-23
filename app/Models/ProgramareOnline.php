<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramareOnline extends Model
{
    use HasFactory;

    protected $table = 'programari_online';
    protected $guarded = [];

    public function path()
    {
        return "/programari-online/{$this->id}";
    }
}
