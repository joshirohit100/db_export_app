<?php

namespace Rohit\MyApp;

use Rohit\MyApp\Query;

class WordpressQuery extends Query {

  /**
   * {@inheritdoc}
   */
  public function getData() {
    $data['user'] = $this->getUserData();
    $data['content'] = $this->getContentData();
    $data['term'] = $this->getTermData();
    $data['comment'] = $this->getCommentData();
    return $data;
  }

  /**
   * {@inheritdoc}
   */
  public function getUserData() {
    $connection = $this->getConnection();
    $sql = 'SELECT id, user_login, user_pass, user_email, user_registered FROM wp_users';
    $query = $connection->query($sql);
    $query->setFetchMode(\PDO::FETCH_ASSOC);
    $data = $query->fetchAll();
    foreach ($data as $key => $record) {
      $sub_sql = 'SELECT meta_key, meta_value FROM wp_usermeta WHERE user_id=' . $record['id'];
      $sub_query = $connection->query($sub_sql);
      $sub_query->setFetchMode(\PDO::FETCH_ASSOC);
      while($sub_record = $sub_query->fetch()) {
        $data[$key][$sub_record['meta_key']] = $sub_record['meta_value'];
      }
    }

    return $data;
  }

  /**
   * {@inheritdoc}
   */
  public function getContentData() {
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
