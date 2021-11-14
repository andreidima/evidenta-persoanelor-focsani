<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdusConstantinSite extends Model
{
    // protected $table = 'produse_constantin_site';
    protected $table = 'table 2';
    protected $guarded = [];

    public function path()
    {
        return "/produse-constantin-site/{$this->id}";
    }

    public function produs_contabilitate()
    {
        return $this->hasOne(ProdusConstantinContabilitate::class, 'cod', 'sku');
    }
}
