<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Reporte;
use App\HistorialReporte;
use App\UsuarioTipo;
use App\Dispositivo;
use App\Semestre;
use App\Salon;

class ReporteController extends Controller
{
    public function selectByDispositivo(Request $request){
        $repoete=Reporte::all();
        return response()->json([
            "msg"=>"Success",
            "convenio"=>$reporte
        ],200);
    }

    public function reparar(Request $request){

        $id=$request->id;
        $autor=$request->autor;
        $descripcion=$request->descripcion;
        if($id!=null&&$autor!=null){
            $reporte=Reporte::where('id','=',$id)->whereNull('fecha_solucion')->where('estado','=',2)->get(); 
            if(isset($reporte[0])){
                $fecha=date("Y-m-d");
                $reporte=Reporte::find($id);
                HistorialReporte::insert(array('reporte'=>$reporte->id,'autor'=>$autor,'fecha'=>$fecha,'estado'=>3,'descripcion'=>$descripcion));
                $reporte->fecha_solucion=$fecha;
                $reporte->estado=3;
                $reporte->save();
                return response()->json([
                    "success"=>"exito"
                ],200);
            }
            return response()->json([
                "err"=>"no tiene reportes"
            ],200);
        }
        return response()->json([
            "err"=>"no se pudo reparar faltan datos"
        ],200);  
    }
    public function crear(Request $request){

        $docente=$request->docente;
        $dispositivo=$request->dispositivo;
        $perisferico=$request->perisferico;
        $descripcion=$request->descripcion;
        $autor=$request->autor;

        if($docente!=null&&$dispositivo!=null&&$autor!=null){
            $fecha=date("Y-m-d");
            $cant=Reporte::where('dispositivo','=',$dispositivo)->where('perisferico','=',$perisferico)->whereNull('fecha_solucion')->count();
            if($cant==0){
                $dispo=Dispositivo::find($dispositivo);
                if(isset($dispo)){
                    if($dispo->tipo==1&& ($perisferico=="teclado"||$perisferico=="pantalla"||$perisferico=="torre"||$perisferico=="mouse")||$dispo->tipo!=1){
                        $semestre=Semestre::orderBy('id','desc')->get();
                        $id=Reporte::max('id')+1;
                        Reporte::insert(array('id'=>$id,'dispositivo' => $dispositivo,'docente'=>$docente,'fecha'=>$fecha,'perisferico'=>$perisferico,'estado'=>1,'semestre'=>$semestre[0]->id));
                        
                        HistorialReporte::insert(array('reporte'=>$id,'autor'=>$autor,'fecha'=>$fecha,'estado'=>1,'descripcion'=>$descripcion));
                    }else{
                        return response()->json([
                            "err"=>"no se puede registrar revice el persiferico del PC que esta enviando "
                        ],200);
                    }
                }else{
                    return response()->json([
                        "err"=>"El dispositivo no existe"
                    ],200);
                }
            }else{ 
                $reporte=Reporte::where('dispositivo','=',$dispositivo)->where('perisferico','=',$perisferico)->whereNull('fecha_solucion')->get();
                if($reporte[0]->id){
                    $reporte[0]->cantidad=$reporte[0]->cantidad+1;
                    $reporte[0]->update();
                    if($reporte[0]->cantidad>=11){
                        return response()->json([
                            "err"=>"El dispositivo esta para cambio"
                        ],200); 
                    }
                }
                return response()->json([
                    "err"=>"ya fue registrado el dispositivo"
                ],200); 
            }    
            return response()->json([
                "success"=>"exito"
            ],200); 
        }


        return response()->json([
            "err"=>"no se pudo crear"
        ],200);  
    }
    public function listar_docente(Request $request){
        $docente=$request->docente;
        if($docente){
            $comproDocent=UsuarioTipo::find($docente);
            if($comproDocent&&$comproDocent->tipo==2){
                $reporte=Reporte::select('reportes.id','reportes.fecha','reportes.dispositivo','reportes.docente','reportes.perisferico','reportes.cantidad','h.autor','h.descripcion','d.referencia')
                    ->join('historial_reportes as h','h.reporte','=','reportes.id')
                    ->join('dispositivos as d','d.id','=','reportes.dispositivo')
                    ->where('reportes.docente','=',$docente)->where('reportes.estado','=',1)->get();
                return response()->json([
                    "success"=>$reporte
                ],200);      
            }else{
                return response()->json([
                    "err"=>"usuario no es docente"
                ],200);  
            }
        }
        return response()->json([
            "err"=>"no se pudo asignar"
        ],200);  
    }
    public function validar(Request $request){
        $dipositivo=$request->dispositivo;
        $docente=$request->docente;
        $perisferico=$request->perisferico;
        $descripcion=$request->descripcion;
        $id=$request->id;

        if($id||($dispositivo&&$docente&&$perisferico) ){
            if($id){
                
                if(Reporte::where('id','=',$id)->where('estado','=',1)->count()){
                    if(!$descripcion)
                        $descripcion="";
                    $fecha=date("Y-m-d");
                    $reporte=Reporte::find($id);
                    HistorialReporte::insert(array('reporte'=>$reporte->id,'autor'=>$reporte->docente,'fecha'=>$fecha,'estado'=>2,'descripcion'=>$descripcion));
                    $reporte->estado=2;
                    $reporte->update();
                    return response()->json([
                        "success"=>"exito"
                    ],200);
                }
                return response()->json([
                    "err"=>"No existe reporte para validar"
                ],200);
            }
        }
        return response()->json([
            "err"=>"no se pudo asignar"
        ],200);  
    }
    public function eliminar(Request $request){
        $id=$request->id;
        if($id){
            HistorialReporte::where('reporte','=',$id)->delete();
            Reporte::where('id','=',$id)->delete();
            return response()->json([
                "success"=>"exito"
            ],200); 
        }
        return response()->json([
            "err"=>"no se pudo asignar"
        ],200);  
    }

