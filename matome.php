<?php
header('Content-Type: text/html; charset=UTF-8');
?>

<html><head>
 <link rel="stylesheet" type="text/css" href="css_test.css">
  <title>まとめ</title>
  <style type="text/css">

div.blocka {
/* 使えません */
}
div.green {
   height: auto;
   min-height: 400%;
   float: left;
   width: 25%;
   background-color: #AAFFCC;
}
div.blue {
   height: auto;
   min-height: 400%;
   float: left;
   width: 25%;
   background-color: #AACCFF;
}
div.yellow{
   height: auto;
   min-height: 400%;
   float: left;
   width: 25%;
   background-color: #FFFFBB;
}
div.pink {
   height: auto;
   min-height: 400%;
   float: left;
   width: 25%;
   background-color: #FFDDDD;
}

#menu div {
  position: relative;
}

.arrow_box {
  display: none;
  position: relative;
  padding: 8px;
  -webkit-border-radius: 8px;
  -moz-border-radius: 8px;
  border-radius: 0px;
  background: #000;
  color: #ff0;
}

.arrow_box:after {
  position: absolute;
  bottom: 100%;
  left: 10%;
  width: 0;
  height: 0;
  margin-left: -10px;
  border: solid transparent;
  border-color: rgba(51, 51, 51, 0);
  border-bottom-color: #000;
  border-width: 10px;
  pointer-events: none;
  content: " ";
}

span:hover + p.arrow_box {
  display: block;
}

</style>
</head>
<body>

<?php
if(isset($_POST["word"])){
    $word=$_POST["word"];
}

if(isset($_POST["word_2"])){
  $word_2=$_POST["word_2"];
}

if(isset($_POST["comma_separated_sample_2"])){
  $comma_separated_sample_2 = $_POST["comma_separated_sample_2"];
}else{
  $comma_separated_sample_2 = '';
}

if(isset($_POST["category_popup"])){
  $category_popup=$_POST["category_popup"];
}else{
  $category_popup="Thing";
}
if(isset($_POST["comma_separated_popup"])){
  $comma_separated_popup = $_POST["comma_separated_popup"];
}else{
  $comma_separated_popup = '';
}
if(isset($_POST["php"])){
  $php = $_POST["php"];
}else{
  $php = 'DB';
}
if(isset($_POST["search"])){
  $search = $_POST["search"];
}else{
  $search = 'DBpedia';
}
?>


<?php
echo '<form action="matome.php" method="post">';
$word = str_replace(" ", "_", $word);
if(isset($word)){
  echo "<input type=\"search\" name=\"word\" style=\"width:20em;height:2em\" value=$word>";
  $word = str_replace("_", " ", $word);
}else{
  echo "<input type=\"search\" name=\"word\" style=\"width:20em;height:2em\">";
}
if(isset($word_2)){
  echo "<input type=\"search\" name=\"word_2\" style=\"width:20em;height:2em\" value=$word_2>";
}else{
  echo "<input type=\"search\" name=\"word_2\" style=\"width:20em;height:2em\">";
}
echo '<input type="submit" name="submit" value="検索">';
echo '</form>';
?>

<?php
$constr = "host=127.0.0.1 dbname=yuna user=yuna password=yuna";
$conn = pg_connect($constr);
$result_suggest_word = pg_query($conn, "SELECT * FROM suggest_word;");
$rows_suggest_word = pg_num_rows($result_suggest_word);

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


echo '<div class="pink">';

echo 'DB内の単語を取得';echo '<br><br>';

$suggest_sample_2=array();
$count_suggest=array();
#$count_already[$j]
$num=0;

