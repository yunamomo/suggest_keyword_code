<?php
header('Content-Type: text/html; charset=UTF-8');
?>

<?php
if(isset($_POST["word"])){
  $word=$_POST["word"];
}
?>

<html><head>
  <title>bubun</title>
</head>
<body>

<form action="test.php" method="post">
<?php
$word = str_replace(" ", ",", $word);
echo "<input type=\"search\" name=\"word\" value=$word>";
$word = str_replace(",", " ", $word);
echo $word;
?>
<input type="submit" name="submit" value="検索">
</form>

<?php
$command="python3 test8.py \"" . $word . "\"";
exec($command,$python_3_popup);

for($i=0;;$i++){
  if(isset($python_3_popup[$i])){
    print($python_3_popup[$i]);
    echo '<br>';
  }else{
    break;
  }
}
?>
</body></html>