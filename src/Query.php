<?php

namespace Rohit\MyApp;

use Rohit\MyApp\QueryInterface;

abstract class Query implements QueryInterface {

  /**
   * {@inheritdoc}
   */
  public function getCommentData() {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function getTermData() {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function getUserData() {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function getContentData() {
    return [];
  }

  /**
   * Returns the actual array of all data for processing.
   *
   * @return []
   */
  public abstract function getData();

}