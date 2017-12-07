<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Salon;
use App\Dispositivo;
use App\Clase;

class SalaController extends Controller
{
    function __construct(){
    }
    /**
     * Metodo registrar sala
     */
    public function registrar(Request $request){
        $nombre=$request->nombre;
        $edificio=$request->torre;
        $fila=$request->fila;
        $columna=$request->columna;
        if($nombre&&$edificio){
            Salon::insert(array('nombre'=>$nombre,'edificio'=>$edificio,'fila'=>$fila,'columna'=>$columna));
            return response()->json([
                "success"=>"exito"
            ],200); 
        }
        return response()->json([
            "err"=>"No se pudo registrar"
        ],200);  
    }
    /**
     * Metodo visualizar sala
     */
    public function visualizar(Request $request){
        $id=$request->id;
        if($id){
            $salon=Salon::find($id);
            return response()->json([
                "success"=>$salon
            ],200); 
        }
        return response()->json([
            "err"=>"No se pudo visualizar"
        ],200);  
    }
    /**
     * Metodo modificar sala
     */
    public function modificar(Request $request){
        $id=$request->id;
        $nombre=$request->nombre;
        $edificio=$request->torre;
        $fila=$request->fila;
        $columna=$request->columna;
        if($id){
            $salon=Salon::find($id);
            if($nombre)
                $salon->nombre=$nombre;
            if($edificio)
                $salon->edificio=$edificio;
            if($fila)
                $salon->fila=$fila;
            if($columna)
                $salon->columna=$columna;
            $salon->update();                
            return response()->json([
                "success"=>"exito"
            ],200); 
        }
        return response()->json([
            "err"=>"No se pudo modificar"
        ],200);  
    }
    /**
     * Metodo eliminar sala
     */
    public function eliminar(Request $request){
        $id=$request->id;
        if($id){
            $dispositivo=Dispositivo::where('salon','=',$id)->get();
            if(isset($dispositivo[0])){
                return response()->json([
                    "err"=>"No se puede eliminar, tiene dispositivos"
                ],200);  
            }else{
                $clase=Clase::where('salon','=',$id)->get();
                if(isset($clase[0])){
                    return response()->json([
                        "err"=>"No se puede eliminar, tiene horarios asignados"
                    ],200);  
                }
            }
            Salon::where('id','=',$id)->delete();
            return response()->json([
                "success"=>"exito"
            ],200);  
        }
        return response()->json([
            "err"=>"No se pudo eliminar"
        ],200);  
    }
    /**
     * Metodo listar sala
     */
    public function listar(){
        $salones=Salon::select('salons.id','salons.nombre','salons.edificio','salons.fila','salons.columna','e.nombre as nombre_edificio')
        ->join('edificios as e','salons.edificio','=','e.id')->get();
        return response()->json([
            "sussess"=>$salones
        ],200);  
    }
}
