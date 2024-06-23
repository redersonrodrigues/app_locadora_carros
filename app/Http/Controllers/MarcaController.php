<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation;

class MarcaController extends Controller
{

    public function __construct(Marca $marca)
    {
        $this->marca = $marca;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //$marcas = Marca::all();
        $marcas = $this->marca->all();
        return response()->json($marcas, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // $marca = Marca::create($request->all());
        $regras = [
            'nome'      => 'required|unique:marcas',
            'imagem'    => 'required'
        ];
        $feedback = [
            'required' => 'O campo :attribute é obrigatório',
            'nome.unique' => 'Já existe uma marca com este nome',
        ];
        request()->validate($regras, $feedback);

        $marca = $this->marca->create($request->all());
        return response()->json($marca, 201);
    }

    /**
     * @param Integer
     * Display the specified resource.
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $marca = $this->marca->find($id);
        if ($marca === null) {
            return response()->json(['erro' => 'Recurso pesquisado não existe'], 404);
        }
        return response()->json($marca, 200);
    }

    /**
     * Update the specified resource in storage.
     * @param \Illuminate\Http\Request $request
     * @param Integer
     * @return \Illuminate\Http\Response
     * 
     */
    public function update(Request $request, $id)
    {

        $marca = $this->marca->find($id);
        if ($marca === null) {
            return response()->json(['erro' => 'Impossível realizar a atualização, o recurso pesquisado não existe'], 404);
        }
        $marca->update($request->all());

        return response()->json($marca, 200);
    }

    /**
     * @param Integer
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // print_r($marca->getAttributes());
        $marca = $this->marca->find($id);
        if ($marca === null) {
            return response()->json(['erro' => 'Impossível deletar o registro, o recurso pesquisado não existe'], 404);
        }
        $marca->delete();
        return response()->json(['msg' => 'A marca foi removida com sucesso!'], 200);
    }
}
