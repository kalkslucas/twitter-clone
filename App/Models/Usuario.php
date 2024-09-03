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

  //Listando Usuários
  public function listarUsuarios()
  {
    $query = "SELECT id, nome, email FROM usuarios WHERE nome LIKE CONCAT('%', :nome, '%')";
    $stmt = $this->db->prepare($query);
    $stmt->bindValue(":nome", $this->__get("nome"), \PDO::PARAM_STR);
    $stmt->execute();

    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
  }

  //Pesquisando por alguns usuários
  public function pesquisar() {}
}
