<?php
use PHPUnit\Framework\TestCase;
use Slim\Psr7\Factory\ServerRequestFactory;

class UsuariosTest extends TestCase
{
public function testGetListaUsuarios(): void
{
$app = require __DIR__ . '/../src/app.php'; // retorna $app sem chamar ->run()
$request = (new ServerRequestFactory())
->createServerRequest('GET', '/usuarios');
$response = $app->handle($request);
$this->assertEquals(200, $response->getStatusCode());
$usuarios = json_decode((string) $response->getBody(), true);
$this->assertEquals(5, count($usuarios));
$this->assertEquals('Davi', $usuarios[0]['nome']);
}


public function testPostCriaUsuario(): void
{
$app = require __DIR__ . '/../src/app.php'; // retorna $app sem chamar ->run()
$request = (new ServerRequestFactory())
->createServerRequest('POST', '/usuarios')
->withHeader('Content-Type', 'application/json');
$request->getBody()->write(json_encode([
    'nome' => 'Mouse',
    'email' => 'mouse@email.com',
    'login' => 'mouse',
    'senha' => '123456'
]));
$request->getBody()->rewind();
$response = $app->handle($request);
$this->assertEquals(201, $response->getStatusCode());
$usuario = json_decode((string) $response->getBody(), true);
$this->assertEquals('Mouse', $usuario['nome']);
$this->assertEquals('mouse@email.com', $usuario['email']);
$this->assertEquals('mouse', $usuario['login']);
}


public function testPostCriaUsuarioComErro(): void
{
$app = require __DIR__ . '/../src/app.php'; // retorna $app sem chamar ->run()
$request = (new ServerRequestFactory())
->createServerRequest('POST', '/usuarios')
->withHeader('Content-Type', 'application/json');
$request->getBody()->write(json_encode([]));
$request->getBody()->rewind();
$response = $app->handle($request);
$this->assertEquals(400, $response->getStatusCode());
$corpo = json_decode((string) $response->getBody(), true);
$this->assertEquals('Todos os campos são obrigatórios', $corpo['erro']);
}


public function testGetUsuarioPorId(): void
{
$app = require __DIR__ . '/../src/app.php'; // retorna $app sem chamar ->run()
$request = (new ServerRequestFactory())
->createServerRequest('GET', '/usuarios/1');
$response = $app->handle($request);
$this->assertEquals(200, $response->getStatusCode());
$usuario = json_decode((string) $response->getBody(), true);
$this->assertEquals(1, $usuario['id']);
$this->assertEquals('Davi', $usuario['nome']);
}


public function testGetUsuarioPorIdInexistente(): void
{
$app = require __DIR__ . '/../src/app.php'; // retorna $app sem chamar ->run()
$request = (new ServerRequestFactory())
->createServerRequest('GET', '/usuarios/999');
$response = $app->handle($request);
$this->assertEquals(404, $response->getStatusCode());
$corpo = json_decode((string) $response->getBody(), true);
$this->assertEquals('Usuário não encontrado', $corpo['erro']);
}


public function testDeleteUsuarioPorId(): void
{
$app = require __DIR__ . '/../src/app.php'; // retorna $app sem chamar ->run()
$request = (new ServerRequestFactory())
->createServerRequest('DELETE', '/usuarios/1');
$response = $app->handle($request);
$this->assertEquals(204, $response->getStatusCode());
}


public function testDeleteUsuarioPorIdInexistente(): void
{
$app = require __DIR__ . '/../src/app.php'; // retorna $app sem chamar ->run()
$request = (new ServerRequestFactory())
->createServerRequest('DELETE', '/usuarios/999');
$response = $app->handle($request);
$this->assertEquals(404, $response->getStatusCode());
$corpo = json_decode((string) $response->getBody(), true);
$this->assertEquals('Usuário não encontrado', $corpo['erro']);
}


public function testPutUsuarioPorId(): void
{
$app = require __DIR__ . '/../src/app.php'; // retorna $app sem chamar ->run()
$request = (new ServerRequestFactory())
->createServerRequest('PUT', '/usuarios/1')
->withHeader('Content-Type', 'application/json');
$request->getBody()->write(json_encode([
    'nome' => 'Davi atualizado',
    'email' => 'davi.atualizado@email.com'
]));
$request->getBody()->rewind();
$response = $app->handle($request);
$this->assertEquals(200, $response->getStatusCode());
$usuario = json_decode((string) $response->getBody(), true);
$this->assertEquals('Davi atualizado', $usuario['nome']);
$this->assertEquals('davi.atualizado@email.com', $usuario['email']);
$this->assertEquals('davi', $usuario['login']);
}


public function testPutUsuarioPorIdInexistente(): void
{
$app = require __DIR__ . '/../src/app.php'; // retorna $app sem chamar ->run()
$request = (new ServerRequestFactory())
->createServerRequest('PUT', '/usuarios/999')
->withHeader('Content-Type', 'application/json');
$request->getBody()->write(json_encode(['nome' => 'Usuario atualizado']));
$request->getBody()->rewind();
$response = $app->handle($request);
$this->assertEquals(404, $response->getStatusCode());
$corpo = json_decode((string) $response->getBody(), true);
$this->assertEquals('Usuário não encontrado', $corpo['erro']);
}
}
