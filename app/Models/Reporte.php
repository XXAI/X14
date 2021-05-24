<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reporte extends Model
{
    use SoftDeletes;
    protected $table = 'reportes';
    protected $fillable = ['concentrado_id','auditoria_id','checklist_id','enlace','fecha'];

    public function respuestas(){
        return $this->hasMany('App\Models\ReporteRespuesta','reporte_id');
    }

    public function checklist(){
        return $this->belongsTo('App\Models\Checklist','checklist_id');
    }

    public function auditoria(){
        return $this->belongsTo('App\Models\Auditoria','auditoria_id');
    }
}

