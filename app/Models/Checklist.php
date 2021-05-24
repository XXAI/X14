<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Checklist extends Model
{
    use SoftDeletes;
    protected $table = 'checklists';
    protected $fillable = ['descripcion','activo'];

    public function titulos(){
        return $this->hasMany('App\Models\ChecklistTitulo','checklist_id');
    }
}
