<?php

namespace App\Controllers;

//Recursos do Miniframework
use MF\Controller\Action;
use MF\Model\Container;

class AppController extends Action
{
  public function timeline()
  {
    $this->validaAutenticacao();
    //Recuperando os tweets
    $tweet = Container::getModel('Tweet');

    $tweet->__set('idusuario', $_SESSION['id']);

    //Variáveis de paginação
    $pagina = isset($_GET['pagina']) ? $_GET['pagina'] : 1;
    $total_registros_pagina = 10; //Limit
    $deslocamento = ($pagina - 1) * $total_registros_pagina; //Offset

    //$tweets = $tweet->listarTweets();
    $tweets = $tweet->listarTweetsPorPagina($total_registros_pagina, $deslocamento);
    $total_tweets = $tweet->totalTweets();
    $this->view->total_paginas = ceil($total_tweets['total'] / $total_registros_pagina);
    $this->view->pagina_ativa = $pagina;

    $this->view->tweets = $tweets;

    $usuario = Container::getModel('Usuario');
    $usuario->__set('id', $_SESSION['id']);

    $this->view->info_usuario = $usuario->getInfoUsuario();
    $this->view->total_tweets = $usuario->qtdTweetsPorUsuario();
    $this->view->total_seguindo = $usuario->qtdTotalSeguindo();
    $this->view->total_seguidores = $usuario->qtdTotalSeguidores();

    $this->render('timeline');
  }

  public function tweet()
  {
    $this->validaAutenticacao();
    $tweet = Container::getModel('Tweet');
    $tweet->__set('tweet', $_POST['tweet']);
    $tweet->__set('idusuario', $_SESSION['id']);

    $tweet->salvar();
    header('Location: /timeline');
  }

  //Pesquisa por outros usuários do sistema para seguir
  public function quemSeguir()
  {
    $this->validaAutenticacao();
    $pesquisarPor = isset($_GET['pesquisarPor']) ? $_GET['pesquisarPor'] : '';
    $usuarios = [];
    if ($pesquisarPor != '') {
      $usuario = Container::getModel('Usuario');
      $usuario->__set('nome', $pesquisarPor);
      $usuario->__set('id', $_SESSION['id']);
      $usuarios = $usuario->listarUsuarios();
    }
    $this->view->pesquisarPorUsuario = $usuarios;

    $usuario = Container::getModel('Usuario');
    $usuario->__set('id', $_SESSION['id']);

    $this->view->info_usuario = $usuario->getInfoUsuario();
    $this->view->total_tweets = $usuario->qtdTweetsPorUsuario();
    $this->view->total_seguindo = $usuario->qtdTotalSeguindo();
    $this->view->total_seguidores = $usuario->qtdTotalSeguidores();

    $this->render('quemSeguir');
  }

  public function acao()
  {
    $this->validaAutenticacao();

    $acao = isset($_GET['acao']) ? $_GET['acao'] : '';
    $id_usuario_seguindo = isset($_GET['idusuario']) ? $_GET['idusuario'] : '';

    $usuario = Container::getModel('Usuario');
    $usuario->__set('id', $_SESSION['id']);

    $idtweet = isset($_GET['idtweet']) ? $_GET['idtweet'] : '';
    $tweet = Container::getModel('Tweet');

    switch ($acao) {
      case 'seguir':
        $usuario->seguirUsuario($id_usuario_seguindo);
        header("Location: /quem_seguir");
        break;
      case 'deixar_de_seguir':
        $usuario->deixarSeguirUsuario($id_usuario_seguindo);
        header('Location: /quem_seguir');
        break;
      case 'remover_tweet':
        $tweet->remover($idtweet);
        header('Location: /timeline');
    }
  }


  //Validação do usuário para utilizar das funções do sistema
  public function validaAutenticacao()
  {
    session_start();
    if (!isset($_SESSION['id']) || isset($_SESSION['id']) == "" && !isset($_SESSION['nome']) || isset($_SESSION['nome']) == "") {
      header('Location: /?login=erro');
    }
  }
}
