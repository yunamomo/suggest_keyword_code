<?php
header('Content-Type: text/html; charset=UTF-8');
?>

<?php
session_start();
if(isset($_POST["word"])){
  $word=$_POST["word"];
}
?>

<html><head>
  <title>目的語取得</title>
</head>
<body>

<?php
echo '<form action="a_popup.php" method="post">';
echo "<input type=\"search\" name=\"word\" value=$word>";
echo '<input type="submit" name="submit" value="検索">';
echo '</form>';

$str = "python3 a_test.py \"" . $word . "\"";
$re = shell_exec($str);
$a_popup_output = explode("\n", $re);

for($i=0 ;; $i++){
    if(isset($a_popup_output[$i])){
        echo "<a href=\"https://www.google.com/search?q=$word+$a_popup_output[$i]\" target=\"_blank\" rel=\"nofollow noopener\">$a_popup_output[$i]</a><br>";
    } else {
        break;
    }
}
?>

</body></html>
