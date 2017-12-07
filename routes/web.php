<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function(){
    dd("Servidor MuxBird");
});

Route::get('/validar', 'UsuarioController@validar'); //FALTA

Route::post('/validar', 'UsuarioController@postValidar');

////////////////////SOCIAL LOGIN////////////////////
Route::get('social/login/redirect/{provider}', 'UsuarioController@redirectToProviderLogin');
Route::get('social/login/{provider}', 'UsuarioController@handleProviderCallbackLogin'); 

////////////////////USUARIO////////////////////////
Route::get('/usuario/listar_docentes','UsuarioController@listar_docentes');
Route::get('/usuario/listar_estudiantes','UsuarioController@listar_estudiantes');
Route::get('/usuario/listar_becas','UsuarioController@listar_becas');
Route::post('/usuario/registrar', 'UsuarioController@postRegistrar');
Route::get('/usuario/registrar_beca','UsuarioController@registrarBeca');
Route::get('/usuario/modificar','UsuarioController@modificar');
Route::get('/usuario/eliminar_beca','UsuarioController@eliminarBeca');

//////////////////EDIFICIOS///////////////////////
Route::get('/edificio/listar','EdificioController@listar'); 
Route::get('/edificio/registrar','EdificioController@registrar'); 
Route::get('/edificio/eliminar','EdificioController@eliminar'); 
Route::get('/edificio/modificar','EdificioController@modificar'); 

/////////////////////SEMESTRE/////////////////////////////
Route::get('/semestre/listar','SalaController@listar');

/////////////////////SALAS////////////////////////
Route::get('/salas/registrar','SalaController@registrar');
Route::get('/salas/visualizar', 'SalaController@visualizar');
Route::get('/salas/modificar','SalaController@modificar');
Route::get('/salas/eliminar', 'SalaController@eliminar');
Route::get('/salas/listar','SalaController@listar');

///////////////////DISPOSITIVOS/////////////////////
Route::get('/dispositivo/registrar', 'DispositivoController@registrar');
Route::get('/dispositivo/modificar', 'DispositivoController@modificar');
Route::get('/dispositivo/eliminar','DispositivoController@eliminar');
Route::get('/dispositivo/listar','DispositivoController@listar');
Route::get('/dispositivo/listar_all','DispositivoController@listarAll');

////////////////////MATERIA////////////////////////
Route::get('/materia/registrar',  'MateriaController@registrar');
Route::get('/materia/modificar','MateriaController@modificar');
Route::get('/materia/eliminar',  'MateriaController@eliminar');
Route::get('/materia/visualizar',  'MateriaController@visualizar');
Route::get('/materia/listar', 'MateriaController@listar');

Route::get('/materia/asignar_horario','MateriaController@asignar_horario');
Route::get('/materia/modificar_horario','MateriaController@modificar_horario');
Route::get('/materia/eliminar_hora', 'MateriaController@eliminar_hora');
Route::get('/materia/listar_horario', 'MateriaController@listar_horario');
Route::get('/materia/visualizar_hora', 'MateriaController@visualizar_hora');

////////////////////REPORTES///////////////////////
Route::get('/reporte/reparar',  'ReporteController@reparar');
Route::post('/reporte/crear',  'ReporteController@crear');
Route::get('/reporte/listar_docente', 'ReporteController@listar_docente');
Route::get('/reporte/validar', 'ReporteController@validar');
Route::get('/reporte/eliminar', 'ReporteController@eliminar');
Route::get('/reporte/visualizar', 'ReporteController@visualizar');
Route::get('/reporte/buscar_qr', 'ReporteController@buscar');


//crear?docente=2&&perisferico=pantalla&&autor=3&&descripcion=se%20daño&&dispositivo=10


