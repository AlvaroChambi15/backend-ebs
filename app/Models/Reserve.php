<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Reserve extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function horarios()
    {
        return $this->hasMany(Horario::class);
    }

    /* public function horarios()
    {
        return $this->hasMany(Horario::class);
    } */

    public function scopeState($query, $filterState)
    {
        if ($filterState) {
            // return $query->where('estado', 'LIKE', "%$filterState%");
            return $query->whereRelation('horarios', 'estado', 'LIKE', "%$filterState%");
        }
    }

    public function scopeFullname($query, $filterSearch)
    {
        if ($filterSearch) {
            // return $query->where('nombres', 'LIKE', "%$filterSearch%");
            return $query->where(DB::raw('CONCAT(nombres, " ", apellidos)'), 'LIKE', "%$filterSearch%");
            // return $query->whereRelation('reserva', 'nombres', 'LIKE', "%$filterSearch%");
        }
    }

    public function scopeEspecialidad($query, $filterSearch)
    {
        if ($filterSearch) {
            // return $query->where('nombres', 'LIKE', "%$filterSearch%");
            return $query->where(DB::raw('CONCAT(nombres, " ", apellidos)'), 'LIKE', "%$filterSearch%");
            // return $query->whereRelation('reserva', 'nombres', 'LIKE', "%$filterSearch%");
        }
    }
}