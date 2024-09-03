<?php

namespace App;

use MF\Init\Bootstrap;

class Route extends Bootstrap
{
  protected function initRoutes()
  {
    $routes['home'] = [
      'route' => '/',
      'controller' => 'indexController',
      'action' => 'index'
    ];

    $routes['inscreverse'] = [
      'route' => '/inscreverse',
      'controller' => 'indexController',
      'action' => 'inscreverse'
    ];

    $routes['registrar'] = [
      'route' => '/registrar',
      'controller' => 'indexController',
      'action' => 'registrar'
    ];

    $routes['autenticar'] = [
      'route' => '/autenticar',
      'controller' => 'authController',
      'action' => 'autenticar'
    ];

    $routes['timeline'] = [
      'route' => '/timeline',
      'controller' => 'appController',
      'action' => 'timeline'
    ];

    $routes['tweet'] = [
      'route' => '/tweet',
      'controller' => 'appController',
      'action' => 'tweet'
    ];

    $routes['quemSeguir'] = [
      'route' => '/quem_seguir',
      'controller' => 'appController',
      'action' => 'quemSeguir'
    ];

    $routes['acao'] = [
      'route' => '/acao',
      'controller' => 'appController',
      'action' => 'acao'
    ];

    $routes['sair'] = [
      'route' => '/sair',
      'controller' => 'authController',
      'action' => 'sair'
    ];


    $this->setRoutes($routes);
  }
}
