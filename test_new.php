<?php
include("Daemon.php");
class Test_New extends Daemon
{
  protected function invoke() {
    while(true) {
      echo time(true)."\n";
      sleep(1);
    }
  }
}

$t = new Test_New();
$t->handle();

