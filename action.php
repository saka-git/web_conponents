<?php
// デバッグ関数
function dd($param)
{
  echo "---------------------------------------------";
  print "<pre>";
  var_dump($param);
  exit();
}

//ドキュメントルート以下の階層を取得
$uri = $_SERVER["REQUEST_URI"];
//もし$uriの中にaction.phpという名前があったら削除
if (preg_match("/action.php/", $uri)) {
  $uri = str_replace("action.php", "", $uri);
  //$urlの最後の２文字が"//"だった場合"/"に変更する
  if (mb_substr($uri, "-2") == "//") {
    //ここから
    $uri = substr_replace($uri, "", -1, 1);
  } //ここまで最後の文字が//の場合しか対応できないので修正する
}

function is_SSL()
{
  if (!empty($_SERVER["https"])) {
    return true;
  }

  if (!empty($_SERVER["HTTP_X_FORWARDED_PROTO"]) && $_SERVER["HTTP_X_FORWARDED_PROTO"] == "https") {
    return true;
  }

  if (!isset($_SERVER["SERVER_PORT"])) {
    return false;
  }

  if ($_SERVER["SERVER_PORT"] == 443) {
    return true;
  }

  return false;
}

$isSSL = is_SSL();
$prot = $isSSL ? "https://" : "http://";
$host = $_SERVER["HTTP_HOST"];
$config["base_url"] = $prot . $host . $uri;

//セッションにpostの値をいれる
session_start();
$_SESSION = $_POST;

//入力必須項目
$required = ["name", "email"];

// $_POST[]が空だったらindex.phpに戻る
foreach ($required as $value) {
  if (empty($_POST[$value])) {
    header("Location: {$config["base_url"]}");
    exit();
  }
}

//電話番号が半角数字以外だったらリダイレクト
if (preg_match("/-/", $_POST["tell"])) {
  $pieces = explode("-", $_POST["tell"]);
  foreach ($pieces as $value) {
    if (!ctype_digit($value)) {
      $tel_test[] = 1;
    }
  }
} else {
  if (!ctype_digit($_POST["tell"])) {
    $tel_test[] = 2;
  }
}
//メールアドレスがおかしかったらリダイレクト
if (!empty($_POST["email"])) {
  if (preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $_POST["email"])) {
    //正しいのでスルーしてOK
  } else {
    $email_test[] = 1;
  }
}

if (!empty($email_test)) {
  $_SESSION["email_test"] = $email_test;
}

//バリデーション通ったのでセッション削除する
session_destroy();

// 採用担当者送信メール
$to = "sakamoto.tatsuya1997@gmail.com";

$subject = "ダミーサイトよりお問い合わせがありました。";
$text = "
下記の内容でご応募がありました。
ご確認の上、対応お願いします。
==========================
氏名：
{$_POST["name"]}

メールアドレス：
{$_POST["email"]}

電話番号：
{$_POST["tel"]}

電話番号：
{$_POST["telsub"]}

==============================
--
このメールは、ダミー（url） のフォームから送信されました。
";

// 回答者への自動返信メール
$toReply = $_POST["email"];
$subjectReply = "ご応募ありがとうございました。";
$textReply = "
ダミー株式会社です。
この度は、ご応募いただき誠にありがとうございます。

※下記の内容でご応募頂いています。
==========================
氏名：
{$_POST["name"]}

メールアドレス：
{$_POST["email"]}

電話番号：
{$_POST["tel"]}

電話番号：
{$_POST["telsub"]}

==========================
--
このメールは、ダミー（url） のフォームから送信されました。
";

if (sendMail($to, $subject, $text)&& sendMail($toReply, $subjectReply, $textReply)) {
  header("Location: {$config["base_url"]}thanks.html");
  exit();
} else {
  header("Location: {$config["base_url"]}");
  exit();
}

function sendMail($to = null, $subject = null, $text = null)
{
  //初期化
  $res = false;

  //日本語の使用宣言
  mb_language("ja");
  mb_internal_encoding("UTF-8");

  if ($to === null || $subject === null || $text === null) {
    return false;
  }

  // 送信元の設定
  $sender_email = "tatsuya19970124@icloud.com";

  $org = "";
  $from = "tatsuya19970124@icloud.com";

  // ヘッダー設定
  $header = "";
  $header .= "Content-Type: multipart/mixed;boundary=\"__BOUNDARY__\"\n";
  $header .= "Content-Type: text/plain; charset=\"ISO-2022-JP\"\n";
  //$header .= "Return-Path: " . $sender_email . " \n";
  $header .= "From: " . $from . " \n";
  $header .= "Sender: " . $from . " \n";
  $header .= "Reply-To: " . $sender_email . " \n";
  $header .= "Organization: " . $org . " \n";
  $header .= "X-Sender: " . $org . " \n";
  $header .= "X-Priority: 3 \n";

  // テキストメッセージを記述
  $body = "--__BOUNDARY__\n";
  $body .= "Content-Type: text/plain; charset=\"ISO-2022-JP\"\n\n";
  $body .= $text . "\n";
  $body .= "--__BOUNDARY__\n";

  $returnPath = "-f " . $sender_email;

  //メール送信
  //mb_send_mailは成功したら、true, 失敗したらfalseがカエル
  $res = mb_send_mail($to, $subject, $body, $header, $returnPath);


  return $res;
}
?>
