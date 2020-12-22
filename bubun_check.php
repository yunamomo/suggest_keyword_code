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
  <title>sample_2</title>
</head>
<body>

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

echo $str;

pg_close($conn);

//ここから別

echo '<br><br>';

$constr = "host=127.0.0.1 dbname=yuna user=yuna password=yuna";
$conn = pg_connect($constr);
$result = pg_query($conn, "SELECT * FROM category_type;");
$rows = pg_num_rows($result);
//ここまでがdb

$a=array();

for($num=0; $num<$rows; $num++){  //結果行数分のループ
  $rows = pg_fetch_array($result, NULL, PGSQL_ASSOC);
  $a[$num] = $rows['category_match'];
}

$str=$a[0];

for($i=1; $i<($num-1); $i++){
  $str .= ',' . $a[$i];
}

echo $str;

pg_close($conn);


?>

</body></html>
