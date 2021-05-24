<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChecklistReactivo extends Model
{
    use SoftDeletes;
    protected $table = 'checklists_reactivos';
    protected $fillable = ['checklist_titulo_id','orden','descripcion'];
}
