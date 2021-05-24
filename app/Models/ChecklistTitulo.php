<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChecklistTitulo extends Model
{
    use SoftDeletes;
    protected $table = 'checklists_titulos';
    protected $fillable = ['checklist_id','seccion','titulo','subtitulo'];

    public function reactivos(){
        return $this->hasMany('App\Models\ChecklistReactivo','checklist_titulo_id');
    }
}
