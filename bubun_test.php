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
  <title>bubun_test</title>
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
$result = pg_query($conn, "SELECT * FROM category_ont_pro;");
$rows = pg_num_rows($result);
//ここまでがdb

$category_property_already=array();
$category_ontology_already=array();
$category_already=array();

for($num=0; $num<$rows; $num++){  //結果行数分のループ
  $rows = pg_fetch_array($result, NULL, PGSQL_ASSOC);
  $category_ontology_already[$num] = $rows['category_ontology'];
  $category_property_already[$num] = $rows['category_property'];
  $category_already[$num] = $rows['category'];
}

$array_ontology=array();
$array_property=array();
$num_ontology=0;
$num_property=0;

for($i=0;$i<$num;$i++){
  if($category_ontology_already[$i]!='null'){
    $array_ontology[$num_ontology] = $category_ontology_already[$i];
    $num_ontology++;
  }
  if($category_property_already[$i]!='null'){
    $array_property[$num_property] = $category_property_already[$i];
    $num_property++;
  }
}

$str_ontology=implode(',', $array_ontology);
$str_property=implode(',', $array_property);


if(isset($word) && isset($word_2)){
  $command="python3 bubun.py " . $word . " " . $word_2 . " " . $str_property;
  exec($command,$output);

  for($i=0 ;; $i++){
    if(isset($output[$i])){
      echo $output[$i];
      echo '<br>';
    } else {
      break;
    }
  }

  $command="python3 bubun_ont.py " . $word . " " . $word_2 . " " . $str_ontology;
  exec($command,$output_ont);

  for($i=0 ;; $i++){
    if(isset($output_ont[$i])){
      echo $output_ont[$i];
      echo '<br>';
    } else {
      break;
    }
  }
}


pg_close($conn);

?>

</body></html>
