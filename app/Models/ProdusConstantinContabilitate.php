<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdusConstantinContabilitate extends Model
{
    protected $table = 'produse_constantin_contabilitate';
    protected $guarded = [];

    public function path()
    {
        return "/produse-constantin-contabilitate/{$this->id}";
    }

    public function produs_site()
    {
        return $this->hasOne(ProdusConstantinSite::class, 'sku', 'cod');
    }
}
