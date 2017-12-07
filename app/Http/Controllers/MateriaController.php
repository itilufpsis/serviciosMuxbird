<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Materia;
use App\Clase;
use App\UsuarioTipo;
use App\Semestre;
use App\Salon;

class MateriaController extends Controller
{
    function __construct(){
    }
    /**
     * Metodo registrar materia
     */
    public function registrar(Request $request){
        $err="";
        $nombre=$request->nombre;
        $grupo=$request->grupo;
        $docente=$request->docente;
        $codigo=$request->codigo;
        if($nombre&&$grupo&&$docente&&$codigo){
            $uTipo=UsuarioTipo::where('usuario','=',$docente)->where('tipo','=',2)->get();
            if(isset($uTipo[0]->id)){
                $materia=Materia::where('codigo','=',$codigo)->where('grupo','=',$grupo);
                if($materia->count()==0){
                    $semestre=Semestre::orderBy('id','desc')->get();
                    Materia::insert(array('nombre'=>$nombre,'grupo'=>$grupo,'docente'=>$uTipo[0]->id,'codigo'=>$codigo,'semestre'=>$semestre[0]->id));
                    return response()->json([
                        "success"=>"exito"
                    ],200);  
                } else{
                    return response()->json([
                        "err"=>"Materia existente"
                    ],200);   
                }  
            }else{
                return response()->json([
                    "err"=>"No es docente"
                ],200); 
            }
        }
        if(!$nombre)$err=" nombre";
        if(!$grupo)$err.=", grupo";
        if(!$docente)$err.=", docente";
        if(!$codigo)$err.=", codigo";
        return response()->json([
            "err"=>"no se pudo registrar dato necesario".$err
        ],200);  
    }
    /**
     * Metodo modificar materia
     */
    public function modificar(Request $request){
        $err="";
        $id=$request->id;
        $nombre=$request->nombre;
        $grupo=$request->grupo;
        $docente=$request->docente;
        $codigo=$request->codigo;
        if($nombre&&$grupo&&$docente&&$id){
            $uTipo=UsuarioTipo::where('usuario','=',$docente)->where('tipo','=',2)->get();
            if(isset($uTipo[0])){
                $materia=Materia::find($id);  
                $materia->nombre=$nombre;
                $materia->grupo=$grupo;
                $materia->docente=$uTipo[0]->id;
                if($codigo)
                    $materia->codigo=$codigo;
                $materia->update();
                return response()->json([
                    "success"=>"exito"
                ],200);
            }else{
                return response()->json([
                    "err"=>"No es docente"
                ],200); 
            }
        }
        return response()->json([
            "err"=>"no se puede actualizar dato necesario"
        ],200);  
    }
    /**
     * Metodo eliminar materia
     */
    public function eliminar(Request $request){
        $id=$request->id;
        if($id){
            $clase=Clase::where('materia','=',$id)->get();
            if(isset($clase[0])){
                return response()->json([
                    "err"=>"No se puede eliminar, existe en una(a) clase(s)"
                ],200);  
            }
            Materia::where('id','=',$id)->delete();
            return response()->json([
                "success"=>"exito"
            ],200);  
        }
        return response()->json([
            "err"=>"No se pudo eliminar"
        ],200);  
    }
    /**
     * Metodo listar materia
     */
    public function listar(){
        $materias=Materia::select('materias.id','materias.nombre','materias.grupo','materias.codigo','u.id as docente','u.nombre as nombre_docente')
        ->join('usuario_tipos as ut','ut.id','=','materias.docente')
        ->join('usuarios as u','u.id','=','ut.usuario')
        ->where('ut.tipo','=',2)
        ->get();
        return response()->json([
            "sussess"=>$materias
        ],200);  
    }

    public function visualizar(Request $request){
        if($request->id){
            $materia=Materia::find($request->id);
            return response()->json([
                "success"=>$materia
            ],200);
        }
        return response()->json([
            "err"=>"no se pudo visualizar"
        ],200);
    }

    
    /**
     * Metodo asignar horario materia
     */
    public function asignar_horario(Request $request){
        $err="";
        $dia=$request->dia;
        $hora=$request->hora;
        $materia=$request->materia;
        $salon=$request->salon;
        if($materia&&$hora&&$dia&&$salon){
            $clase=Clase::where('dia','=',$dia)->where('hora','=',$hora)->where('salon','=',$salon);
            if($clase->count()==0){
                    
                Clase::insert(array('dia'=>$dia,'hora'=>$hora,'materia'=>$materia,'salon'=>$salon));
                return response()->json([
                    "success"=>"exito"
                ],200);  
            } else{
                return response()->json([
                    "err"=>"El salon ya tiene asignado un horario"
                ],200);   
            }  
        }
        if(!$dia)$err=" dia";
        if(!$hora)$err.=", hora";
        if(!$materia)$err.=", materia";
        if(!$salon)$err.=", salon";
        return response()->json([
            "err"=>"no se pudo asignar".$err
        ],200);  
    }
    /**
     * Metodo eliminar hora
     */
    public function eliminar_hora(Request $request){
        $id=$request->id;
        if($id){
            $hora=Clase::find($id);
            if(isset($hora->id) ){
                $hora->delete();
                return response()->json([
                    "success"=>"exito"
                ],200);
            }
            return response()->json([
                "err"=>"No se puede eliminar"
            ],200);  
        }
   
        return response()->json([
            "err"=>"no se pudo eliminar"
        ],200); 
    }
    /**
     * Metodo lista horas
     */
    public function listar_horario(Request $request){
        if(isset($request->materia)){
            $clase=Clase::select('clases.id','clases.dia','clases.hora','clases.salon','clases.materia','m.nombre as nombre_materia','s.nombre as nombre_salon','d.nombre as nombre_dia')
            ->join('materias as m','m.id','=','clases.materia')
            ->join('salons as s','s.id','=','clases.salon')
            ->join('dias as d','d.id','=','clases.dia')
            ->orderBY('clases.dia')
            ->orderBy('clases.hora')
            ->where('materia','=',$request->materia)->get();
            return response()->json([
                "sussess"=>$clase
            ],200); 
        }else{
            return response()->json([
                "err"=>"no se pudo listar"
            ],200); 
        }
      
    }

    public function visualizar_hora(Request $request){
        $id=$request->id;
        if($id){
            $clase=Clase::find($id);
            if(isset($clase->id)){
                $materia=Materia::find($clase->materia);
                $clase['nombre_materia']=$materia->nombre;
                $salon=Salon::find($clase->salon);
                $clase['nombre_salon']=$salon->nombre;
                return response()->json([
                    "success"=>$clase
                ],200); 
            }
        }
        return response()->json([
            "err"=>"no se puede visualizar"
        ],200); 
    }

    public function modificar_horario(Request $request){
        if($request->id){
            $hora=Clase::find($request->id);
            if($request->hora&&$request->dia&&$request->salon){
               // dd(Clase::where('hora','=',$request->hora)->where('dia','=',$request->dia)->where('salon','=',$request->salon)->count());
                if(! Clase::where('hora','=',$request->hora)->where('dia','=',$request->dia)->where('salon','=',$request->salon)->count() ){
                    $hora->hora=$request->hora;
                    $hora->dia=$request->dia;
                    $hora->salon=$request->salon;
                    $hora->update();
                    return response()->json([
                        "success"=>"exito"
                    ],200);
                }
                return response()->json([
                    "err"=>"la hora ya existe en ese salon"
                ],200);
            }
            return response()->json([
                "err"=>"No están todos los datos"
            ],200);
        }
        return response()->json([
            "err"=>"no está permitido"
        ],200);
    }
}