if(isset($word)){
  for($i=0; $i<$num_suggest_word; $i++){
    $rows_suggest_word = pg_fetch_array($result_suggest_word, NULL, PGSQL_ASSOC);
    if((strpos($primary_word_already[$i], $word) !== false)){//文字列を含むか
      if($word==$primary_word_already[$i]){
        if($word==$suggest_word_already[$i]){
        }else{
          $suggest_sample_2[$num]=$suggest_word_already[$i];
          $count_suggest[$num]=$count_already[$i];
          $num++;
        }
      }else{
        $suggest_sample_2[$num]=$suggest_word_already[$i];
        $count_suggest[$num]=$count_already[$i];
        $num++;
      }
    }
  }

for($i=0; $i<($num-1); $i++){
  for($j=($num-1); $j>$i; $j--){
    if($count_suggest[$j-1]<$count_suggest[$j]){
      $temp = $count_suggest[$j-1];
      $count_suggest[$j-1] = $count_suggest[$j];
      $count_suggest[$j] = $temp;

      $str = $suggest_sample_2[$j-1];
      $suggest_sample_2[$j-1] = $suggest_sample_2[$j];
      $suggest_sample_2[$j] = $str;
    }
  }
}

  for($i=0;;$i++){
    if(isset($suggest_sample_2[$i])){
      $form="form4_" . $i;
      $java="javascript:" . $form . ".submit()";
      echo "<form style=\"display: inline\" action=\"sample_4.php\" method=\"post\" name=$form>";
      echo "<input type=\"hidden\" name=\"word\" value=$word>";
      echo "<input type=\"hidden\" name=\"word_2\" value=$suggest_sample_2[$i]>";
      echo "<a href=$java>$suggest_sample_2[$i]</a>";
      echo "<br>";
      echo "</form>";
    }else{
      break;
    }
  }
}
?>
</div>


<div class="yellow">
<?php
echo '述語取得(完全一致のみ)';
#popup_test_3
echo '<form action="matome.php" method="post">';
echo "<input type=\"hidden\" name=\"php\" value='picup'>";
echo "<input type=\"hidden\" name=\"comma_separated_sample_2\" value=$comma_separated_sample_2>";
echo "<input type=\"hidden\" name=\"comma_separated_popup\" value=$comma_separated_popup>";
$word = str_replace(" ", "_", $word);
echo "<input type=\"hidden\" name=\"word\" value=$word>";
$word = str_replace("_", " ", $word);
if(isset($word_2)){
  echo "<input type=\"hidden\" name=\"word_2\" value=$word_2>";
}
echo "<input type=\"hidden\" name=\"search\" value=\"Google\">";
echo '<input type="submit" name="submit" value="Google">';
echo '</form>';

echo '<form action="matome.php" method="post">';
echo "<input type=\"hidden\" name=\"php\" value='picup'>";
echo "<input type=\"hidden\" name=\"comma_separated_sample_2\" value=$comma_separated_sample_2>";
echo "<input type=\"hidden\" name=\"comma_separated_popup\" value=$comma_separated_popup>";
$word = str_replace(" ", "_", $word);
echo "<input type=\"hidden\" name=\"word\" value=$word>";
$word = str_replace("_", " ", $word);
if(isset($word_2)){
  echo "<input type=\"hidden\" name=\"word_2\" value=$word_2>";
}
echo "<input type=\"hidden\" name=\"search\" value=\"DBpedia\">";
echo '<input type="submit" name="submit" value="DBpedia">';
echo '</form>';

