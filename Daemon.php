<?php
/**
 * @file PackApk.php.php
 * @author 燕睿涛 ritoyan@163.com
 * @date 2015-05-06
 * @desc 多进程处理任务，进程控制（启动，停止）
 *
 *  -s start --count {num} 开启一个进程
 *  -s stop --count {num} 关闭一个进程
 *  
 **/

class Daemon
{

  public function __construct() {
 
  }
  /**
   * 代码的处理程序
   */ 
  public function handle() {
    global $argv;

    $options = "s:";
    $longoptions = ["count:"];

    $signal = getopt($options, $longoptions);
    if(!isset($signal['s']) || !$signal['s'] || !isset($signal['count']) || !$signal['count']) {
      $this->_msg("参数不正确，-s [start|stop] --count {num}");
      exit(-1);
    }
    $s = $signal['s'];
    $count = $signal['count'];
    switch($s) {
      case 'start': 
        $this->_start($count);
      break;
      case 'stop': 
        $this->_stop($count);
      break;
      default: {
        $this->_msg("参数不正确，-s [start|stop] --count {num}");
        exit(-1);
      }break;
    }
  }
  /**
   * 这里需要实现自己的daemon进程逻辑
   */
  protected function invoke() {
    $this->_msg("这里需要你实现一个死循环程序");
    exit(-1);
  }

  /**
   * 判断当前文件、进程号码为$count的进程是否在运行
   */
  private function _isRun($count) {
    $command = $this->_getGrepCommand($count);
    if(!$command) {
      $this->_msg("获取停止匹配字符串失败");
      exit(-1);
    }
    //如果正在运行，报错
    $shell = "ps aux | grep -E \"".$command."\" | wc -l";
    $this->_msg('运行shell脚本：'.$shell);
    $num = shell_exec($shell);
    return $num > 1 ? true : false;
  }

  /**
   * 启动进程，
   * @count int 进程号，同一文件可以有多个进程
   */
  private function _start($count) {
    if($this->_isRun($count)) {
      $this->_msg("进程正在运行！");
      exit(-1);
    }
    $this->invoke();
  }

  /**
   * 结束进程的代码
   */
  protected function _stop($count) {
    $command = $this->_getGrepCommand($count);
    if(!$command) {
      $this->_msg("获取停止匹配字符串失败");
      exit(-1);
    }
    $shell = "ps aux | grep -E \"".$command."\" | awk '{print $2}' | xargs --no-run-if-empty kill";
    $this->_msg("\n正在结束进程，进程信息".$command."\n");
    shell_exec($shell);
    $this->_msg("\n进程已经结束\n");
  }

  /**
   * 获取grep规则字符串，用于ps aux时候匹配
   */
  private function _getGrepCommand($count) {
    global $argv;
    $file = $argv[0];
    $ret = "\s*{$file}\s*-s";
    $count = intval($count);
    if(!$count) {
      return false;
    }
    $ret .= "\s*start\s*--count\s*{$count}";
    return $ret;
  }

  /**
   * 输出内容
   */
  protected function _msg($msg) {
    echo $msg;
  }
}
