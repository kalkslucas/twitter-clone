<?php

namespace App\Models;

use MF\Model\Model;

class Usuario extends Model
{
  private $id;
  private $nome;
  private $email;
  private $senha;

  public function __get($attr)
  {
    return $this->$attr;
  }
  public function __set($attr, $value)
  {
    $this->$attr = $value;
  }
  //Salvar o usuário
  public function salvar()
  {
    $query = "INSERT INTO usuarios(nome, email, senha) VALUES(?,?,?)";
    $stmt = $this->db->prepare($query);
    $stmt->bindValue(1, $this->__get('nome'), \PDO::PARAM_STR);
    $stmt->bindValue(2, $this->__get('email'), \PDO::PARAM_STR);
    $stmt->bindValue(3, md5($this->__get('senha')), \PDO::PARAM_STR);
    $stmt->execute();

    return $this;
  }

  //Validar registro para cadastro

  public function validarCadastro()
  {
    $valido = true;
    if (strlen($this->__get('nome')) < 3) {
      $valido = false;
    }
    if (strlen($this->__get('email')) < 3) {
      $valido = false;
    }
    if (strlen($this->__get('senha')) < 3) {
      $valido = false;
    }
    return $valido;
  }
  //recuperar um usuário por email
  public function getUsuarioPorEmail()
  {
    $query = "SELECT nome, email from usuarios where email = :email";
    $stmt = $this->db->prepare($query);
    $stmt->bindValue(':email', $this->__get("email"), \PDO::PARAM_STR);
    $stmt->execute();

    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
  }

  //Autenticar usuário
  public function autenticar()
  {
    $query = "SELECT id, nome, email FROM usuarios WHERE email = :email AND senha = :senha";
    $stmt = $this->db->prepare($query);
    $stmt->bindValue(":email", $this->__get("email"), \PDO::PARAM_STR);
    $stmt->bindValue(":senha", md5($this->__get("senha")), \PDO::PARAM_STR);
    $stmt->execute();
    $usuario = $stmt->fetch(\PDO::FETCH_ASSOC);

    if ($usuario['id'] != '' && $usuario['nome'] != '') {
      $this->__set('id', $usuario['id']);
      $this->__set('nome', $usuario['nome']);
    }
    return $this;
  }

  //Pesquisando por alguns usuários
  public function listarUsuarios()
  {
    $query = "SELECT u.id, u.nome, u.email, 
              (
                SELECT count(*) as seguindo_sn 
                FROM usuarios_seguidores us 
                WHERE us.id_usuario = :id 
                and us.id_usuario_seguindo = u.id
              ) as seguindo_sn
              FROM usuarios u
              WHERE u.nome 
              LIKE CONCAT('%', :nome, '%') 
              AND u.id != :id";
    $stmt = $this->db->prepare($query);
    $stmt->bindValue(":nome", $this->__get("nome"), \PDO::PARAM_STR);
    $stmt->bindValue(":id", $this->__get('id'), \PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
  }

  //Seguir Usuário
  public function seguirUsuario($id_usuario_seguindo)
  {
    $query = "INSERT INTO usuarios_seguidores (id_usuario, id_usuario_seguindo) VALUES (?, ?)";
    $stmt = $this->db->prepare($query);
    $stmt->bindValue(1, $this->__get("id"), \PDO::PARAM_INT);
    $stmt->bindValue(2, $id_usuario_seguindo, \PDO::PARAM_INT);
    $stmt->execute();

    return $this;
  }

  //Deixar de Seguir Usuário
  public function deixarSeguirUsuario($id_usuario_seguindo)
  {
    $query = "DELETE FROM usuarios_seguidores WHERE id_usuario = :id_usuario AND id_usuario_seguindo = :id_usuario_seguindo";
    $stmt = $this->db->prepare($query);
    $stmt->bindValue(":id_usuario", $this->__get("id"), \PDO::PARAM_INT);
    $stmt->bindValue(":id_usuario_seguindo", $id_usuario_seguindo, \PDO::PARAM_INT);
    $stmt->execute();

    return $this;
  }
}
