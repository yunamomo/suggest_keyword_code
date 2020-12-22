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
  <title>カテゴリ提示済の検索</title>
</head>
<body>

<form action="popup_test.php" method="post">
<?php
echo "<input type=\"search\" name=\"word\" value=$word>";
echo "<br>";
echo "<select name=\"category\">";
    $command="python3 csv_call_category.py " . $category;
    exec($command,$python);
    echo "<option value=\"Thing\">カテゴリを選択してください</option>";
    for($i=0 ;; $i++){
        if(isset($python[$i])){
            echo "<option value=$python[$i]>$python[$i]</option>";
        } else {
            break;
        }
    }
?>
</select>
<br>
<input type="submit" name="submit" value="検索">
</form>

<?php
if($category != "Thing"){
    echo "<form action=\"popup_test.php\" method=\"post\">";
        $command="python3 csv_call_ParentClass.py " . $category;
        exec($command,$category_change);
        echo "<input type=\"hidden\" name=\"word\" value=$word>";
        echo "<input type=\"hidden\" name=\"category\" value=$category_change[0]>";
    echo "<input type=\"submit\" name=\"submit\" value=\"一つ前に戻る\">";
    echo "</form>";
}

if(!isset($_POST["category"])){
  $command="python3 sample.py " . $word;
  exec($command,$python_2);

  for($i=0 ;; $i++){
      if(isset($python_2[$i])){
          echo "<a href=\"https://www.google.com/search?q=$python_2[$i]\" target=\"_blank\" rel=\"nofollow noopener\">$python_2[$i]</a><br>";
      } else {
          break;
      }
  }

}else if(isset($_POST["category"])){
  if($category != "Thing"){
    $command="python3 sample_2.py " . $word . " " . $category;
    exec($command,$python_2);
  
    for($i=0 ;; $i++){
      if(isset($python_2[$i])){
          echo "<a href=\"https://www.google.com/search?q=$python_2[$i]\" target=\"_blank\" rel=\"nofollow noopener\">$python_2[$i]</a><br>";
      } else {
          break;
      }
    }

  }else{
    $command="python3 sample.py " . $word;
    exec($command,$python_2);
  
    for($i=0 ;; $i++){
      if(isset($python_2[$i])){
          echo "<a href=\"https://www.google.com/search?q=$python_2[$i]\" target=\"_blank\" rel=\"nofollow noopener\">$python_2[$i]</a><br>";
      } else {
          break;
      }
    }
  }
}
?>

</body></html>
