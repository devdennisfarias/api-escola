<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use Illuminate\Http\Response;
use App\Http\Requests\AlunoRequest;
use App\Http\Resources\AlunoCollection;
use App\Http\Resources\AlunoResource;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use SimpleXMLElement;

class AlunoController extends Controller
{
    /**
     * @OA\Get(
     *      path="/alunos",
     *      sumary="Mostra os alunos cadastrados",
     *      @OA\Response(response=200, description="ok")
     * )
     * 
     * Display a listing of the resource.
     *
     * @return AlunoCollection
     */
    public function index(Request $request): AlunoCollection
    {
        if ($request->query('relacoes') === 'turma')
        {
            $alunos = Aluno::with('turma')->paginate(15);
        } else {
            $alunos = Aluno::paginate(15);
        }
        return new AlunoCollection($alunos);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  AlunoRequest  $request
     * @return Response
     */
    public function store(AlunoRequest $request): Response
    {
        return response(Aluno::create($request->all()), 201);
    }

    /**
     * @OA\Get(
     *      path="/alunos/{id}",
     *      sumary="Mostra os detalhes do aluno",
     *      @OA\Response(response=200, description="ok")
     * )
     * 
     * Display the specified resource.
     *
     * @param  Aluno $aluno
     * @return AlunoResource
     */
    public function show(Aluno $aluno): AlunoResource
    {
        if(request()->header("Accept") === "application/xml")
        {
            return $this->pegarAlunoXMLResponse($aluno);
        }

        if(request()->wantsJson())
        {
            return new AlunoResource($aluno);
        }

        return response('Formato de Dados Desconhecidos nesta aplicação');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  AlunoRequest  $request
     * @param  Aluno $aluno
     * @return Aluno
     */
    public function update(AlunoRequest $request, Aluno $aluno): Aluno
    {
        $aluno->update($request->all());

        return $aluno;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Aluno $aluno
     * @return array
     */
    public function destroy(Aluno $aluno)
    {
        $aluno->delete();

        return [];
    }

    /**
     * Retorna uma response com xml do aluno
     * 
     * @param Aluno $aluno
     * @return Response
     */
    private function pegarAlunoXMLResponse(Aluno $aluno): Response
    {
        $aluno = $aluno->toArray();

        $xml = new SimpleXMLElement('<aluno/>');

        array_walk_recursive($aluno, function($valor, $chave) use ($xml)
        {
            $xml->addChild($chave, $valor);
        });

        return response($xml->asXML())
        ->header('Content-Type', 'application/xml');
    }
}