if(isset($word)){
  if($word != ""){
    $word = str_replace("_", " ", $word);
    $str = "python3 mouse_over.py \"" . $word . "\"";
    $re = shell_exec($str);
    $python = explode("\n", $re);
    $word = str_replace(" ", "_", $word);
  }

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
        if(strpos($detail_set[$check_num_mouseover], $detail[$i]) !== false){
        }else if(strpos($detail[$i], $detail_set[$check_num_mouseover]) !== false){
          $detail_set[$check_num_mouseover]=$detail[$i];
        }else{
          $detail_set[$check_num_mouseover]=$detail_set[$check_num_mouseover] . "、" . $detail[$i];
        }
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

    $mouse_over_decision=array();
    $detail_set_decision=array();
    $num_mouseover=0;
    $check_num_mouseover=0;
    $check_mouseover=0;

    for($i=0;;$i++){
      if(isset($mouseover_set[$i])){
        if((strpos($mouseover_set[$i], '?') === false) && (strpos($mouseover_set[$i], 'wikiPage') === false) && (strpos($mouseover_set[$i], '画像') === false) && (strpos($mouseover_set[$i], 'image') === false) && (strpos($mouseover_set[$i], 'activeYears') === false) && (strpos($mouseover_set[$i], '写真') === false) && (strpos($mouseover_set[$i], '更新日') === false) && (strpos($mouseover_set[$i], '表記') === false)){
        if($mouseover_set[$i]!='date' && $mouseover_set[$i]!='section' && $mouseover_set[$i]!='thumbnail' && $mouseover_set[$i]!='id' && $mouseover_set[$i]!='キャプション' && $mouseover_set[$i]!='存命人物の出典明記' && $mouseover_set[$i]!='公式サイト' && $mouseover_set[$i]!='独自研究'  && $mouseover_set[$i]!='ソートキー' && $mouseover_set[$i]!='活動期間' && $mouseover_set[$i]!='名前' && $mouseover_set[$i]!='芸名' && $mouseover_set[$i]!='title' && $mouseover_set[$i]!='name' && $mouseover_set[$i]!='活動時期'){
        if($mouseover_set[$i]!='雑多な内容の箇条書き' && $mouseover_set[$i]!='民族' && $mouseover_set[$i]!='活動内容' && $mouseover_set[$i]!='人名' && $mouseover_set[$i]!='就任日' && $mouseover_set[$i]!='国旗' && $mouseover_set[$i]!='各国語表記' && $mouseover_set[$i]!='退任日'  && $mouseover_set[$i]!='当選回数' && $mouseover_set[$i]!='before' && $mouseover_set[$i]!='after' && $mouseover_set[$i]!='titlenote' && $mouseover_set[$i]!='direction' && $mouseover_set[$i]!='titlestyle' && $mouseover_set[$i]!='前代'){
        if($mouseover_set[$i]!='当代' && $mouseover_set[$i]!='次代' && $mouseover_set[$i]!='alias' && $mouseover_set[$i]!='ラテン文字' && $mouseover_set[$i]!='showMedals' && $mouseover_set[$i]!='listclass' && $mouseover_set[$i]!='text' && $mouseover_set[$i]!='議論ページ'  && $mouseover_set[$i]!='サイン' && $mouseover_set[$i]!='元首' && $mouseover_set[$i]!='元首職' && $mouseover_set[$i]!='wikify' && $mouseover_set[$i]!='period' && $mouseover_set[$i]!='commonName' && $mouseover_set[$i]!='caption'){
        if($mouseover_set[$i]!='年' && $mouseover_set[$i]!='特筆性' && $mouseover_set[$i]!='活動備考' && $mouseover_set[$i]!='時点' && $mouseover_set[$i]!='モデル名' && $mouseover_set[$i]!='listclass' && $mouseover_set[$i]!='text' && $mouseover_set[$i]!='議論ページ'  && $mouseover_set[$i]!='サイン' && $mouseover_set[$i]!='元首' && $mouseover_set[$i]!='元首職' && $mouseover_set[$i]!='wikify' && $mouseover_set[$i]!='period' && $mouseover_set[$i]!='commonName' && $mouseover_set[$i]!='caption'){
          if($mouseover_set[$i]=='Person/height' || $mouseover_set[$i]=='height'){ $mouseover_set[$i]='身長'; }
          if($mouseover_set[$i]=='Person/weight' || $mouseover_set[$i]=='weight'){ $mouseover_set[$i]='体重'; }
          if($mouseover_set[$i]=='abstract'){ $mouseover_set[$i]='詳細'; }
          if($mouseover_set[$i]=='birthDate' || $mouseover_set[$i]=='出生' || $mouseover_set[$i]=='born'){ $mouseover_set[$i]='生年月日'; }
          if($mouseover_set[$i]=='bloodType'){ $mouseover_set[$i]='血液型'; }
          if($mouseover_set[$i]=='spouse'){ $mouseover_set[$i]='配偶者'; }
          if($mouseover_set[$i]=='notableWork'){ $mouseover_set[$i]='代表作'; }
          if($mouseover_set[$i]=='birthYear'){ $mouseover_set[$i]='生年'; }
          if($mouseover_set[$i]=='activeYearsStartYear'){ $mouseover_set[$i]='活動開始年'; }
          if($mouseover_set[$i]=='almaMater' || $mouseover_set[$i]=='school' || $mouseover_set[$i]=='schoolBackground'){ $mouseover_set[$i]='出身校'; }
          if($mouseover_set[$i]=='affiliation'){ $mouseover_set[$i]='所属'; }
          if($mouseover_set[$i]=='nationality'){ $mouseover_set[$i]='国籍'; }
          if($mouseover_set[$i]=='deathYear'){ $mouseover_set[$i]='没年'; }
          if($mouseover_set[$i]=='deathDate'){ $mouseover_set[$i]='没年月日'; }
          if($mouseover_set[$i]=='deathPlace'){ $mouseover_set[$i]='死没地'; }
          if($mouseover_set[$i]=='occupation'){ $mouseover_set[$i]='職業'; }
          if($mouseover_set[$i]=='party'){ $mouseover_set[$i]='所属政党'; }
          if($mouseover_set[$i]=='predecessor'){ $mouseover_set[$i]='前任者'; }
          if($mouseover_set[$i]=='region'){ $mouseover_set[$i]='選挙区'; }
          if($mouseover_set[$i]=='genre'){ $mouseover_set[$i]='ジャンル'; }
          if($mouseover_set[$i]=='hometown'){ $mouseover_set[$i]='出身'; }
          if($mouseover_set[$i]=='measurements'){ $mouseover_set[$i]='カップ数'; }
          if($mouseover_set[$i]=='employer'){ $mouseover_set[$i]='専属契約'; }
          if($mouseover_set[$i]=='choreographer'){ $mouseover_set[$i]='振付師'; }
          if($mouseover_set[$i]=='coach'){ $mouseover_set[$i]='コーチ'; }
          if($mouseover_set[$i]=='country'){ $mouseover_set[$i]='国籍'; }
          if($mouseover_set[$i]=='formerChoreographer'){ $mouseover_set[$i]='前振付師'; }
          if($mouseover_set[$i]=='formerCoach'){ $mouseover_set[$i]='前コーチ'; }
          if($mouseover_set[$i]=='debutWorks'){ $mouseover_set[$i]='デビュー作'; }
          if($mouseover_set[$i]=='subject'){ $mouseover_set[$i]='主題'; }
          if($mouseover_set[$i]=='relations'){ $mouseover_set[$i]='親族'; }
          if($mouseover_set[$i]=='children' || $mouseover_set[$i]=='child' || $mouseover_set[$i]=='parent' || $mouseover_set[$i]=='relative'){ $mouseover_set[$i]='親族'; }
          if($mouseover_set[$i]=='出生地' || $mouseover_set[$i]=='出身地'){ $mouseover_set[$i]='出身'; }
          if($mouseover_set[$i]=='number'){ $mouseover_set[$i]='背番号'; }
          if($mouseover_set[$i]=='battingSide'){ $mouseover_set[$i]='打席'; }
          if($mouseover_set[$i]=='throwingSide'){ $mouseover_set[$i]='投球'; }
          if($mouseover_set[$i]=='position'){ $mouseover_set[$i]='ポジション'; }
          if($mouseover_set[$i]=='birthPlace' || $mouseover_set[$i]=='origin'){ $mouseover_set[$i]='出身'; }
          if($mouseover_set[$i]=='company'){ $mouseover_set[$i]='事務所'; }
          if($mouseover_set[$i]=='description'){ $mouseover_set[$i]='他の活動'; }
          for($j=0;$j<$num_mouseover;$j++){
            if($mouseover_set[$i]==$mouseover_set_decision[$j]){
               $check_mouseover=1;
               $check_num_mouseover=$j;
            }
          }
          if($check_mouseover==1){
            if(strpos($detail_set_decision[$check_num_mouseover], $detail_set[$i]) !== false){
            }else if(strpos($detail_set[$i], $detail_set_decision[$check_num_mouseover]) !== false){
              $detail_set_decision[$check_num_mouseover]=$detail_set[$i];
            }else{
              $detail_set_decision[$check_num_mouseover]= $detail_set_decision[$check_num_mouseover] . "、" . $detail_set[$i];
            }
          }else if($check_mouseover==0){
               $mouseover_set_decision[$num_mouseover]=$mouseover_set[$i];
               $detail_set_decision[$num_mouseover]=$detail_set[$i];
               $num_mouseover++;
          }
          $check_mouseover=0;
        }}}}}
      }else{
        break;
      }
    }

  if($search=='Google'){
    echo 'Google';echo '<br><br>';
    for($i=0;;$i++){
      if(isset($mouseover_set_decision[$i])){
        if((strpos($mouseover_set_decision[$i], '?') === false) && (strpos($mouseover_set_decision[$i], 'wikiPage') === false) && (strpos($mouseover_set_decision[$i], '画像') === false) && (strpos($mouseover_set_decision[$i], 'image') === false) && (strpos($mouseover_set_decision[$i], 'activeYears') === false) && (strpos($mouseover_set_decision[$i], '写真') === false) && (strpos($mouseover_set_decision[$i], '更新日') === false) && (strpos($mouseover_set_decision[$i], '表記') === false)){
        if($mouseover_set_decision[$i]!='date' && $mouseover_set_decision[$i]!='section' && $mouseover_set_decision[$i]!='thumbnail' && $mouseover_set_decision[$i]!='id' && $mouseover_set_decision[$i]!='キャプション' && $mouseover_set_decision[$i]!='存命人物の出典明記' && $mouseover_set_decision[$i]!='公式サイト' && $mouseover_set_decision[$i]!='独自研究'  && $mouseover_set_decision[$i]!='ソートキー' && $mouseover_set_decision[$i]!='活動期間' && $mouseover_set_decision[$i]!='名前' && $mouseover_set_decision[$i]!='芸名' && $mouseover_set_decision[$i]!='title' && $mouseover_set_decision[$i]!='name' && $mouseover_set_decision[$i]!='活動時期'){
        if($mouseover_set_decision[$i]!='雑多な内容の箇条書き' && $mouseover_set_decision[$i]!='民族' && $mouseover_set_decision[$i]!='活動内容' && $mouseover_set_decision[$i]!='人名' && $mouseover_set_decision[$i]!='就任日' && $mouseover_set_decision[$i]!='国旗' && $mouseover_set_decision[$i]!='各国語表記' && $mouseover_set_decision[$i]!='退任日'  && $mouseover_set_decision[$i]!='当選回数' && $mouseover_set_decision[$i]!='before' && $mouseover_set_decision[$i]!='after' && $mouseover_set_decision[$i]!='titlenote' && $mouseover_set_decision[$i]!='direction' && $mouseover_set_decision[$i]!='titlestyle' && $mouseover_set_decision[$i]!='前代'){
        if($mouseover_set_decision[$i]!='当代' && $mouseover_set_decision[$i]!='次代' && $mouseover_set_decision[$i]!='alias' && $mouseover_set_decision[$i]!='ラテン文字' && $mouseover_set_decision[$i]!='showMedals' && $mouseover_set_decision[$i]!='listclass' && $mouseover_set_decision[$i]!='text' && $mouseover_set_decision[$i]!='議論ページ'  && $mouseover_set_decision[$i]!='サイン' && $mouseover_set_decision[$i]!='元首' && $mouseover_set_decision[$i]!='元首職' && $mouseover_set_decision[$i]!='wikify' && $mouseover_set_decision[$i]!='period' && $mouseover_set_decision[$i]!='commonName' && $mouseover_set_decision[$i]!='caption'){
        if($mouseover_set_decision[$i]!='年' && $mouseover_set_decision[$i]!='特筆性' && $mouseover_set_decision[$i]!='活動備考' && $mouseover_set_decision[$i]!='時点' && $mouseover_set_decision[$i]!='モデル名' && $mouseover_set_decision[$i]!='listclass' && $mouseover_set_decision[$i]!='text' && $mouseover_set_decision[$i]!='議論ページ'  && $mouseover_set_decision[$i]!='サイン' && $mouseover_set_decision[$i]!='元首' && $mouseover_set_decision[$i]!='元首職' && $mouseover_set_decision[$i]!='wikify' && $mouseover_set_decision[$i]!='period' && $mouseover_set_decision[$i]!='commonName' && $mouseover_set_decision[$i]!='caption'){
          $form="form2_" . $i;
          $java="javascript:" . $form . ".submit()";
          echo "<form style=\"display: inline\" action=\"sample_4.php\" method=\"post\" name=$form>";
          echo "<input type=\"hidden\" name=\"word\" value=$word>";
          echo "<input type=\"hidden\" name=\"word_2\" value=$mouseover_set_decision[$i]>";
          echo "<a href=$java>$mouseover_set_decision[$i]</a>";
          echo "<br>";
          echo "</form>";
        }}}}}
      }else{
        break;
      }
    }
  }

  if($search=='DBpedia'){
    echo 'DBpedia';echo '<br><br>';
    echo '<div id="menu">';

    for($i=0;;$i++){
    if(isset($mouseover_set_decision[$i])){
      if((strpos($mouseover_set_decision[$i], '?') === false) && (strpos($mouseover_set_decision[$i], 'wikiPage') === false) && (strpos($mouseover_set_decision[$i], '画像') === false) && (strpos($mouseover_set_decision[$i], 'image') === false) && (strpos($mouseover_set_decision[$i], 'activeYears') === false) && (strpos($mouseover_set_decision[$i], '写真') === false) && (strpos($mouseover_set_decision[$i], '更新日') === false) && (strpos($mouseover_set_decision[$i], '表記') === false)){
        if($mouseover_set_decision[$i]!='date' && $mouseover_set_decision[$i]!='section' && $mouseover_set_decision[$i]!='thumbnail' && $mouseover_set_decision[$i]!='id' && $mouseover_set_decision[$i]!='キャプション' && $mouseover_set_decision[$i]!='存命人物の出典明記' && $mouseover_set_decision[$i]!='公式サイト' && $mouseover_set_decision[$i]!='独自研究'  && $mouseover_set_decision[$i]!='ソートキー' && $mouseover_set_decision[$i]!='活動期間' && $mouseover_set_decision[$i]!='名前' && $mouseover_set_decision[$i]!='芸名' && $mouseover_set_decision[$i]!='title' && $mouseover_set_decision[$i]!='name' && $mouseover_set_decision[$i]!='活動時期'){
        if($mouseover_set_decision[$i]!='雑多な内容の箇条書き' && $mouseover_set_decision[$i]!='民族' && $mouseover_set_decision[$i]!='活動内容' && $mouseover_set_decision[$i]!='人名' && $mouseover_set_decision[$i]!='就任日' && $mouseover_set_decision[$i]!='国旗' && $mouseover_set_decision[$i]!='各国語表記' && $mouseover_set_decision[$i]!='退任日'  && $mouseover_set_decision[$i]!='当選回数' && $mouseover_set_decision[$i]!='before' && $mouseover_set_decision[$i]!='after' && $mouseover_set_decision[$i]!='titlenote' && $mouseover_set_decision[$i]!='direction' && $mouseover_set_decision[$i]!='titlestyle' && $mouseover_set_decision[$i]!='前代'){
        if($mouseover_set_decision[$i]!='当代' && $mouseover_set_decision[$i]!='次代' && $mouseover_set_decision[$i]!='alias' && $mouseover_set_decision[$i]!='ラテン文字' && $mouseover_set_decision[$i]!='showMedals' && $mouseover_set_decision[$i]!='listclass' && $mouseover_set_decision[$i]!='text' && $mouseover_set_decision[$i]!='議論ページ'  && $mouseover_set_decision[$i]!='サイン' && $mouseover_set_decision[$i]!='元首' && $mouseover_set_decision[$i]!='元首職' && $mouseover_set_decision[$i]!='wikify' && $mouseover_set_decision[$i]!='period' && $mouseover_set_decision[$i]!='commonName' && $mouseover_set_decision[$i]!='caption'){
        if($mouseover_set_decision[$i]!='年' && $mouseover_set_decision[$i]!='特筆性' && $mouseover_set_decision[$i]!='活動備考' && $mouseover_set_decision[$i]!='時点' && $mouseover_set_decision[$i]!='モデル名' && $mouseover_set_decision[$i]!='listclass' && $mouseover_set_decision[$i]!='text' && $mouseover_set_decision[$i]!='議論ページ'  && $mouseover_set_decision[$i]!='サイン' && $mouseover_set_decision[$i]!='元首' && $mouseover_set_decision[$i]!='元首職' && $mouseover_set_decision[$i]!='wikify' && $mouseover_set_decision[$i]!='period' && $mouseover_set_decision[$i]!='commonName' && $mouseover_set_decision[$i]!='caption'){
          echo '<div>';
          echo "<span>$mouseover_set_decision[$i]</span>";
          echo "<p class=\"arrow_box\">$detail_set_decision[$i]</p>";
          echo '</div>';
        }}}}
      }
    }else{
      break;
    }
  }
    echo '</div>';
    echo '<br><br><br><br>';

  }
}

