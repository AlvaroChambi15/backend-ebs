<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class Horario extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function reserva()
    {
        return $this->belongsTo(Reserve::class, 'reserve_id');
    }

    /* public function cita()
    {
        return $this->belongsTo(Cita::class);
    } */

    // SCOPES

    public function scopeState($query, $filterState)
    {
        if ($filterState) {
            return $query->where('estado', 'LIKE', "%$filterState%");
        }
    }

    public function scopeFullname($query, $filterFullName)
    {
        if ($filterFullName) {
            return $query->where('nombres', 'LIKE', "%$filterFullName%");
            // return $query->whereRelation('reserva', 'nombres', 'LIKE', "%$filterFullName%");
        }
    }
}
