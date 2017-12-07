<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);


       //ESTADO REPORTE
        \DB::table('estado_reportes')->insert(array(
        'nombre'    =>'no validado'
        ));
        \DB::table('estado_reportes')->insert(array(
            'nombre'    =>'validado'
        ));
        \DB::table('estado_reportes')->insert(array(
            'nombre'    =>'solucionado'
        ));

        //DIAS
        \DB::table('dias')->insert(array(
            'nombre'    =>'Lunes'
        ));
        \DB::table('dias')->insert(array(
            'nombre'    =>'Martes'
        ));
        \DB::table('dias')->insert(array(
            'nombre'    =>'Miércoles'
        ));
        \DB::table('dias')->insert(array(
            'nombre'    =>'Jueves'
        ));
        \DB::table('dias')->insert(array(
            'nombre'    =>'Viernes'
        ));
        \DB::table('dias')->insert(array(
            'nombre'    =>'Sábado'
        ));
        \DB::table('dias')->insert(array(
            'nombre'    =>'Domingo '
        ));
        
        //SEMESTRE
        \DB::table('semestres')->insert(array(
            'ano'    =>'2017',
            'semestre'=>'1'
        ));
        \DB::table('semestres')->insert(array(
            'ano'    =>'2017',
            'semestre'=>'2'
        ));
        

        //TIPOS DE DISPOSITIVOS
        \DB::table('tipo_dispositivos')->insert(array(
            'nombre'    =>'pc'
        ));
        \DB::table('tipo_dispositivos')->insert(array(
            'nombre'    =>'video beam'
        ));
        \DB::table('tipo_dispositivos')->insert(array(
            'nombre'    =>'microcomponete'
        ));

        //TIPO USUARIOS
        \DB::table('tipo_usuarios')->insert(array(
            'nombre'    =>'Administrador'
        ));
        \DB::table('tipo_usuarios')->insert(array(
            'nombre'    =>'Docente'
        ));
        \DB::table('tipo_usuarios')->insert(array(
            'nombre'    =>'Estudiante'
        ));
        \DB::table('tipo_usuarios')->insert(array(
            'nombre'    =>'Beca'
        ));

        //USUARIOS
        \DB::table('usuarios')->insert(array(
            'nombre'    =>'Luis',
            'correo'    =>'luis@ufps.edu.co',
            'contrasena'=>'pass'
        ));
        \DB::table('usuarios')->insert(array(
            'nombre'    =>'Milton',
            'codigo'    =>'115100',
            'correo'    =>'arquitertura@ufps.edu.co',
            'contrasena'=>'pass'
        ));
        \DB::table('usuarios')->insert(array(
            'nombre'    =>'Eliam',
            'codigo'    =>'1151193',
            'correo'    =>'eliannahunza@ufps.edu.co',
            'contrasena'=>'pass'
        ));

        \DB::table('usuarios')->insert(array(
            'nombre'    =>'Angel',
            'codigo'    =>'1151040',
            'correo'    =>'angelaparicio@ufps.edu.co',
            'contrasena'=>'pass'
        ));

        \DB::table('usuarios')->insert(array(
            'nombre'    =>'Edwar',
            'codigo'    =>'1151111',
            'correo'    =>'edwar@ufps.edu.co',
            'contrasena'=>'pass'
        ));

        //USUARIO TIPOS
        \DB::table('usuario_tipos')->insert(array(
            'usuario'   =>1,
            'tipo'      =>1
        ));
        \DB::table('usuario_tipos')->insert(array(
            'usuario'   =>2,
            'tipo'      =>2
        ));
        \DB::table('usuario_tipos')->insert(array(
            'usuario'   =>3,
            'tipo'      =>3
        ));

        \DB::table('usuario_tipos')->insert(array(
            'usuario'   =>4,
            'tipo'      =>3
        ));
        \DB::table('usuario_tipos')->insert(array(
            'usuario'   =>5,
            'tipo'      =>3
        ));

        \DB::table('usuario_tipos')->insert(array(
            'usuario'   =>5,
            'tipo'      =>4
        ));


        //MATERIA
        \DB::table('materias')->insert(array(
            'nombre'    =>'Arquitectura Software',
            'grupo'     =>'A',
            'docente'   =>2,
            'codigo'    =>'115905',
            'semestre'  =>2
        ));

        //EDIFICIOS
        \DB::table('edificios')->insert(array(
            'nombre'    =>'Aula sur'
        ));

        \DB::table('edificios')->insert(array(
            'nombre'    =>'Aula sur D'
        ));

        //SALON
        \DB::table('salons')->insert(array(
            'nombre'    =>'AS411',
            'edificio'    =>1,
            'fila'=> 4,
            'columna'=>6
        ));
        \DB::table('salons')->insert(array(
            'nombre'    =>'AS410',
            'edificio'    =>1,
            'fila'=> 4,
            'columna'=>6
        ));
        \DB::table('salons')->insert(array(
            'nombre'    =>'AS401',
            'edificio'    =>1,
            'fila'=> 4,
            'columna'=>6
        ));
        

        //CLASES
        \DB::table('clases')->insert(array(
            'dia'    =>3,
            'hora'    =>9,
            'salon'  =>1,
            'materia'=>1
        ));
        \DB::table('clases')->insert(array(
            'dia'    =>4,
            'hora'    =>10,
            'salon'  =>1,
            'materia'=>1
        ));
        \DB::table('clases')->insert(array(
            'dia'    =>4,
            'hora'    =>11,
            'salon'  =>1,
            'materia'=>1
        ));

        //DISPOSITIVO SALON 411
        $j=0;
        $k=0;
        for ($i=0; $i<18; $i++) {
            \DB::table('dispositivos')->insert(array(
            'numero'=>($i+1) ,  
            'numero_reportes'    =>0,
            'tipo'    =>1,
            'salon'  =>1,
            'referencia'=>$i."_RF_PC_" .$j."_".$k
            ));
            \DB::table('pc_estudiantes')->insert(array(
                'id'    =>$i+1,
                'fila'    =>$j,
                'columna'  =>$k,
                ));
            if($k==5){
                $j++;
                $k=0;
            }else $k++;  
        }
    }
}