pg_close($conn);
?>
</div>


<div class="green">
<?php
echo '目的語取得(完全一致のみ)';echo '<br>';
$str = "python3 a_test.py \"" . $word . "\"";
$re = shell_exec($str);
$a_popup_output = explode("\n", $re);

for($i=0 ;; $i++){
    if(isset($a_popup_output[$i])){
        $form="form3_" . $i;
        $java="javascript:" . $form . ".submit()";
        echo "<form style=\"display: inline\" action=\"sample_4.php\" method=\"post\" name=$form>";
        echo "<input type=\"hidden\" name=\"word\" value=$word>";
        echo "<input type=\"hidden\" name=\"word_2\" value=$a_popup_output[$i]>";
        echo "<a href=$java>$a_popup_output[$i]</a>";
        echo "<br>";
        echo "</form>";
    } else {
        break;
    }
}
?>
</div>


<div class="blue">

<?php
echo 'DBpedia内で検索したキーワードを取得';
echo '<form action="matome.php" method="post">';
echo "<input type=\"hidden\" name=\"php\" value='DBpedia'>";
echo "<input type=\"hidden\" name=\"comma_separated_sample_2\" value=$comma_separated_sample_2>";
echo "<input type=\"hidden\" name=\"comma_separated_popup\" value=$comma_separated_popup>";
echo "<input type=\"hidden\" name=\"word\" value=$word>";
if(isset($word_2)){
  echo "<input type=\"hidden\" name=\"word_2\" value=$word_2>";
}
echo '<input type="submit" name="submit" value="検索">';
echo '</form>';

