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
  <title>データベースの確認のため</title>
</head>
<body>

<form action="sample.php" method="post">
<?php
if(isset($word)){
  echo "<input type=\"search\" name=\"word\" value=$word>";
}else{
  echo "<input type=\"search\" name=\"word\">";
}
echo "を含む単語";
echo "<br>";
?>
<input type="submit" name="submit" value="検索">
</form>

<?php
  $constr = "host=127.0.0.1 dbname=yuna user=yuna password=yuna";
  $conn = pg_connect($constr);
  $result = pg_query($conn, "SELECT * FROM word_category;");
  $rows = pg_num_rows($result);

if(isset($word)){
  $a=array();
  $num=0;
  for($i=0; $i<$rows; $i++){
    $rows = pg_fetch_array($result, NULL, PGSQL_ASSOC);
    if(strpos($rows['word'], $word) !== false){
      array_push($a,$rows['category']);
      $num+=1;
    }
  }

  $unique = array_unique($a);

  for($j=0 ;; $j++){
    if(isset($unique[$j])){
      if($unique[$j] == ""){
      }else{
        echo $j;
        echo $unique[$j];
        echo '<br>';
      }
    }else{
      break;
    }
  }
}

pg_close($conn);
?>

</body></html>