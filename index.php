<?php

date_default_timezone_set("America/Sao_Paulo");
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/JWTWrapper.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Silex\Application;

$app = new Silex\Application();

$app->post('/login', function(Request $request) use ($app) {
    $dados = json_decode($request->getContent(), true);
     
    if ($dados['usuario'] == 'coala' && $dados['password'] == 'coala') {

        $jwt = JWTWrapper::encode([
                    'expiration_sec' => 3600,
                    'iss' => 'matheus.zr@gmail.com',
                    'userdata' => [
                        'id' => 1,
                        'name' => 'Matheus Zipf'
                    ]
        ]);
        return $app->json([
                    'login' => 'true',
                    'access_token' => $jwt
        ]);
    }
    return $app->json([
                'login' => 'false',
                'message' => 'Login Inálido'
    ]);
});

$app->before(function(Request $request, Application $app) {
    $route = $request->get('_route');

    if ($route != 'POST_login') {
        $authorization = $request->headers->get("jwt-token");
        list($jwt) = sscanf($authorization, 'Bearer %s');

        if ($jwt) {
            try {
                $app['jwt'] = JWTWrapper::decode($jwt);
            } catch (Exception $ex) {
                return new Response('Acesso nao autorizado', 400);
            }
        } else {
            return new Response('Token nao informado', 400);
        }
    }
});

$app->get('/categoria', function(Application $app) {
    include "/src/config.php";
    $sql = "select * from categorias order by descricao";
    
    $res = $pdo->prepare($sql);
    $res->execute();
    while ($l = $res->fetch(PDO::FETCH_OBJ)) {
        $dados[$l->id] = $l; 
    }
   
    return new Response(json_encode($dados));
});

$app->run();
