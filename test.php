<?php
include("Daemon.php");
class Test extends Daemon
{
  protected function invoke() {
    while(true) {
      echo time(true)."\n";
      sleep(1);
    }
  }
}

$t = new Test();
$t->handle();

