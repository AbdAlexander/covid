<?php
include_once('storage.php');

class DateStorage extends Storage {
  public function __construct() {
    parent::__construct(new JsonIO('dates.json'));
  }
}