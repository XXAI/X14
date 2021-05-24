<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReporteRespuesta extends Model
{
    use SoftDeletes;
    protected $table = 'reportes_respuestas';
    protected $fillable = ['reporte_id','checklist_reactivo_id','tiene_informacion','no_aplica','comentarios'];
    
}
