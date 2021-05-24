<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Proyecto extends Model
{
    use SoftDeletes;
    protected $table = 'proyectos';
    protected $fillable = ['direccion_id','ejercicio','clave','descripcion'];

    public function direccion(){
        return $this->belongsTo('App\Models\Direccion','direccion_id');
    }
}
