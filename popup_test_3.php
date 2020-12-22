<?php
header('Content-Type: text/html; charset=UTF-8');
?>

<?php
session_start();
if(isset($_POST["word"])){
  if($_POST["word"]==''){
    $word='入力してください';
  }else{
    $word=$_POST["word"];
  }
}else{
  $word='入力してください';
}

if(isset($_POST["category"])){
  $category=$_POST["category"];
}else{
  $category="Thing";
}

?>

<html><head>
  <title>その他に当たるものを表示</title>
</head>
<body>

<form action="popup_test_3.php" method="post">
<?php
if(isset($_POST["word"])){
  echo "<input type=\"search\" name=\"word\" value=$word>";
  echo "と完全一致の単語";
}else{
 echo "<input type=\"search\" name=\"word\">";
 echo "と完全一致の単語";
}
?>
<br>
<input type="submit" name="submit" value="検索">
</form>
<?php



if(isset($_POST["word"])){
  if($word != "入力してください"){
    $command="python3 test_7.py " . $word;
    exec($command,$python);

    $command="python3 test8.py " . $word;
    exec($command,$python_2);
    $comma_separated = implode(",", $python_2);

    $command="python3 test_4.py " . $comma_separated . " Thing";
    exec($command,$python_3);
  }
}

$constr = "host=127.0.0.1 dbname=yuna user=yuna password=yuna";
$conn = pg_connect($constr);
$result = pg_query($conn, "SELECT * FROM category_type;");
$rows = pg_num_rows($result);

$num=0;
$category_new=array();
$category_match_new=array();
$count_new=array();
for($j=0; $j<$rows; $j++){  //結果行数分のループ
  $rows = pg_fetch_array($result, NULL, PGSQL_ASSOC);
  $category_new[$j]=$rows['category'];
  $category_match_new[$j]=$rows['category_match'];
  $count_new[$j]=$rows['count'];
  $num++;
}

$check = 'No';

  for($i=0;;$i++){
    if(isset($python[$i])){
      for($j=0; $j<$num; $j++){  //結果行数分のループ
        if($python_3[0]==$category_new[$j] && $python[$i]==$category_match_new[$j]){
          $count = $count_new[$j]+1;
          $query="update category_type set count=$1 where category=$2 and category_match=$3";
          $result = pg_prepare($conn, "my_query", $query);
          $result = pg_execute($conn, "my_query", array($count, $python_3[0], $python[$i]));
          $check = 'Yes';
        }
      }
      if($check=='No'){
        $count=1;
        $query="insert into category_type values ($1,$2,$3)";
        $result = pg_prepare($conn, "my_query", $query);#エラー出てます、重複禁止prepareは
        $result = pg_execute($conn, "my_query", array($python_3[0],$python[$i],$count));
      }
      $check='No';
    }else{
      break;
    }
  }

pg_close($conn);

?>

</body></html>
