<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CordinadorUsuario extends Model
{
    use HasFactory;
    protected $table = 'cordinador_usuarios';
    protected $primaryKey = 'id';
    protected $fillable = [
        'cordinador_id',
        'user_id',
        'area_id',
    ];
    // Relaciones
    public function cordinador()
    {
        return $this->belongsTo(User::class, 'cordinador_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id');
    }
}
