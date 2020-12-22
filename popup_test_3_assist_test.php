<?php
header('Content-Type: text/html; charset=UTF-8');
?>


<?php
session_start();
$word=$_GET['word'];
$category=$_GET["category"];
$category_match=$_GET["category_match"];
$count=$_GET["count"];
$number=$_GET["number"];

if($number==0){
  if($category_match!='' || $count!=''){
    $command="python3 bunkai.py " . $category_match;
    exec($command,$category_match_0);
    $command="python3 bunkai.py " . $count;
    exec($command,$count_0);
  }
}
if($number==1){
  if($category_match!=''){
    $command="python3 bunkai.py " . $category_match;
    exec($command,$category_match_0);
  }
}

?>

<?php
  $constr = "host=127.0.0.1 dbname=yuna user=yuna password=yuna";
  $conn = pg_connect($constr);
  $result = pg_query($conn, "SELECT * FROM word_category;");
  $rows = pg_num_rows($result);

  echo $word;  echo '<br>';  echo $category;  echo '<br>';  echo $category_match;  echo '<br>';  echo $count;  echo '<br>';  echo $number;

  echo mb_detect_encoding($category_match_0[0]) . "\n";
  echo mb_detect_encoding($category_match_0[1]) . "\n";

  pg_close($conn);
?>