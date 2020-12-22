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
  <title>bubun</title>
</head>
<body>

<form action="b_popup.php" method="post">
<?php
echo "<input type=\"search\" name=\"word\" value=$word>";
?>
<input type="submit" name="submit" value="検索">
</form>

<?php
$constr = "host=127.0.0.1 dbname=yuna user=yuna password=yuna";
$conn = pg_connect($constr);
$result = pg_query($conn, "SELECT * FROM cover_category;");
$rows = pg_num_rows($result);
//ここまでがdb

$str = "python3 test8.py " . $word;
$re = shell_exec($str);
$output = explode("\n", $re);
$output_2 = implode(',', $output);

$str = "python3 csv_test.py " . $output_2;
$re = shell_exec($str);
$output_change = explode("\n", $re);

$category=array();
$category_match=array();
$count=array();
$num=0;

for($i=0; $i<$rows; $i++){
  $rows = pg_fetch_array($result, NULL, PGSQL_ASSOC);
  array_push($category,$rows['category']);
  array_push($category_match,$rows['category_match']);
  array_push($count,$rows['count']);
  $num+=1;
}

$category_set=array();
$category_match_set=array();
$count_set=array();
$number=0;

for($i=0; $i<$num; $i++){
  if(strpos($category[$i],$output_change[0]) !== false){
    $category_set[$number]=$category[$i];
    $category_match_set[$number]=$category_match[$i];
    $count_set[$number]=$count[$i];
    $number++;
  }
}

for($i=0; $i<($number-1); $i++){
  for($j=($number-1); $j>$i; $j--){
    if($count_set[$j-1]<$count_set[$j]){
      $temp = $count_set[$j-1];
      $count_set[$j-1] = $count_set[$j];
      $count_set[$j] = $temp;

      $str = $category_set[$j-1];
      $category_set[$j-1] = $category_set[$j];
      $category_set[$j] = $str;

      $str = $category_match_set[$j-1];
      $category_match_set[$j-1] = $category_match_set[$j];
      $category_match_set[$j] = $str;
    }
  }
}

$val = array_unique($category_match_set);
$number_new=0;

for($i=0;$i<$number;$i++){
  if(isset($val[$i])){
    $category_match_set[$number_new]=$val[$i];
    $number_new++;
  }
}

for($i=0; $i<$number_new; $i++){
  if(strpos($category_match_set[$i],'wiki') === false){
    echo $category_match_set[$i];echo '<br>';
  }
}

pg_close($conn);
#多い順に表示
?>

</body></html>
