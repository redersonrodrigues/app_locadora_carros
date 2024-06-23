<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use Illuminate\Http\Request;

class MarcaController extends Controller
{

public function __construct(Marca $marca) {
    $this->marca = $marca;
}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //$marcas = Marca::all();
        $marcas = $this->marca->all();
        return $marcas;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // $marca = Marca::create($request->all());
        $marca = $this->marca->create($request->all());
        return $marca;
    }

    /**
     * @param Integer
     * Display the specified resource.
     * @return \Illuminate\Http\Response
     */
    public function show( $id )
    {
        $marca = $this->marca->find($id);
        if ($marca === null) {
            return ['erro' => 'Recurso pesquisado não existe'];
        }
        return $marca;
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
            return ['erro' => 'Impossível realizar a atualização, o recurso pesquisado não existe'];
        }
        $marca->update($request->all());

        return $marca;
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
            return ['erro' => 'Impossível deletar o registro, o recurso pesquisado não existe'];
        }
        $marca->delete();
        return ['msg' => 'A marca foi removida com sucesso!'];
    }
}
