<?php


namespace App\Educar\Controller;


use App\Educar\Helper\FlashMessageTrait;
use App\Educar\Infrastructure\Persistence\ConnectionFactory;
use App\Educar\Infrastructure\Repository\PdoRepoUsers;
use App\Educar\Model\Usuario;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RecoverPasswordController implements RequestHandlerInterface
{
    use FlashMessageTrait;

    private PdoRepoUsers $repoUsers;

    public function __construct()
    {
        $pdo = ConnectionFactory::createConnection();
        $this->repoUsers = new PdoRepoUsers($pdo);
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $email1 = filter_var(
            $request->getParsedBody()['email'],
            FILTER_SANITIZE_EMAIL
        );
        $email2 = filter_var(
            $email1,
            FILTER_VALIDATE_EMAIL
        );

        $usuario = new Usuario(null, $email2, '');
        $validate = $this->repoUsers->sendEmail($usuario);


        if ($validate === false) {
            $this->definyMessage('danger', 'E-mail não encontrado');
            return new Response(
                302, ['Location' => '/recupera-senha-form']
            );
        }
        $this->definyMessage('success', 'Senha recuperada com sucesso');
        return new Response(200, ['Location' => ' / login']);
    }
}