    public function visualizar(Request $request){
        $id=$request->id;
        if($id){
            
            $reporte=Reporte::select('reportes.id','reportes.fecha','reportes.dispositivo','reportes.docente','reportes.perisferico',
                                'd.nombre as nombre_docente','d.codigo as codigo_docente')
            ->join('usuarios as d','reportes.docente','=','d.id')
            ->where('dispositivo','=',$id)->where('estado','=',2)->get();
            foreach($reporte as $r){
                $autor=HistorialReporte::select('e.nombre','e.codigo','historial_reportes.reporte','historial_reportes.descripcion')
                ->join('usuarios as e','e.id','=','historial_reportes.autor')
                ->where('historial_reportes.reporte','=',$r->id)
                ->where('historial_reportes.estado','=',1)
                ->get();
                if(isset($autor[0])){
                    $r['nombre_autor']=$autor[0]->nombre;
                    $r['codigo_autor']=$autor[0]->codigo;
                    $r['descripcion']=$autor[0]->descripcion;
                }
            }
            return response()->json([
                "success"=>$reporte
            ],200);
        }
        return response()->json([
            "err"=>"no se pudo visualizar"
        ],200); 
    }

    public function buscar(Request $request){
        
         $dis=null;
         if($request->numero){
             $dis=Dispositivo::where('numero','=',$request->numero);
         }else if($request->referencia){
             $dis=Dispositivo::where('referencia','=',$request->referencia);
         }else if($request->id){
             $dis=Dispositivo::where('id','=',$request->id);
         }else {
             return response()->json([
                 "err"=>"No reconoce parametros"
             ],200); 
         }
         if(isset($dis)&&$dis->count()){
             $dis=$dis->get();
             $dis=$dis[0];
             if($dis->tipo==1)
                 $tipo="pc";
             else if($dis->tipo==2){
                 $tipo="vb";
             } else{
                 $tipo="mn";
             }
             $salon=Salon::find($dis->salon);
             $men=$tipo."_".$dis->id."_".$salon->nombre;
             return response()->json([
                 "succes"=>$men
             ],200); 
         }else{
             return response()->json([
                 "err"=>"No encontro dispositivo"
             ],200); 
         }
     }
}
