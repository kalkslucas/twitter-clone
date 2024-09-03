<?php

namespace App\Models;

use MF\Model\Model;

class Tweet extends Model
{
  private $id;
  private $idusuario;
  private $tweet;
  private $data;

  public function __get($attr)
  {
    return $this->$attr;
  }
  public function __set($attr, $value)
  {
    $this->$attr = $value;
  }

  //Inserir tweets
  public function salvar()
  {
    $query = "INSERT INTO tweets(idusuario, tweet) VALUES(?,?)";
    $stmt = $this->db->prepare($query);
    $stmt->bindValue(1, $this->__get('idusuario'), \PDO::PARAM_INT);
    $stmt->bindValue(2, $this->__get('tweet'), \PDO::PARAM_STR);
    $stmt->execute();

    return $this;
  }

  //Recuperar tweets
  public function listarTweets()
  {
    $query = "SELECT t.id as id, t.idusuario, u.nome as nome, t.tweet, DATE_FORMAT(t.data, '%d/%m/%Y %H:%i') as data 
              FROM tweets t 
              LEFT JOIN usuarios u ON t.idusuario = u.id 
              WHERE idusuario = :idusuario ORDER BY t.data DESC";
    $stmt = $this->db->prepare($query);
    $stmt->bindValue(':idusuario', $this->__get('idusuario'), \PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
  }

  //Contar tweets realizados
  public function qtdTweetsPorUsuario()
  {
    $query = "SELECT COUNT(*) as QTD_TWEETS FROM tweets WHERE idusuario = :idusuario";
    $stmt = $this->db->prepare($query);
    $stmt->bindValue(':idusuario', $this->__get("idusuario"), \PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(\PDO::FETCH_ASSOC);
  }
}
