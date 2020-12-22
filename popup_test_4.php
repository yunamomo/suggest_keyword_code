<?php
header('Content-Type: text/html; charset=UTF-8');
?>

<?php
session_start();
if(isset($_POST["word"])){
  if($_POST["word"]==''){
    $word='';
  }else{
    $word=$_POST["word"];
  }
}else{
  $word='';
}

if(isset($_POST["suggest_word"])){
  if($_POST["suggest_word"]==''){
    $suggest_word='';
  }else{
    $suggest_word=$_POST["suggest_word"];
  }
}else{
  $suggest_word='';
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
  <title>不完全、完全一致のみの2単語検索</title>
</head>
<body>

<form action="popup_test_4.php" method="post">
<?php
echo "<input type=\"search\" name=\"word\" value=$word>";
echo "<input type=\"search\" name=\"suggest_word\" value=$suggest_word>";
echo "<input type=\"hidden\" name=\"comma_separated\" value=$comma_separated>";
?>
<br>
<input type="submit" name="submit" value="検索">
</form>
<?php
if(isset($_POST["word"]) && isset($_POST["suggest_word"])){
    $command="python3 test_5.py " . $word . " " . $suggest_word;
    exec($command,$python);
    $command_2="python3 test8.py " . $word;
    exec($command_2,$python_2);
    $command_3="python3 test8.py " . $suggest_word;
    exec($command_3,$python_3);

    $relation = '';
    $pk_category = '';
    $sk_category = '';

    for($i=0 ;; $i++){
        if(isset($python[$i])){
            echo $python[$i];
            echo "<br>";
            if($python[$i]=='出演者' || $python[$i]=='starring'){
                $relation = '出演者';
            }else if($python[$i]=='notableWorks'){
                $relation = '作品';
            }else if($python[$i]=='edテーマ' || $python[$i]=='opテーマ' || $python[$i]=='主題歌'){
                $relation = '使用曲';
            }
        } else {
            break;
        }
    }

    echo '<br>';

    for($i=0 ;; $i++){
        if(isset($python_2[$i])){
            echo $python_2[$i];
            echo "<br>";
            if($python_2[$i]=='Actor'){
                $pk_category = 'Actor';
            }else if($python_2[$i]=='TelevisionShow'){
                $pk_category = 'TelevisionShow';
            }else if($python_2[$i]=='Writer'){
                $pk_category = 'Writer';
            }
        } else {
            break;
        }
    }

    echo '<br>';

    for($i=0 ;; $i++){
        if(isset($python_3[$i])){
            echo $python_3[$i];
            echo "<br>";
        } else {
            break;
        }
    }

    if($relation=='出演者' && $pk_category=='Actor'){
        $sk_category = '出演作品';
    }else if($relation=='出演者'){
        $sk_category = '出演者';
    }else if($relation=='使用曲' && $pk_category=='TelevisionShow'){
        $sk_category = '使用曲';
    }else if($relation=='使用曲'){
        $sk_category = '使用作品';
    }else if($relation=='作品' && $pk_category=='Writer'){
        $sk_category = '書籍';
    }else if($relation=='作品'){
        $sk_category = '作者';
    }

    echo '<br>';
    echo $sk_category;
}
?>

</body></html>