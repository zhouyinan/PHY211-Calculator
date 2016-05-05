<?php
class Grade{
  protected $score;
  protected $full_score;

  function __construct($s,$f){
    $this->score = $s;
    $this->full_score = $f;
  }

  public function getScore(){
    return (int)$this->score;
  }

  public function getFullScore(){
    return (int)$this->full_score;
  }

  public function getFloat(){
    return ((float)$this->score / (float)$this->full_score);
  }

  public function getPercentage(){
    return 100*((float)$this->score / (float)$this->full_score);
  }

  public static function compare_sort_by_score($a,$b){
    if($a->getFloat() == $b->getFloat()){
      return 0;
    }
    return ($a->getFloat() > $b->getFloat()) ? 1 : -1;
  }
}

class HomeworkGrade extends Grade{

  protected $index;
  protected $dropped;

  function __construct($i,$s,$f){
    parent::__construct($s,$f);
    $this->index = $i;
    $this->dropped = false;
  }

  public function getIndex(){
    return $this->index;
  }

  public function drop(){
    $this->dropped = true;
  }

  public static function compare_sort_by_index($a,$b){
    if($a->getIndex() == $b->getIndex()){
      return 0;
    }
    return ($a->getIndex() > $b->getIndex()) ? 1 : -1;
  }

  public function isDropped(){
    return $this->dropped;
  }
}

class ComputationalProjectGrade extends Grade{

  protected $index;

  function __construct($i,$s,$f){
    parent::__construct($s,$f);
    $this->index = $i;
  }

  public function getIndex(){
    return $this->index;
  }

  public static function compare_sort_by_index($a,$b){
    if($a->getIndex() == $b->getIndex()){
      return 0;
    }
    return ($a->getIndex() > $b->getIndex()) ? 1 : -1;
  }
}

class ExamGrade extends Grade{

  protected $index;
  protected $dropped;

  function __construct($i,$s,$f){
    parent::__construct($s,$f);
    $this->index = $i;
    $this->dropped = false;
  }

  public function getIndex(){
    return $this->index;
  }

  public function drop(){
    $this->dropped = true;
  }

  public static function compare_sort_by_index($a,$b){
    if($a->getIndex() == $b->getIndex()){
      return 0;
    }
    return ($a->getIndex() > $b->getIndex()) ? 1 : -1;
  }

  public function isDropped(){
    return $this->dropped;
  }
}
