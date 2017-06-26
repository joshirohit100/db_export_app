<?php

namespace Rohit\MyApp;

interface QueryInterface {

  public function getUserData();
  public function getTermData();
  public function getFilesData();
  public function getContentData();
  public function getCommentData();

}
