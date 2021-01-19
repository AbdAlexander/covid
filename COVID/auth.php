<?php

include_once("storage.php");

class Auth {
  private $user_storage;
  private $user = NULL;

  public function __construct(IStorage $user_storage) {
    $this->user_storage = $user_storage;

    if (isset($_SESSION["user"])) {
      $this->user = $_SESSION["user"];
    }
  }

  public function register($data) {
    $user = [
      'name'            => $data['name'],
      'pwd'             => password_hash($data['pwd'], PASSWORD_DEFAULT),
      'taj'             => $data['taj'],
      'email'           => $data['email'],
      "roles"           => ["user"],
      'vaccinationID'   => $data['vaccinationID']
    ];
    return $this->user_storage->add($user);
  }

  public function user_exists($username) {
    $users = $this->user_storage->findOne(['name' => $username]);
    return !is_null($users);
  }

  public function authenticate($email, $pwd) {
    $users = $this->user_storage->findMany(function ($user) use ($email, $pwd) {
      return $user["email"] === $email && 
             password_verify($pwd, $user["pwd"]);
    });
    return count($users) === 1 ? array_shift($users) : NULL;
  }
  
  public function is_authenticated() {
    return !is_null($this->user);
  }

  public function authorize($roles = []) {
    if (!$this->is_authenticated()) {
      return FALSE;
    }
    foreach ($roles as $role) {
      if (in_array($role, $this->user["roles"])) {
        return TRUE;
      }
    }
    return FALSE;
  }

  public function login($user) {
    $this->user = $user;
    $_SESSION["user"] = $user;
  }

  public function logout() {
    $this->user = NULL;
    unset($_SESSION["user"]);
  }

  public function authenticated_user() {
    return $this->user;
  }
}