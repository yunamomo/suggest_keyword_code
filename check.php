<?php

$str = 'python3 test_7.py ビートたけし';
$re = shell_exec($str);
$output = explode("\n", $re);

for($i=0;;$i++){
    if(isset($output[$i])){
      print $output[$i];
      print "<br>";
    }else{
      break;
    }
  }

?>
