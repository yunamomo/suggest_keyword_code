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
if(isset($_POST["comma_separated"])){
  $comma_separated = $_POST["comma_separated"];
}
?>

<html><head>
  <title>2のバックアップ</title>
</head>
<body>

<form action="popup_test_5.php" method="post">
<?php
echo "<input type=\"search\" name=\"word\" value=$word>";
echo "を含む単語";
echo "<br>";
echo "<select name=\"category\">";
    if($category == "Thing"){
        $command="python3 test_3.py " . $word;
        exec($command,$python);
        $comma_separated = implode(",", $python);
    }

    echo "<option value=\"Thing\">カテゴリを選択してください</option>";
    $command="python3 test_4.py " . $comma_separated . " " . $category;
    exec($command,$python_2);
    for($i=0 ;; $i++){
        if(isset($python_2[$i])){
            echo "<option value=$python_2[$i]>$python_2[$i]</option>";
        } else {
            break;
        }
    }
echo "</select>";
echo "<input type=\"hidden\" name=\"comma_separated\" value=$comma_separated>";
?>
<br>
<input type="submit" name="submit" value="検索">
</form>

<?php
if(isset($_POST["category"])){
  if($category != "Thing"){
    $command="python3 sample_2.py " . $word . " " . $category;
    exec($command,$python_3);
    for($i=0 ;; $i++){
        if(isset($python_3[$i])){
            echo "<a href=\"https://www.google.com/search?q=$python_3[$i]\" target=\"_blank\" rel=\"nofollow noopener\">$python_3[$i]</a><br>";
        } else {
            break;
        }
    }
  }else{
    $command="python3 sample.py " . $word;
    exec($command,$python_3);

    for($i=0 ;; $i++){
      if(isset($python_3[$i])){
          echo "<a href=\"https://www.google.com/search?q=$python_3[$i]\" target=\"_blank\" rel=\"nofollow noopener\">$python_3[$i]</a><br>";
      } else {
          break;
      }
    }
  }
}

?>

</body></html>
