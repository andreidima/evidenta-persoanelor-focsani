<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZiNelucratoare extends Model
{
    use HasFactory;

    protected $table = 'programari_zile_nelucratoare';
    protected $guarded = [];

    public function path($serviciu = null)
    {
        return "zile-nelucratoare/{$this->id}";
    }
}
