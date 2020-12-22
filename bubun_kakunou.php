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
  <title>bubun_kakunou</title>
</head>
<body>

<form action="bubun_kakunou.php" method="post">
<?php
if(isset($word)){
  echo "<input type=\"search\" name=\"word\" value=$word>";
}else{
  echo "<input type=\"search\" name=\"word\">";
}
if(isset($word_2)){
  echo "<input type=\"search\" name=\"word_2\" value=$word_2>";
}else{
  echo "<input type=\"search\" name=\"word_2\">";
}
?>
<br>
<input type="submit" name="submit" value="検索">
</form>

<?php
$constr = "host=127.0.0.1 dbname=yuna user=yuna password=yuna";
$conn = pg_connect($constr);
$result_suggest_word = pg_query($conn, "SELECT * FROM suggest_word;");
$rows_suggest_word = pg_num_rows($result_suggest_word);
//ここまでがdb

$primary_word_already=array();
$suggest_word_already=array();
$count_already=array();
$num_suggest_word=0;

for($j=0; $j<$rows_suggest_word; $j++){  //結果行数分のループ
  $rows_suggest_word = pg_fetch_array($result_suggest_word, NULL, PGSQL_ASSOC);
  $primary_word_already[$j]=$rows_suggest_word['primary_word'];
  $suggest_word_already[$j]=$rows_suggest_word['suggest_word'];
  $count_already[$j]=$rows_suggest_word['count'];
  $num_suggest_word++;
}

if(isset($word) && isset($word_2)){
if($word!='' && $word_2!=''){
  for($i=0; $i<$num_suggest_word; $i++){
    if($word==$primary_word_already[$i] && $word_2==$suggest_word_already[$i]){
      $count_new = $count_already[$i]+1;
      $query="update suggest_word set count=$1 where primary_word=$2 and suggest_word=$3";
      $result = pg_prepare($conn, "my_query", $query);
      $result = pg_execute($conn, "my_query", array($count_new, $word, $word_2));
      $url = 'https://www.google.com/search?q='.$word.'+'.$word_2;
      header("Location:$url");
      exit;
    }
  }

  $query="insert into suggest_word(primary_word,suggest_word,count) values ($1,$2,$3)";
  $result = pg_prepare($conn, "my_query", $query);
  $count=1;
  $result = pg_execute($conn, "my_query", array($word, $word_2, $count));
  $url = 'https://www.google.com/search?q='.$word.'+'.$word_2;
  header("Location:$url");
  exit;
}
}

pg_close($conn);
?>

</body></html>
