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
        // validação
        $request->validate($this->marca->rules(), $this->marca->feedback());

        // dd($request->nome);
        // dd($request->get('nome'));
        // dd($request->input('nome'));

        // dd($request->imagem);
        // dd($request->file('imagem'));
        
        $image = $request->file('imagem');
        // store('<path>','<disco>') // disco pode ser: local (storage/app/<nome do path>) ou public (storage/app/public/<nome>)
        
        $imagem_urn = $image->store('imagens','public');
        //dd($imagem_urn);

        // efetua o cadastro
        $marca = $this->marca->create([
            'nome' => $request->nome,
            'imagem' => $imagem_urn
        ]);

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

        // metodo controle patch/put
        if ($request->method() === 'PATCH') {
            $regrasDinamicas = array();

            // percorrendo todas as regras definidas no Model

            foreach ($marca->rules() as $input => $regra) {
                // coletar apenas as regras aplicáveis aos parâmetros parciais da requisição PATCH
                if (array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] =  $regra;
                }
            }
           // dd($regrasDinamicas);
        // validação
        request()->validate($regrasDinamicas, $this->marca->feedback()); 

        }else {
        
         // validação
        request()->validate($this->marca->rules(), $this->marca->feedback()); 

       
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
