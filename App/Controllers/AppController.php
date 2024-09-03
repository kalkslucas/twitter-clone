<?php

namespace App\Controllers;

//Recursos do Miniframework
use MF\Controller\Action;
use MF\Model\Container;

class AppController extends Action
{
  public function timeline()
  {
    session_start();
    if (isset($_SESSION['id']) && isset($_SESSION['nome'])) {
      $this->render('timeline');
    } else {
      header('Location: /?login=erro');
    }
  }
}
