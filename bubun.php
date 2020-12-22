<?php
header('Content-Type: text/html; charset=UTF-8');
?>

<?php
session_start();
if(isset($_POST["word"])){
  $word=$_POST["word"];
}

if(isset($_POST["word_2"])){
  $word_2=$_POST["word_2"];
}
?>

<html><head>
  <title>bubun</title>
</head>
<body>

<form action="bubun.php" method="post">
<?php
echo "<input type=\"search\" name=\"word\" value=$word>";
echo "<input type=\"search\" name=\"word_2\" value=$word_2>";
?>
<br>
<input type="submit" name="submit" value="検索">
</form>

<?php
$constr = "host=127.0.0.1 dbname=yuna user=yuna password=yuna";
$conn = pg_connect($constr);
$result = pg_query($conn, "SELECT * FROM data;");
$rows = pg_num_rows($result);
//ここまでがdb

$a=array();

for($num=0; $num<$rows; $num++){  //結果行数分のループ
  $rows = pg_fetch_array($result, NULL, PGSQL_ASSOC);
  $a[$num] = $rows['category'];
}

$str=$a[0];

for($i=1; $i<($num-1); $i++){
  $str .= ',' . $a[$i];
}

$query="insert into suggest_word(primary_word,suggest_word,suggest_word_category,count) values ($1,$2,$3,$4)";
$result = pg_prepare($conn, "my_query", $query);

if(isset($word) && isset($word_2)){
  $command="python3 bubun.py " . $word . " " . $word_2 . " " . $str;
  exec($command,$output);

  for($i=0 ;; $i++){
    if(isset($output[$i])){
      echo $output[$i];
      echo '<br>';
    } else {
      break;
    }
  }
}



pg_close($conn);
?>

</body></html>
