<?php

namespace App\Controllers;

//Recursos do Miniframework
use MF\Controller\Action;
use MF\Model\Container;

//Scripts de tabelas do banco de dados
use App\Models\Usuario;


class IndexController extends Action
{
  public function index()
  {
    $this->view->login = isset($_GET['login']) ? $_GET['login'] : '';
    $this->render('index');
  }
  public function inscreverse()
  {
    $this->view->usuario = ['nome' => '', 'email' => '', 'senha' => ''];
    $this->view->erroCadastro = false;
    $this->render('inscreverse');
  }

  public function registrar()
  {
    //Receber os dados do formulário
    $user = Container::getModel('Usuario');

    $user->__set('nome', $_POST['nome']);
    $user->__set('email', $_POST['email']);
    $user->__set('senha', $_POST['senha']);

    //Sucesso
    if ($user->validarCadastro() && count($user->getUsuarioPorEmail()) == 0) {
      $user->salvar();
      $this->render('cadastro');
    } else {
      $this->view->usuario = ['nome' => $_POST['nome'], 'email' => $_POST['email'], 'senha' => $_POST['senha']];
      $this->view->erroCadastro = true;
      $this->render('inscreverse');
    }
  }
}
