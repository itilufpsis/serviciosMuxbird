<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Usuario;
use App\UsuarioTipo;

class UsuarioController extends Controller
{
    function __construct(){
    }
    /**
     * Metodo validar usuario
     * Request: usario, contrasena
     */
    public function validar(Request $request){
        $user=null;
        $err=null;
        $usuario=Usuario::where('correo',$request->input('correo') );
        if($usuario->count()!=0){
            $pass=$request->input('contrasena');
            if($pass){
                
                $user=Usuario::select('usuarios.id','usuarios.nombre as name','usuarios.correo','tipo.nombre as tipo')
                ->join('usuario_tipos as ut','usuarios.id','=','ut.usuario')
                ->join('tipo_usuarios as tipo','ut.tipo','=','tipo.id')
                ->where('usuarios.correo','=',$request->input('correo'))
                ->where('usuarios.contrasena','=',$pass);
                if($request->input('tipo')==="Beca")
                    $user->where('ut.tipo','=',4);
                else if($request->input('tipo')==="Administrador")
                    $user->where('ut.tipo','=',1); 
                else $user->where('ut.tipo','!=',4);
                     
                $user=$user->first();
                if($user){
                    return response()->json([
                        $user
                    ],200);
                }$err="No existe usuario ".$request->tipo;
            }else $err="contraseña mal digitada";
        }  else $err='Correo no existe correo='.$request->input('correo');

        return response()->json([
            "err"=>$err
        ],200); 
    }
     /**
     * Metodo validar usuario
     * Request: usario, contrasena
     */
     public function postValidar(Request $request){
        $user=null;
        $err=null;
        $usuario=Usuario::where('correo',$request->input('correo') );
        if($usuario->count()!=0){
            $pass=$request->input('contrasena');
            if($pass){
                
                $user=Usuario::select('usuarios.id','usuarios.nombre as name','usuarios.correo','tipo.nombre as tipo')
                ->join('usuario_tipos as ut','usuarios.id','=','ut.usuario')
                ->join('tipo_usuarios as tipo','ut.tipo','=','tipo.id')
                ->where('usuarios.correo','=',$request->input('correo'))
                ->where('usuarios.contrasena','=',$pass);
                if($request->input('tipo')==="Beca")
                    $user->where('ut.tipo','=',4);
                else if($request->input('tipo')==="Administrador")
                    $user->where('ut.tipo','=',1); 
                else $user->where('ut.tipo','!=',4);
                     
                $user=$user->first();
                if($user){
                    return response()->json([
                        $user
                    ],200);
                }$err="No existe usuario ".$request->tipo;
            }else $err="contraseña mal digitada";
        }  else $err='Correo no existe correo='.$request->input('correo');

        return response()->json([
            "err"=>$err
        ],200);  
    }

    public function postRegistrar(Request $request){

        dd("que es");
        $nombre=$request->nombre;
        $correo=$request->correo;
        $pass=$request->contrasena;
        $tipo=$request->tipo;
        $codigo=$request->codigo;
        if($nombre&&$correo&&$pass&&$tipo&&$codigo){
            if($tipo=="Docente"||$tipo=="Estudiante"){
                if($tipo=="Docente"){
                    $tipo=2;
                }else $tipo=3;
                $array=explode("@",$correo);
                if(isset($array[1])&&$array[1]=="ufps.edu.co"){
                    $user=Usuario::where('correo','=',$correo)->get();
                    if(isset($user[0]) ){
                        return response()->json([
                            "err"=>"Usuario existente"
                        ],200);  
                    }
                    Usuario::insert(array('nombre'=>$nombre,'correo'=>$correo,'contrasena'=>$pass,'codigo'=>$codigo));
                    $id=Usuario::where('correo','=',$correo)->get();
                    UsuarioTipo::insert(array('usuario'=>$id[0]->id,'tipo'=>$tipo));
                    return response()->json([
                        "success"=>"exito"
                    ],200);

                }else{
                    return response()->json([
                        "err"=>"Correo invalido"
                    ],200); 
                }
            }
        }
        return response()->json([
            "err"=>"No se pudo registrar"
        ],200);  
    }
    /**
     */
    public function registrarBeca(Request $request){
        $correo=$request->correo;
        if($correo){
            $array=explode("@",$correo);
            if(isset($array[1])&&$array[1]=="ufps.edu.co"){
                $user=Usuario::where('correo','=',$correo)->get();
                if(isset($user[0]->id)){
                    $tipo=UsuarioTipo::where('usuario','=',$user[0]->id)->where('tipo','=',3);
                    
                    if($tipo->count()==0){
                        return response()->json([
                            "err"=>"El usuario no es estudiante"
                        ],200);
                    }else{
                    
                            if(UsuarioTipo::where('usuario','=',$user[0]->id)->where('tipo','=',4)->count() ){
                                return response()->json([
                                    "err"=>"Beca existente"
                                ],200);
                            }else{
                                UsuarioTipo::insert(array('usuario'=>$user[0]->id,'tipo'=>4) );
                                return response()->json([
                                    "success"=>"exito"
                                ],200);
                            }
                        
                    }
                }else{
                    return response()->json([
                        "err"=>"ATENCIÓN: Regístrese primero como estudiante "
                    ],200);
                }
            }else{
                return response()->json([
                    "err"=>"El correo no es institucional"
                ],200);
            }
        }
        return response()->json([
            "err"=>"No se puede registrar"
        ],200); 
    }

