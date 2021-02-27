<?php

use \Illuminate\Http\Request;
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

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => '/admin'], function(){
    Route::get('client', 'ClientsController@listar');
    Route::get('client/form-cadastrar', 'ClientsController@formCadastrar');
    Route::post('client/cadastrar', 'ClientsController@cadastrar');
    Route::get('client/{id}/form-editar', 'ClientsController@formEditar');
    Route::post('client/{id}/editar', 'ClientsController@editar');
    Route::get('client/{id}/excluir', 'ClientsController@excluir');
});

Route::group(['prefix' => '/'], function(){
    Route::group(['prefix' => '/examples'], function(){
        Route::get('/blade', function(){
            $nome = "Marcello Costa";
            $variavel1 = "Algum valor";

            return view('test')
            ->with('nome', $nome)
            ->with('variavel1', $variavel1);
        });

        Route::get('/env-variables', function(){
            // Only with variables_order "E" (EGPCS) enable
            //var_dump($_ENV);

            var_dump(getenv('APP_NAME'));
        });
    });
});



Route::get('/cliente/cadastrar', function(Request $request){
    $nome = "Marcello Costa";
    $variavel1 = "Algum valor";

    // METODO 1:
    /*return view('cliente.cadastrar', [
        'nome' => $nome
    ]);*/

    // METODO 2:
    //return view('cliente.cadastrar', compact('nome', 'variavel1'));

    // METODO 3:
    return view('cliente.cadastrar')
            ->with('nome', $nome)
            ->with('variavel1', $variavel1);
});

Route::get('/for-if/{value}', function($value){
    return view('for-if')->with('value', $value)
    ->with('myArray', [
        'key1' => 'value1',
        'key2' => 'value2',
        'key3' => 'value3',
    ]);
});


Route::get('/cliente', function() {
    $csrfToken = csrf_token();
    $html = <<<HTML
    <html>
    <body>
        <h1>Cliente</h1>
        <form method="POST" action="/cliente/cadastrar">
            <input type='hidden' name="_token" value="$csrfToken"/>
            <input type="text" name="name" />
            <button type='submit'>Enviar</button>
        </form>
    </body>
    </html>
HTML;

    return $html;
});

Route::post('/cliente/cadastrar', function(Request $request){
    echo $request->get('name');
    echo $request->name;
    die();
});