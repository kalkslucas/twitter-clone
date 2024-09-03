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
    $qtdTweetsPorUsuario = $tweet->qtdTweetsPorUsuario();
    $tweets = $tweet->listarTweets();

    $this->view->qtdTweetsPorUsuario = $qtdTweetsPorUsuario['QTD_TWEETS'];
    $this->view->tweets = $tweets;
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

  public function validaAutenticacao()
  {
    session_start();
    if (!isset($_SESSION['id']) || isset($_SESSION['id']) == "" && !isset($_SESSION['nome']) || isset($_SESSION['nome']) == "") {
      header('Location: /?login=erro');
    }
  }
}
