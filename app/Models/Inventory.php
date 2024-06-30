<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;
    protected $table = "inventory";
    protected $fillable = [
        'explorer_id',
        'item_id'
    ];

    public function explorador()
    {
        return $this->belongsTo(Explorer::class);
    }

    public function item()
    {
        return $this->belongsTo(Items::class);
    }

    public function inventario()
    {
        return $this->hasMany(Explorer::class);
    }
}
