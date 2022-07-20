<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Aluno extends Model
{
    use HasFactory;

    /**
     * Define os campos que passarão para a inserção no banco quando os dados vierem em massa
     * 
     * @var array
     */
    protected $fillable = [
        'nome',
        'nascimento',
        'genero',
        'turma_id',
    ];
    
    /**
     * Conversao dinamica de valores depois de processados (serialização)
     *
     * @var array
     */
/*     protected $casts = [
        'nascimento' => 'd/m/Y'
    ]; */

    /** 
     * Define os campos escondidos no momento da serialização
     */
    //protected $hidden = [ 'created_at','updated_at'];
    
    /**
     * Define os campos a serem exibidos no momento da serialização
     */
/*     protected $visible = ['id','nome', 'genero', 'turma_id']; */

    /**
     * Define os acessores enviados na serialização
     */
/*     protected $appends = ['aceito']; */



    public function turma(): BelongsTo
    {
        return $this->belongsTo(Turma::class);
    }

    /**
     * Cria um atributo virtual, chamado de "aceito"
     * Seguindo a convenção, a palavra que esta entre get e attribute será o nome do atributo, mas tem que ser declarado como apendice com o mesmo nome;
     * @return string
     */
/*     public function getAceitoAttribute(): string
    {
        return $this->attributes['nascimento'] > '2001-01-01' ? 'aceito' : 'Não Aceito';
    } */
}