if($php=='DBpedia'){
echo '<form action="matome.php" method="post">';
if(isset($word)){
  echo "<select name=\"category_popup\">";
    if($category_popup == "Thing"){
        $word = str_replace("_", " ", $word);
        $command="python3 test_3.py \"" . $word . "\"";
        exec($command,$python_popup);
        $word = str_replace(" ", "_", $word);
        $comma_separated_popup = implode(",", $python_popup);
    }

    echo "<option value=\"Thing\">カテゴリを選択してください</option>";
    $command="python3 test_4.py \"" . $comma_separated_popup . "\" \"" . $category_popup."\"";
    exec($command,$python_2_popup);
    for($i=0 ;; $i++){
        if(isset($python_2_popup[$i])){
            echo "<option value=$python_2_popup[$i]>$python_2_popup[$i]</option>";
        } else {
            break;
        }
    }
  echo "</select>";
  echo "<input type=\"hidden\" name=\"comma_separated_sample_2\" value=$comma_separated_sample_2>";
  echo "<input type=\"hidden\" name=\"comma_separated_popup\" value=$comma_separated_popup>";
  $word = str_replace(" ", "_", $word);
  echo "<input type=\"hidden\" name=\"word\" value=$word>";
  $word = str_replace("_", " ", $word);
  echo "<input type=\"hidden\" name=\"php\" value=$php>";
  if(isset($word_2)){
    echo "<input type=\"hidden\" name=\"word_2\" value=$word_2>";
  }
  echo '<input type="submit" name="submit" value="検索">';
  echo '</form>';
}
if($category_popup != "Thing"){
    echo "<form action=\"matome.php\" method=\"post\">";
        $command="python3 csv_call_ParentClass.py \"" . $category_popup."\"";
        exec($command,$category_change);
        echo "<input type=\"hidden\" name=\"word\" value=$word>";
        $word = str_replace("_", " ", $word);
        echo "<input type=\"hidden\" name=\"category_popup\" value=$category_change[0]>";
        echo "<input type=\"hidden\" name=\"comma_separated_popup\" value=$comma_separated_popup>";
        echo "<input type=\"hidden\" name=\"php\" value=$php>";
    echo "<input type=\"submit\" name=\"submit\" value=\"一つ前に戻る\">";
    echo "</form>";
}

if(isset($category_popup) && isset($word) && $word!=''){
  if($category_popup != "Thing"){
    $word = str_replace("_", " ", $word);
    $str = "python3 sample_2.py \"" . $word . "\" \"" . $category_popup."\"";
    $word = str_replace(" ", "_", $word);
    $re = shell_exec($str);
    $python_3_popup = explode("\n", $re);
  }else{
    $word = str_replace("_", " ", $word);
    $str = "python3 sample.py \"" . $word."\"";
    $word = str_replace(" ", "_", $word);
    $re = shell_exec($str);
    $python_3_popup = explode("\n", $re);
  }

  for($i=0 ;; $i++){
    if(isset($python_3_popup[$i])){
      $form="form" . $i;
      $java="javascript:" . $form . ".submit()";
      echo "<form style=\"display: inline\" action=\"sample_3.php\" method=\"post\" name=$form>";
      echo "<input type=\"hidden\" name=\"word\" value=$python_3_popup[$i]>";
      echo "<a href=$java>$python_3_popup[$i]</a>";
      echo "<br>";
      echo "</form>";
    } else {
      break;
    }
  }
}

}
?>
</div>
</body></html>