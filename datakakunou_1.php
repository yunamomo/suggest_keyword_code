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
  <title>データ格納</title>
</head>
<body>

<form action="datakakunou_1.php" method="post">
<?php
echo "<input type=\"search\" name=\"word\" value=$word>";
echo "<br>";
?>
<br>
<input type="submit" name="submit" value="検索">
</form>

<?php
$constr = "host=127.0.0.1 dbname=yuna user=yuna password=yuna";
$conn = pg_connect($constr);
$result = pg_query($conn, "SELECT * FROM category_ont_pro;");
$rows = pg_num_rows($result);
//ここまでがdb

$category_property_already=array();
$category_already=array();

for($num=0; $num<$rows; $num++){  //結果行数分のループ
  $rows = pg_fetch_array($result, NULL, PGSQL_ASSOC);
  $category_property_already[$num] = $rows['category_property'];
  $category_already[$num] = $rows['category'];
}

$count=0;

$command="python3 test8.py " . $word;
exec($command,$python_2);
$comma_separated = implode(",", $python_2);

$command="python3 test_4.py " . $comma_separated . " Thing";
exec($command,$python_3);
$category=$python_3[0];

$null='null';

$query="insert into category_ont_pro(category, category_ontology, category_property) values ($1, $2, $3)";
$result = pg_prepare($conn, "my_query", $query);

if(isset($word) && $word!=''){
  $command="python3 test_9.py " . $word;
  exec($command,$output);

  for($i=0 ;; $i++){
    if(isset($output[$i])){
      echo $output[$i];
      echo "<br>";
      $output[$i] = mb_convert_encoding($output[$i], "UTF-8", "auto");
      for($j=0; $j<$num; $j++){  //結果行数分のループ
        if($output[$i]==$category_property_already[$j] && $category==$category_already[$j]){
          $count=1;
        }
      }
      if($count==0){
          $result = pg_execute($conn, "my_query", array($category, $null, $output[$i]));
      }
      $count=0;
      echo "<br>";
    } else {
      break;
    }
  }
}

pg_close($conn);
?>

</body></html>
