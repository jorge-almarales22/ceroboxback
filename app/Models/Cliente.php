<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $casts = [
    'fecha_inicio' => 'datetime:Y-m-d',
    'fecha_fin' => 'datetime:Y-m-d',
	];

	//esto es para guardar en BD con el mismo formato DATE de SQL y no genere error
  
}
