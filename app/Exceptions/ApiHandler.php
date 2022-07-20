<?php
namespace App\Exceptions;

use Illuminate\Auth\Events\Validated;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Throwable;

trait ApiHandler{

     /**
     * Tratamento de Erros Personalizados
     *
     * @param  Throwable  $e
     * @return Response
     */
    public function tratarErros(Throwable $e): Response
    {
        if($e instanceof ModelNotFoundException){
            return $this->modelNotFoundException($e);
        }

        if ($e instanceof ValidationException){
            return $this->validationException($e);
        }      
        return false; 
    }

    /**
     * Retorna erro ao não encontrar um registro
     *
     * @return Response
     */
    public function modelNotFoundException(): Response
    {
        return  $this->respostaPadrao(
            "registro-nao-encontrado",
            "O sistema não encontrou o registro solicitado",
            404
        );
    }

    /**
     * Retorna um erro quando os dados não são válidos
     *
     * @param  ValidationException  $e
     * @return Response
     */
    public function validationException(ValidationException $e): Response
    {
        return  $this->respostaPadrao(
            "erro-validacao",
            "Os dados enviados são invalidos",
            400,
            $e->errors()
        );
    }


     /**
     * Retorna uma resposta padrão para os erros da API
     *
     * @param  string $code
     * @param  string $mensagem
     * @param  string $status
     * @param  array $erros
     * @return Response
     */
    public function respostaPadrao(string $code, string $mensagem, int $status, array $erros = null): Response
    {
        $dadosResposta = [
            'code' => $code,
            'message' =>  $mensagem,
            'status' => $status
        ];

        if($erros){
            $dadosResposta = $dadosResposta + ['erros' => $erros];
        }

        return response(
            $dadosResposta,
            $status
        );
    }

}