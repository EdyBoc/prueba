<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    protected $fillable = [
        'primer_nombre',
        'segundo_nombre',
        'primer_apellido',
        'segundo_apellido',
        'apellido_casada',
        'tipo_documento',
        'cui',
        'pasaporte',
        'fecha_nacimiento',
        'telefono',
        'email',
        'sexo',
        'estado_civil',
        'direccion',
        'documento',
    ];

    protected $guarded = [];

}
