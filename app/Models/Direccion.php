<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Direccion extends Model
{
    use SoftDeletes;
    protected $table = 'direcciones';
    protected $fillable = ['clave','descripcion'];

    public function proyectos(){
        return $this->hasMany('App\Models\Proyecto','direccion_id');
    }
}
