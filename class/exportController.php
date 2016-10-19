<?php
class exportController{

  public function initializeManager(){
    return new exportManager();
  }

  public function exportExcelDay(){
    $export = $this->initializeManager()->exportExcelDay();
  }

  public function exportExcelWeek(){
    $export = $this->initializeManager()->exportExcelWeek();
  }

  public function exportExcelMonth(){
    $export = $this->initializeManager()->exportExcelMonth();
  }
}
?>
