<?php

namespace Rohit\MyApp;

class WordpressQuery {

  public function getData() {
    $users = $this->getUserData();
    $content = $this->getContent();
    $data['user'] = $users;
    $data['content'] = $content;
    return $data;
  }

  public function getUserData() {
    $connection = $this->getConnection();
    $sql = 'SELECT id, user_login, user_pass, user_email, user_registered FROM wp_users';
    $query = $connection->query($sql);
    $query->setFetchMode(\PDO::FETCH_ASSOC);
    return $query->fetchAll();
  }

  public function getContent() {
    $connection = $this->getConnection();
    $sql = 'SELECT id, post_title, post_content, post_author, post_type FROM wp_posts';
    $query = $connection->query($sql);
    $query->setFetchMode(\PDO::FETCH_ASSOC);
    return $query->fetchAll();
  }

  public function getConnection() {
    $conntection = new \PDO("mysql:host=127.0.0.1:3306;dbname=wordpress_test", 'root', 'root');
    return $conntection;
  }
}
