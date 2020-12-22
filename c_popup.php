<?php
header('Content-Type: text/html; charset=UTF-8');
?>

<?php
session_start();
if(isset($_POST["word"])){
  $word=$_POST["word"];
}else if(isset($_GET['word'])){
  $word=$_GET["word"];
}
?>

<html><head>
<link rel="stylesheet" type="text/css" href="css_test.css">
  <title>その他に当たるものを表示</title>
</head>
<body>

<form action="c_popup.php" method="post">
<?php
if(isset($word)){
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

$number_new=0;
$val = array_unique($category_match_set);
$category_match_set_new=array();

for($i=0;$i<$number;$i++){
  if(isset($val[$i])){
    $category_match_set_new[$number_new]=$val[$i];
    $number_new++;
  }
}

pg_close($conn);

if(isset($word)){
  $str = "python3 mouse_over.py " . $word;
  $re = shell_exec($str);
  $python = explode("\n", $re);

  $mouseover=array();
  $detail=array();
  for($i=0;;$i++){
    if(isset($python[$i])){
      $mouse_over = explode("::", $python[$i]);
      if(isset($mouse_over[0]) && isset($mouse_over[1])){
        $mouseover[$i]=$mouse_over[0];
        $detail[$i]=$mouse_over[1];
      }
    }else{
      break;
    }
  }
  $num_mouseover=0;
  $check_num_mouseover=0;
  $check_mouseover=0;
  $mouseover_set=array();
  $detail_set=array();
  for($i=0;;$i++){
    if(isset($mouseover[$i])){
      for($j=0;$j<$num_mouseover;$j++){
        if($mouseover[$i]==$mouseover_set[$j]){
          $check_mouseover=1;
          $check_num_mouseover=$j;
        }
      }
      if($check_mouseover==1){
        $detail_set[$check_num_mouseover]= $detail_set[$check_num_mouseover] . "、" . $detail[$i];
      }else if($check_mouseover==0){
        $mouseover_set[$num_mouseover]=$mouseover[$i];
        $detail_set[$num_mouseover]=$detail[$i];
        $num_mouseover++;
      }
      $check_mouseover=0;
    }else{
      break;
    }
  }

  echo '<div id="menu">';
  for($i=0; $i<$number; $i++){
    if(strpos($category_match_set_new[$i],'wiki') === false){
      for($j=0;;$j++){
        if(isset($mouseover_set[$j])){
          if((strpos($mouseover_set[$j], '?') === false) && (strpos($mouseover_set[$j], 'wikiPage') === false)){
            if($category_match_set_new[$i]==$mouseover_set[$j]){
              if($mouseover_set[$j]!='date' && $mouseover_set[$j]!='section' && $mouseover_set[$j]!='thumbnail' && $mouseover_set[$j]!='id' && $mouseover_set[$j]!='キャプション' && $mouseover_set[$j]!='存命人物の出典明記' && $mouseover_set[$j]!='公式サイト' && $mouseover_set[$j]!='独自研究'  && $mouseover_set[$j]!='ソートキー' && $mouseover_set[$j]!='活動期間' && $mouseover_set[$i]!='id'){
                if($mouseover_set[$j]=='Person/height' || $mouseover_set[$j]=='height'){ $mouseover_set[$j]='身長'; }
                if($mouseover_set[$j]=='abstract'){ $mouseover_set[$j]='詳細'; }
                if($mouseover_set[$j]=='birthDate'){ $mouseover_set[$j]='生年月日'; }
                if($mouseover_set[$j]=='bloodType'){ $mouseover_set[$j]='血液型'; }
                if($mouseover_set[$j]=='spouse'){ $mouseover_set[$j]='配偶者'; }
                if($mouseover_set[$j]=='notableWork'){ $mouseover_set[$j]='代表作'; }
                if($mouseover_set[$j]=='company'){ $mouseover_set[$j]='事務所'; }
                    echo '<div>';
                    echo "<span>$mouseover_set[$j]</span>";
                    echo "<p class=\"arrow_box\">$detail_set[$j]</p>";
                    echo '</div>';
              }
            }
          }
        }else{
          break;
        }
      }
    }
  }
  echo '</div>';echo '<br><br><br>';

}

?>

</body></html>