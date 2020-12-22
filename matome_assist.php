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
$category_sample_2=$_GET['category_sample_2'];
$comma_separated_sample_2=$_GET['comma_separated_sample_2'];
$category_popup=$_GET['category_popup'];
$comma_separated_popup=$_GET['comma_separated_popup'];
$php=$_GET['php'];

if($number==0){
  if($category_match!='' || $count!=''){
    $command="python3 bunkai.py \"" . $category_match."\"";
    exec($command,$category_match);
    $command="python3 bunkai.py \"" . $count."\"";
    exec($command,$count);
  }
}
if($number==1){
  if($category_match!=''){
    $command="python3 bunkai.py \"" . $category_match."\"";
    exec($command,$category_match);
  }
}

?>

<?php
  $constr = "host=127.0.0.1 dbname=yuna user=yuna password=yuna";
  $conn = pg_connect($constr);
  $result = pg_query($conn, "SELECT * FROM word_category;");
  $rows = pg_num_rows($result);

  if($number==0){
    $query="update category_type set count=$1 where category=$2 and category_match=$3";
    $result = pg_prepare($conn, "my_query", $query);#エラー出てます、重複禁止prepareは
    for($i=0;;$i++){
      if(isset($category_match[$i]) && isset($count[$i])){
        if($category_match[$i]!='' || $count[$i]!=''){
          $category_match[$i] = mb_convert_encoding($category_match[$i], "UTF-8", "auto");//ないというエラーあり、多分空白
          $result = pg_execute($conn, "my_query", array($count[$i], $category, $category_match[$i]));
        }
      }else{
        break;
      }
    }
  }
  if($number==1){
    $query="insert into category_type values ($1,$2,$3)";
    $result = pg_prepare($conn, "my_query", $query);
    for($i=0;;$i++){
      if(isset($category_match[$i])){
        $category_match[$i] = mb_convert_encoding($category_match[$i], "UTF-8", "auto");//結局？になるだけなので、エラーを消しただけで解決してない
        if($category_match[$i]!=''){
          if((strpos($category_match[$i], '?') === false) && (strpos($category_match[$i], 'wikiPage') === false)){//文字列を含まない
            $result = pg_execute($conn, "my_query", array($category,$category_match[$i],$number));
          }
        }
      }else{
        break;
      }
    }
  }

  $number+=1;

$word = str_replace(" ", "_", $word);
  if($number==1){
    header("Location: matome.php?word=".$word.'&category='.$category.'&number='.$number.'&category_sample_2='.$category_sample_2.'&comma_separated_sample_2='.$comma_separated_sample_2.'&category_popup='.$category_popup.'&comma_separated_popup='.$comma_separated_popup.'&php='.$php);
  }else{
    header("Location: matome.php?word=".$word."&number=".$number.'&category_sample_2='.$category_sample_2.'&comma_separated_sample_2='.$comma_separated_sample_2.'&category_popup='.$category_popup.'&comma_separated_popup='.$comma_separated_popup.'&php='.$php);
  }

  pg_close($conn);
?>