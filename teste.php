<?php

use App\Educar\Helper\Email;
use App\Educar\Model\Usuario;

require __DIR__ . '/vendor/autoload.php';


$user = new Usuario(null, 'ronyandersonpc@gmail.com', '');


$email = new Email();
$body = $email->templateEmail($user, $hash);


$email->add(
    'Solicitação de troca de senha',
    $body,
    $user->getEmail(),
    'Rony Anderson'

)->send();

if (!$email->error()) {
    var_dump(true);
} else {
    echo $email->error()->getMessage();
}

