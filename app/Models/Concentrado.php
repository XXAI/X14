<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Concentrado extends Model
{
    use SoftDeletes;
    protected $table = 'concentrados';
    protected $fillable = ['proyecto_id','direccion_id','enlace','ultimo_reporte'];

    public function proyecto(){
        return $this->belongsTo('App\Models\Proyecto','proyecto_id');
    }

    public function reportes(){
        return $this->hasMany('App\Models\Reporte','concentrado_id');
    }
}

