<?php
header('Content-Type: text/html; charset=UTF-8');
?>

<?php
session_start();
if(isset($_POST["word"])){
  $word=$_POST["word"];
}
if(isset($_POST["category"])){
  $category=$_POST["category"];
}else{
  $category="Thing";
}
?>

<html><head>
  <title>一つ前に戻るの失敗</title>
</head>
<body>

<form action="popup.php" method="post">
<?php
echo "<input type=\"search\" name=\"word\" value=$word>";
echo "<br>";
echo "<select name=\"category\">";
    $command="python3 csv_call_category.py " . $category;
    exec($command,$python);
    echo "<option value=\"Thing\">カテゴリを選択してください</option>";
    for($i=0; $python[$i]!=''; $i++){
        echo "<option value=$python[$i]>$python[$i]</option>";
    }
?>
</select>
<br>
<input type="submit" name="submit" value="検索">
</form>
<form action="popup.php" method="post">
<?php
    $command="python3 csv_call_ParentClass.py " . $category;
    exec($command,$category_change);
    echo "<input type=\"hidden\" name=\"word\" value=$word>";
    echo "<input type=\"hidden\" name=\"category\" value=$category_change[0]>";
?>
<input type="submit" name="submit" value="一つ前に戻る">
</form>

<?php
if(!isset($_POST["category"])){
  $command="python3 sample.py " . $word;
  exec($command,$python_2);

  for($i=0; $python_2[$i]!=""; $i++){
    echo $python_2[$i];
    echo "<br>";
  }
}else if(isset($_POST["category"])){
  if($category != "Thing"){
    $command="python3 sample_2.py " . $word . " " . $category;
    exec($command,$python_2);
  
    for($i=0; $python_2[$i]!=""; $i++){
      echo $python_2[$i];
      echo "<br>";
    }
  }else{
    $command="python3 sample.py " . $word;
    exec($command,$python_2);
  
    for($i=0; $python_2[$i]!=""; $i++){
      echo $python_2[$i];
      echo "<br>";
    }
  }
}
?>

</body></html>
