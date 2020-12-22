<?php
header('Content-Type: text/html; charset=UTF-8');
?>

<?php
session_start();
if(isset($_POST["word"])){
  $word=$_POST["word"];
}
if(isset($_POST["category_sample_2"])){
  $category_sample_2=$_POST["category_sample_2"];
}else{
  $category_sample_2="Thing";
}
if(isset($_POST["comma_separated_sample_2"])){
  $comma_separated_sample_2 = $_POST["comma_separated_sample_2"];
}
?>

<html><head>
  <title>sample_2</title>
</head>
<body>

<?php
echo '<form action="sample_2.php" method="post">';
if(isset($word)){
  echo "<input type=\"search\" name=\"word\" value=$word>";
}else{
  echo "<input type=\"search\" name=\"word\">";
}
?>
<input type="submit" name="submit" value="検索">
</form>

<?php
$constr = "host=127.0.0.1 dbname=yuna user=yuna password=yuna";
$conn = pg_connect($constr);
$result_word_category = pg_query($conn, "SELECT * FROM word_category;");
$rows_word_category = pg_num_rows($result_word_category);

$word_sample_2=array();
$category_sample_2=array();
$num_sample_2=0;
if(isset($word)){
  for($i=0; $i<$rows_word_category; $i++){
    $rows_word_category = pg_fetch_array($result_word_category, NULL, PGSQL_ASSOC);
    if(strpos($rows_word_category['word'], $word) !== false){//文字列を含むか
      array_push($word_sample_2,$rows_word_category['word']);
      array_push($category_sample_2,$rows_word_category['category']);
      $num_sample_2+=1;
    }
  }

  $unique_sample_2 = array_unique($word_sample_2);
  $alignedUnique_sample_2 = array_values($unique_sample_2);
  $unique_category_sample_2 = array_unique($category_sample_2);
  $alignedUnique_category_sample_2 = array_values($unique_category_sample_2);

  echo 'word_category :'; echo $word;
  echo '<br>';echo '<br>';

  for($i=0;;$i++){
    if(isset($alignedUnique_sample_2[$i])){
      echo $alignedUnique_sample_2[$i];
      echo '<br>';
    }else{
      break;
    }
  }

  #for($i=0;;$i++){
  #  if(isset($alignedUnique_category_sample_2[$i])){
  #    echo $alignedUnique_category_sample_2[$i];
  #    echo '<br>';
  #  }else{
  #    break;
  #  }
  #}
  echo '<br>';
}
//ここまでがdb

$result_category_type = pg_query($conn, "SELECT * FROM category_type;");
$rows_category_type = pg_num_rows($result_category_type);

  echo 'category_type :'; echo $word;
  echo '<br>';echo '<br>';

$category_sample_2=array();
$category_match_sample_2=array();
$count_sample_2=array();
$num_sample_2=0;
if(isset($word)){
  for($i=0; $i<$rows_category_type; $i++){
    $rows_category_type = pg_fetch_array($result_category_type, NULL, PGSQL_ASSOC);
      array_push($category_sample_2,$rows_category_type['category']);
      array_push($category_match_sample_2,$rows_category_type['category_match']);
      array_push($count_sample_2,$rows_category_type['count']);
      $num_sample_2+=1;
  }

  $unique_sample_2 = array_unique($category_sample_2);
  $aligned_sample_2 = array_values($unique_sample_2);
  $unique_match_sample_2 = array_unique($category_match_sample_2);
  $aligned_match_sample_2 = array_values($unique_match_sample_2);
  $unique_count_sample_2 = array_unique($count_sample_2);
  $aligned_count_sample_2 = array_values($unique_sample_2);

  for($i=0;;$i++){
    if(isset($alignedUnique_category_sample_2[$i])){
      for($j=0;;$j++){
        if(isset($category_sample_2[$j])){
          if($category_sample_2[$j]==$alignedUnique_category_sample_2[$i]){
            echo $category_match_sample_2[$j];
            echo '<br>';
          }
        }else{
          break;
        }
      }
    }else{
      break;
    }
  }
  echo '<br>';
}



$result_suggest_word = pg_query($conn, "SELECT * FROM suggest_word;");
$rows_suggest_word = pg_num_rows($result_suggest_word);


$word_sample_2=array();
$category_sample_2=array();
$num_sample_2=0;
if(isset($word)){
  for($i=0; $i<$rows_suggest_word; $i++){
    $rows_suggest_word = pg_fetch_array($result_suggest_word, NULL, PGSQL_ASSOC);
    if((strpos($rows_suggest_word['primary_word'], $word) !== false) || (strpos($word, $rows_suggest_word['primary_word']) !== false) ){//文字列を含むか
      array_push($word_sample_2,$rows_suggest_word['primary_word']);
      array_push($category_sample_2,$rows_suggest_word['suggest_word_category']);
      $num_sample_2+=1;
    }
  }

  $unique_sample_2 = array_unique($word_sample_2);
  $alignedUnique_sample_2 = array_values($unique_sample_2);
  $unique_category_sample_2 = array_unique($category_sample_2);
  $alignedUnique_category_sample_2 = array_values($unique_category_sample_2);

  echo 'suggest_word :'; echo $word;
  echo '<br>';echo '<br>';

  for($i=0;;$i++){
    if(isset($alignedUnique_sample_2[$i])){
      echo $alignedUnique_sample_2[$i];
      echo '<br>';
    }else{
      break;
    }
  }

  echo '<br>';

  for($i=0;;$i++){
    if(isset($alignedUnique_category_sample_2[$i])){
      echo $alignedUnique_category_sample_2[$i];
      echo '<br>';
    }else{
      break;
    }
  }

}


pg_close($conn);
?>

</body></html>
