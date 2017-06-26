<?php

namespace SfDataExport;

interface QueryInterface {

  public function getUserData();
  public function getTermData();
  public function getFilesData();
  public function getContentData();
  public function getCommentData();

}
