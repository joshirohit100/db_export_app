<?php

namespace SfDataExport\data_src;

use SfDataExport\Query;

class Wordpress extends Query {

  /**
   * {@inheritdoc}
   */
  public function getData() {
    $data['user'] = $this->getUserData();
    $data['term'] = $this->getTermData();
    $data['content'] = $this->getContentData();
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
    $data['users'] = $query->fetchAll();
    foreach ($data['users'] as $key => $record) {
      $sub_sql = 'SELECT meta_key, meta_value FROM wp_usermeta WHERE user_id=' . $record['id'];
      $sub_query = $connection->query($sub_sql);
      $sub_query->setFetchMode(\PDO::FETCH_ASSOC);
      while ($sub_record = $sub_query->fetch()) {
        $data['users'][$key][$sub_record['meta_key']] = $sub_record['meta_value'];
      }
    }

    return $data;
  }

  /**
   * {@inheritdoc}
   */
  public function getTermData() {
    $connection = $this->getConnection();
    $sql = 'SELECT term_id, name FROM wp_terms';
    $query = $connection->query($sql);
    $query->setFetchMode(\PDO::FETCH_ASSOC);
    $data['wp_terms'] = $query->fetchAll();

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
    $data['content'] = $query->fetchAll();
    foreach ($data['content'] as $key => $record) {
      $sub_sql = 'SELECT term_taxonomy_id FROM wp_term_relationships WHERE object_id=' . $record['id'];
      $sub_query = $connection->query($sub_sql);
      $sub_query->setFetchMode(\PDO::FETCH_ASSOC);
      $tags = '';
      while ($sub_record = $sub_query->fetch()) {
        $tags .= $sub_record['term_taxonomy_id'] . ',';
      }
      $data['content'][$key]['tag_ids'] = rtrim($tags, ',');
    }

    return $data;
  }

  /**
   * {@inheritdoc}
   */
  public function getCommentData() {
    $connection = $this->getConnection();
    $sql = 'SELECT comment_ID, comment_post_ID, comment_author, comment_author_email, comment_author_url, comment_author_IP, comment_date, comment_content,
 comment_approved, comment_parent, user_id FROM wp_comments';
    $query = $connection->query($sql);
    $query->setFetchMode(\PDO::FETCH_ASSOC);
    $data['comment'] = $query->fetchAll();

    return $data;
  }

  /**
   * Database connection.
   *
   * @return \PDO
   *   PDO object.
   */
  public function getConnection() {
    global $config;

    $host = $config['connection']['mysql']['host'];
    $db   = $config['connection']['mysql']['db'];
    $user = $config['connection']['mysql']['user'];
    $pass = $config['connection']['mysql']['password'];

    $options = [\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'];
    $connection = new \PDO("mysql:host=$host;dbname=$db", $user, $pass, $options);

    return $connection;
  }

}