    public function eliminarBeca(Request $request){
        $id=$request->id;
        if($id){
            $user=Usuario::find($id);
            if(isset($user->id)){
                $ut=UsuarioTipo::where('usuario','=',$id)->where('tipo','=',4);
                if($ut->count()){
                    $ut=$ut->get();
                    UsuarioTipo::find($ut[0]->id)->delete();
                    return response()->json([
                        "success"=>"exito"
                    ],200); 
                }
                return response()->json([
                    "err"=>"No es un beca"
                ],200); 
            }
            return response()->json([
                "err"=>"No reconoce parámetos"
            ],200); 
        }
        return response()->json([
            "err"=>"No se puede eliminar"
        ],200); 
    }

    public function modificar(Request $request){
        $id=$request->id;
        if($id){
            $user=Usuario::find($id);
            if(isset($user->id)){
                $estado=false;
                $nombre=$request->nombre;
                $pass=$request->contrsena;
                $correo=$request->correo;
                if($correo&&$correo!=$user->correo){
                    if(Usuario::where('correo','=',$correo)->where('id','!=',$id)->count()){
                        return response()->json([
                            "err"=>"No reconoce parametos"
                        ],200); 
                    }
                    $array=explode("@",$correo);

                    if(!(isset($array[1])&&$array[1]=="ufps.edu.co")){
                        return response()->json([
                            "err"=>"correo no reconocido para este sitio"
                        ],200);
                    }
                   
                }    
                if($nombre)
                    $user->nombre=$nombre;
                if($pass)
                    $user->contrasena=$pass;
                if($correo)
                    $user->correo=$correo;
                $user->update();

                return response()->json([
                    "success"=>"exito"
                ],200);             
            }
            return response()->json([
                "err"=>"No reconoce parametos"
            ],200); 
        }
        return response()->json([
            "err"=>"No se puede modificar"
        ],200); 
    }
    /**
     */
    public function redirectToProviderLogin($provider)
    {
        if (Session::has('usuario')) {
            //redirecciona al index
            return Redirect::route('app.index');
        }else{
            return Socialite::driver($provider)->redirect();
        }
        
    }
    public function handleProviderCallbackLogin($provider, \Illuminate\Http\Request $request)
    {

        if (Session::has('usuario')) {
            return Redirect::route('app.index');
        }else{
            $user = Socialite::driver($provider)->user();
            $email = $user->getEmail();
            $dominios=array('ufps.edu.co');
            if(validateEmailDomain($email, $dominios)){
                $usuario=Usuario::where('correo',$email)->count();
                if ($usuario != 0) {
                    $user = Usuario::where('correo',$email)->first();
                    Session::put('usuario', $user->toArray());
                    return Redirect::route('app.index');
                } else { 
                    $name = $user->getName();
                    $request->merge(array(
                        'nombre' => $name
                    ));
                    $request->merge(array(
                        'correo' => $email
                    ));
                    $user = Usuario::create($request->all());
                    $request->merge(array(
                        'id' => $user->id
                    ));
                    $request->merge(array(
                        'tipo' => $request->tipo
                    ));
                    $usuariotipo = UsuarioTipo::create($request->all());
                    Session::put('usuario', $user->toArray());
                    return Redirect::route('app.index');
                }
            }
        return view('app.usuario.validar');
        }
    }


    /**
     * Metodo listar docentes    
     */
    public function listar_docentes(){
        $usuario=Usuario::select('usuarios.id','usuarios.nombre','usuarios.correo','usuarios.codigo')
            ->join('usuario_tipos as ut','ut.usuario','=','usuarios.id')
            ->where('ut.tipo','=',2)->get();
        return response()->json([
            "success"=>$usuario
        ],200);
    }
    /**
     * Metodo listar estudiantes    
     */
    public function listar_estudiantes(){
        $usuario=Usuario::select('usuarios.id','usuarios.nombre','usuarios.correo','usuarios.codigo')
            ->join('usuario_tipos as ut','ut.usuario','=','usuarios.id')
            ->where('ut.tipo','=',3)->get();
        return response()->json([
        "success"=>$usuario
        ],200);
    }
    /**
     * Metodo listar becas   
     */
    public function listar_becas(){
        $usuario=Usuario::select('usuarios.id','usuarios.nombre','usuarios.correo','usuarios.codigo')
            ->join('usuario_tipos as ut','ut.usuario','=','usuarios.id')
            ->where('ut.tipo','=',4)->get();
        return response()->json([
        "success"=>$usuario
        ],200);
    }

}
