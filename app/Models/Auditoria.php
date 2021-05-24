<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Auditoria extends Model
{
    use SoftDeletes;
    protected $table = 'auditorias';
    protected $fillable = ['ejercicio','descripcion'];
}
