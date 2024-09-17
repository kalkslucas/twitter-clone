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
    $query = "INSERT INTO tweets(id_usuario, tweet) VALUES(?,?)";
    $stmt = $this->db->prepare($query);
    $stmt->bindValue(1, $this->__get('idusuario'), \PDO::PARAM_INT);
    $stmt->bindValue(2, $this->__get('tweet'), \PDO::PARAM_STR);
    $stmt->execute();

    return $this;
  }

  //Recuperar tweets
  public function listarTweets()
  {
    $query = "SELECT 
                t.id as id, 
                t.id_usuario, 
                u.nome as nome, 
                t.tweet, 
                DATE_FORMAT(t.data, '%d/%m/%Y %H:%i') as data 
              FROM tweets t 
              LEFT JOIN usuarios u ON t.id_usuario = u.id 
              WHERE id_usuario = :idusuario 
              OR t.id_usuario in (SELECT id_usuario_seguindo FROM `usuarios_seguidores` WHERE id_usuario = :idusuario)
              ORDER BY t.data DESC";
    $stmt = $this->db->prepare($query);
    $stmt->bindValue(':idusuario', $this->__get('idusuario'), \PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
  }

  public function listarTweetsPorPagina($limit, $offset)
  {
    $query = "SELECT 
                t.id as id, 
                t.id_usuario, 
                u.nome as nome, 
                t.tweet, 
                DATE_FORMAT(t.data, '%d/%m/%Y %H:%i') as data 
              FROM tweets t 
              LEFT JOIN usuarios u ON t.id_usuario = u.id 
              WHERE id_usuario = :idusuario 
              OR t.id_usuario in (SELECT id_usuario_seguindo FROM `usuarios_seguidores` WHERE id_usuario = :idusuario)
              ORDER BY t.data DESC LIMIT :limit OFFSET :offset";
    $stmt = $this->db->prepare($query);
    $stmt->bindValue(':idusuario', $this->__get('idusuario'), \PDO::PARAM_INT);
    $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
  }

  public function totalTweets()
  {
    $query = "SELECT count(*) as total
              FROM tweets t 
              LEFT JOIN usuarios u ON t.id_usuario = u.id 
              WHERE id_usuario = :idusuario 
              OR t.id_usuario in (SELECT id_usuario_seguindo FROM `usuarios_seguidores` WHERE id_usuario = :idusuario)";
    $stmt = $this->db->prepare($query);
    $stmt->bindValue(':idusuario', $this->__get('idusuario'), \PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetch(\PDO::FETCH_ASSOC);
  }

  //Remover tweet publicado
  public function remover($idtweet)
  {
    $query = "DELETE FROM tweets WHERE id = ?";
    $stmt = $this->db->prepare($query);
    $stmt->bindValue(1, $idtweet, \PDO::PARAM_INT);
    $stmt->execute();

    return true;
  }
}
