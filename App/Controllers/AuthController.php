<?php

namespace App\Controllers;

//Recursos do Miniframework
use MF\Controller\Action;
use MF\Model\Container;

class AuthController extends Action
{
  public function autenticar()
  {
    $user = Container::getModel("Usuario");
    $user->__set("email", $_POST['email']);
    $user->__set("senha", $_POST['senha']);

    $retorno = $user->autenticar();
    if ($user->__get('id') != '' && $user->__get('nome') != '') {
      session_start();
      $_SESSION['id'] = $user->__get('id');
      $_SESSION['nome'] = $user->__get('nome');
      header('location: /timeline');
    } else {
      header('Location: /?login=erro');
    }
  }

  public function sair()
  {
    session_start();
    session_destroy();
    header('Location: /');
  }
}
