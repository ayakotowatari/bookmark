<?php

$search = $_POST["search"];
// echo $search;

//2. DB接続します
try {
    //Password:MAMP='root',XAMPP=''
    // 最後の2つは、'id', 'password'
    $pdo = new PDO('mysql:dbname=bookmark_db;charset=utf8;host=localhost','root','root');
  } catch (PDOException $e) {
    exit('DBConnectError:'.$e->getMessage());
  }

//２．データ登録SQL作成
if($search != ""){

  $stmt = $pdo->prepare(
    "SELECT url,comment 
    FROM bm
    INNER JOIN bm_tag_map ON bm.entry_id = bm_tag_map.entry_id
    INNER JOIN bm_tag ON bm_tag_map.tag_id = bm_tag.tag_id
    WHERE bm_tag.tag_id = '".$search."'
    ");
  $status = $stmt->execute();

}

//３．データ表示
$view="";
if($status==false) {
    //execute（SQL実行時にエラーがある場合）
  $error = $stmt->errorInfo();
  exit("SQLError:".$error[2]);

}else{
  //Selectデータの数だけ自動でループしてくれる
  //FETCH_ASSOC=http://php.net/manual/ja/pdostatement.fetch.php
  // .=は、+=と同じ。
  while( $r = $stmt->fetch(PDO::FETCH_ASSOC)){ 
    $view .= "<p>".$r["tag_name"]."</p>".
             "<p>".$r["url"]."</p>".
             "<p>".$r["comment"]."</p>";
  }


}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>ブックマーク表示</title>
</head>
<body id="main">
<!-- Head[Start] -->
<!-- <header>
  <nav class="navbar navbar-default">
    <div class="container-fluid">
      <div class="navbar-header">
      <a class="navbar-brand" href="index.php">データ登録</a>
      </div>
    </div>
  </nav>
</header> -->
<!-- Head[End] -->

<!-- Main[Start] -->
<div>
    <div class="container"><?=$view?></div>
</div>
<!-- Main[End] -->

</body>
</html>



