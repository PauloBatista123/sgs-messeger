<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Atendimento extends Model
{
    use HasFactory;

    protected $connection = 'production';

    public $timestamps = false;

    protected $table = 'atendimentos';

    protected $with = ['cooperado'];

    protected $fillable = ['avaliacao', 'comentario'];

    public function cooperado()
    {
        return $this->belongsTo(Cooperado::class, 'cooperado_id', 'id');
    }
}
