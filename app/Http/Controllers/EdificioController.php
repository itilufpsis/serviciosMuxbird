<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Edificio;

class EdificioController extends Controller
{
    function __construct(){
    }
    /**
     * Metodo listar edificios
     */
    public function listar(){
        $edificios=Edificio::all();
        return response()->json([
        "success"=>$edificios
        ],200);
    } 
    
    public function registrar(Request $request){
        $nombre=$request->nombre;
        if($nombre){
            $cant=Edificio::where('nombre','=',$nombre)->count();
            if($cant==0){
                Edificio::insert(array('nombre'=>$nombre));
                return response()->json([
                    "success"=>'exito'
                ],200);
            }
            return response()->json([
                "err"=>'Edificio existente'
            ],200);
        }
        return response()->json([
            "err"=>'No se puede registrar edificio'
        ],200);
    }

    public function modificar(Request $request){
        $nombre=$request->nombre;
        $id=$request->id;
        if($nombre&&$id){
            if(!Edificio::where('nombre','=',$nombre)->count()){
                $edi=Edificio::find($id);
                if($edi){
                    $edi->nombre=$nombre;
                    $edi->update();
                    return response()->json([
                        "success"=>'exito'
                    ],200);
                }
                return response()->json([
                    "err"=>'no existe edificio '
                ],200);
            }else{
                return response()->json([
                    "err"=>'Ya existe edificio con ese nombre'
                ],200);
            }
        }
        return response()->json([
            "err"=>'No se puede modificar edificio'
        ],200);
    }

    public function eliminar(Request $request){
        if($request->id){
            $edifi=Edificio::find($request->id);
            $edifi->delete();
            return response()->json([
                "success"=>'exito'
            ],200);
        }
        return response()->json([
            "err"=>'No se puede eliminar'
        ],200);
    }
}
