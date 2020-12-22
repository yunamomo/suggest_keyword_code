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

<form action="mouse_over.php" method="post">
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

  echo '<div id="menu">';
  for($i=0;;$i++){
    if(isset($mouseover_set[$i])){
      if((strpos($mouseover_set[$i], '?') === false) && (strpos($mouseover_set[$i], 'wikiPage') === false) && (strpos($mouseover_set[$i], '画像') === false) && (strpos($mouseover_set[$i], 'image') === false) && (strpos($mouseover_set[$i], 'activeYears') === false) && (strpos($mouseover_set[$i], '写真') === false) && (strpos($mouseover_set[$i], '更新日') === false) && (strpos($mouseover_set[$i], '表記') === false)){
        if($mouseover_set[$i]!='date' && $mouseover_set[$i]!='section' && $mouseover_set[$i]!='thumbnail' && $mouseover_set[$i]!='id' && $mouseover_set[$i]!='キャプション' && $mouseover_set[$i]!='存命人物の出典明記' && $mouseover_set[$i]!='公式サイト' && $mouseover_set[$i]!='独自研究'  && $mouseover_set[$i]!='ソートキー' && $mouseover_set[$i]!='活動期間' && $mouseover_set[$i]!='名前' && $mouseover_set[$i]!='芸名' && $mouseover_set[$i]!='title' && $mouseover_set[$i]!='name' && $mouseover_set[$i]!='活動時期'){
        if($mouseover_set[$i]!='雑多な内容の箇条書き' && $mouseover_set[$i]!='民族' && $mouseover_set[$i]!='活動内容' && $mouseover_set[$i]!='人名' && $mouseover_set[$i]!='就任日' && $mouseover_set[$i]!='国旗' && $mouseover_set[$i]!='各国語表記' && $mouseover_set[$i]!='退任日'  && $mouseover_set[$i]!='当選回数' && $mouseover_set[$i]!='before' && $mouseover_set[$i]!='after' && $mouseover_set[$i]!='titlenote' && $mouseover_set[$i]!='direction' && $mouseover_set[$i]!='titlestyle' && $mouseover_set[$i]!='前代'){
        if($mouseover_set[$i]!='当代' && $mouseover_set[$i]!='次代' && $mouseover_set[$i]!='alias' && $mouseover_set[$i]!='ラテン文字' && $mouseover_set[$i]!='showMedals' && $mouseover_set[$i]!='listclass' && $mouseover_set[$i]!='text' && $mouseover_set[$i]!='議論ページ'  && $mouseover_set[$i]!='サイン' && $mouseover_set[$i]!='元首' && $mouseover_set[$i]!='元首職' && $mouseover_set[$i]!='wikify' && $mouseover_set[$i]!='period' && $mouseover_set[$i]!='commonName' && $mouseover_set[$i]!='caption'){
        if($mouseover_set[$i]!='年' && $mouseover_set[$i]!='特筆性' && $mouseover_set[$i]!='活動備考' && $mouseover_set[$i]!='時点' && $mouseover_set[$i]!='モデル名' && $mouseover_set[$i]!='未検証' && $mouseover_set[$i]!='参照方法' && $mouseover_set[$i]!='背景色'  && $mouseover_set[$i]!='サイン' && $mouseover_set[$i]!='元首' && $mouseover_set[$i]!='元首職' && $mouseover_set[$i]!='wikify' && $mouseover_set[$i]!='period' && $mouseover_set[$i]!='commonName' && $mouseover_set[$i]!='caption'){
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

        }}}}
      }
    }else{
      break;
    }
  }
  for($i=0;;$i++){
    if(isset($mouseover_set_decision[$i])){
      if((strpos($mouseover_set_decision[$i], '?') === false) && (strpos($mouseover_set_decision[$i], 'wikiPage') === false) && (strpos($mouseover_set_decision[$i], '画像') === false) && (strpos($mouseover_set_decision[$i], 'image') === false) && (strpos($mouseover_set_decision[$i], 'activeYears') === false) && (strpos($mouseover_set_decision[$i], '写真') === false) && (strpos($mouseover_set_decision[$i], '更新日') === false) && (strpos($mouseover_set_decision[$i], '表記') === false)){
        if($mouseover_set_decision[$i]!='date' && $mouseover_set_decision[$i]!='section' && $mouseover_set_decision[$i]!='thumbnail' && $mouseover_set_decision[$i]!='id' && $mouseover_set_decision[$i]!='キャプション' && $mouseover_set_decision[$i]!='存命人物の出典明記' && $mouseover_set_decision[$i]!='公式サイト' && $mouseover_set_decision[$i]!='独自研究'  && $mouseover_set_decision[$i]!='ソートキー' && $mouseover_set_decision[$i]!='活動期間' && $mouseover_set_decision[$i]!='名前' && $mouseover_set_decision[$i]!='芸名' && $mouseover_set_decision[$i]!='title' && $mouseover_set_decision[$i]!='name' && $mouseover_set_decision[$i]!='活動時期'){
        if($mouseover_set_decision[$i]!='雑多な内容の箇条書き' && $mouseover_set_decision[$i]!='民族' && $mouseover_set_decision[$i]!='活動内容' && $mouseover_set_decision[$i]!='人名' && $mouseover_set_decision[$i]!='就任日' && $mouseover_set_decision[$i]!='国旗' && $mouseover_set_decision[$i]!='各国語表記' && $mouseover_set_decision[$i]!='退任日'  && $mouseover_set_decision[$i]!='当選回数' && $mouseover_set_decision[$i]!='before' && $mouseover_set_decision[$i]!='after' && $mouseover_set_decision[$i]!='titlenote' && $mouseover_set_decision[$i]!='direction' && $mouseover_set_decision[$i]!='titlestyle' && $mouseover_set_decision[$i]!='前代'){
        if($mouseover_set_decision[$i]!='当代' && $mouseover_set_decision[$i]!='次代' && $mouseover_set_decision[$i]!='alias' && $mouseover_set_decision[$i]!='ラテン文字' && $mouseover_set_decision[$i]!='showMedals' && $mouseover_set_decision[$i]!='listclass' && $mouseover_set_decision[$i]!='text' && $mouseover_set_decision[$i]!='議論ページ'  && $mouseover_set_decision[$i]!='サイン' && $mouseover_set_decision[$i]!='元首' && $mouseover_set_decision[$i]!='元首職' && $mouseover_set_decision[$i]!='wikify' && $mouseover_set_decision[$i]!='period' && $mouseover_set_decision[$i]!='commonName' && $mouseover_set_decision[$i]!='caption'){
        if($mouseover_set_decision[$i]!='年' && $mouseover_set_decision[$i]!='次代' && $mouseover_set_decision[$i]!='alias' && $mouseover_set_decision[$i]!='ラテン文字' && $mouseover_set_decision[$i]!='showMedals' && $mouseover_set_decision[$i]!='listclass' && $mouseover_set_decision[$i]!='text' && $mouseover_set_decision[$i]!='議論ページ'  && $mouseover_set_decision[$i]!='サイン' && $mouseover_set_decision[$i]!='元首' && $mouseover_set_decision[$i]!='元首職' && $mouseover_set_decision[$i]!='wikify' && $mouseover_set_decision[$i]!='period' && $mouseover_set_decision[$i]!='commonName' && $mouseover_set_decision[$i]!='caption'){
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
?>

</body></html>