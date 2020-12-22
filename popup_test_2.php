<?php
header('Content-Type: text/html; charset=UTF-8');
?>

<?php
session_start();
if(isset($_POST["word"])){
  $word=$_POST["word"];
}
if(isset($_POST["category_popup"])){
  $category_popup=$_POST["category_popup"];
}else{
  $category_popup="Thing";
}
if(isset($_POST["comma_separated_popup"])){
  $comma_separated_popup = $_POST["comma_separated_popup"];
}
?>

<html><head>
  <title>popup</title>
</head>
<body>

<form action="popup_test_2.php" method="post">
<?php
if(isset($word)){
  echo "<input type=\"search\" name=\"word\" value=$word>";
}else{
  echo "<input type=\"search\" name=\"word\">";
}
echo "を含む単語";
echo "<br>";
if(isset($word)){
  echo "<select name=\"category_popup\">";
    if($category_popup == "Thing"){
        $command="python3 test_3.py " . $word;
        exec($command,$python_popup);
        $comma_separated_popup = implode(",", $python_popup);
    }

    echo "<option value=\"Thing\">カテゴリを選択してください</option>";
    $command="python3 test_4.py " . $comma_separated_popup . " " . $category_popup;
    exec($command,$python_2_popup);
    for($i=0 ;; $i++){
        if(isset($python_2_popup[$i])){
            echo "<option value=$python_2_popup[$i]>$python_2_popup[$i]</option>";
        } else {
            break;
        }
    }
echo "</select>";
echo "<input type=\"hidden\" name=\"comma_separated_popup\" value=$comma_separated_popup>";
}
?>
<br>
<input type="submit" name="submit" value="検索">
</form>

<?php


if($category_popup != "Thing"){
    echo "<form action=\"popup_test_2.php\" method=\"post\">";
        $command="python3 csv_call_ParentClass.py " . $category_popup;
        exec($command,$category_change);
        echo "<input type=\"hidden\" name=\"word\" value=$word>";
        echo "<input type=\"hidden\" name=\"category_popup\" value=$category_change[0]>";
    echo "<input type=\"submit\" name=\"submit\" value=\"一つ前に戻る\">";
    echo "</form>";
}

if(isset($category_popup) && isset($word) && $word!=''){
  if($category_popup != "Thing"){
    $command="python3 sample_2.py " . $word . " " . $category_popup;
    exec($command,$python_3_popup);
  }else{
    $command="python3 sample.py " . $word;
    exec($command,$python_3_popup);
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
?>

</body></html>
