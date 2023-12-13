<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoricoAtendimento extends Model
{
    use HasFactory;

    protected $connection = 'production';

    public $timestamps = false;

    protected $table = 'historico_atendimentos';

    protected $with = ['cooperado'];

    protected $fillable = ['avaliacao', 'comentario'];

    public function cooperado()
    {
        return $this->belongsTo(Cooperado::class, 'cooperado_id', 'id');
    }
}
