<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Dispositivo;
use App\PcEstudiante;
use App\Reporte;

class DispositivoController extends Controller
{
    function __construct(){
    }
    /**
     * Metodo registrar dispositivo
     */
    public function registrar(Request $request){
        $err="";
        $referencia=$request->referencia;
        $tipo=$request->tipo;
        $salon=$request->salon;
        $fila=$request->fila;
        $columna=$request->columna;
        $numero=$request->numero;
        if($fila === NULL)
            $fila = NuLL;
        if($columna === NULL)
            $columna = NuLL;
        if($referencia&&$tipo&&$salon&&$numero){
            $dispositivo=Dispositivo::where('referencia','=',$referencia);
            if($dispositivo->count()==0){
                $id=Dispositivo::max('id')+1;
                
                Dispositivo::insert(array('id'=>$id,'referencia'=>$referencia,'numero'=>$numero,'tipo'=>$tipo,'salon'=>$salon));
                if($fila!=null&&$columna!=null&&$tipo==1){
                    PcEstudiante::insert(array('id'=>$id,'fila'=>$fila,'columna'=>$columna));
                }
                return response()->json([
                    "succes"=>"exito"
                ],200);  
            } else{
                return response()->json([
                    "err"=>"Id del dispositivo existente"
                ],200);   
            }  
        }
        if(!$referencia)$err=" referencia";
        if(!$tipo)$err.=", tipo";
        if(!$salon)$err.=", salon";
        return response()->json([
            "err"=>"no se pudo registrar dato necesario".$err
        ],200);  
    }

    /**
     * Metodo visualizar dispositivo
     */
    public function visualizar(Request $request){
        
        return response()->json([
            "err"=>"no se pudo visualizar"
        ],200);  
    }
    /**
     * Metodo modificar dispositivo
     */
    public function modificar(Request $request){
        
        $salon=$request->salon;
        $fila=$request->fila;
        $columna=$request->columna;
        $numero=$request->numero;
        $id=$request->id;
        if($id){
            $dispositivo=Dispositivo::find($id);
            
            if($salon)
                $dispositivo->salon=$salon;
            if($numero)
                $dispositivo->numero=$numero;
            $dispositivo->update();   
            
            $pcE=PcEstudiante::find($id);
            if($fila!=null&&$columna!=null){
                if(isset($pcE->id)){
                    $pcE->fila=$fila;
                    $pcE->columna=$columna;
                    $pcE->update();
                }else{
                    PcEstudiante::insert(array('id'=>$id,'fila'=>$fila,'columna'=>$columna));
                }
            }else{
                $pe=PcEstudiante::find($id);
                if(isset($pe->id)){
                    $pe->delete();
                }
            }
            return response()->json([
                "success"=>"exito"
            ],200); 
        }
        return response()->json([
            "err"=>"No se pudo modificar el dispositivo"
        ],200);  
    }
    /**
     * Metodo eliminar dispositivo
     */
    public function eliminar(Request $request){
        $id=$request->id;
        if($id){
            $reportes=Reporte::where('dispositivo','=',$id)->get();
            if(isset($reportes[0])){
                return response()->json([
                    "err"=>"No se puede eliminar, tiene reportes"
                ],200);  
            }else{
                $pce=PcEstudiante::find($id);
                if($pce->id){
                    $pce->delete();
                }
                  
            }
            Dispositivo::find($id)->delete();
            return response()->json([
                "success"=>"exito"
            ],200);  
        }
        return response()->json([
            "err"=>"No se pudo eliminar"
        ],200); 
    }
    /**
     * Metodo listar dispositivo
     */
    public function listar(Request $request){
        $sala=$request->sala;
        if($sala!=null){
            $dispositivo=Dispositivo::where('salon','=',$sala)->get();

            foreach($dispositivo as $d){

                $pce=PcEstudiante::where('id','=',$d->id)->get();
                
                if(isset($pce[0])){
                    $d['fila']=$pce[0]->fila;
                    $d['columna']=$pce[0]->columna;
                }else{
                    $d['fila']=-1;
                    $d['columna']=-1;
                }
                $repor=Reporte::where('dispositivo','=',$d->id)->whereNull('reportes.fecha_solucion')->where('estado','=',2)->get();
                
                if($repor&&isset($repor[0])){
                    $d['estado']=false;
                }else{
                    $d['estado']=true;
                }
            }
            return response()->json([
                "sussess"=>$dispositivo
            ],200);  
        }
        return response()->json([
            "err"=>"Datos invalidos"
        ],200); 

    }

    public function listarAll(){
        $disp=Dispositivo::orderBy('salon')->get();
        foreach($disp as $d){
            $pce=PcEstudiante::find($d->id);
            if(isset($pce->id)){
                $d['fila']=$pce->fila;
                $d['coumna']=$pce->columna;
            }
        }
        return response()->json([
            "sussess"=>$disp
        ],200);
    }
}
