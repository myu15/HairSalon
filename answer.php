<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">

    <title>Hair Salon</title>
  </head>
  <body>
    <h1>Hair Salon</h1>
    <?php
    // index.phpより受け取った値を変数に代入
    $bir = $_POST['bir'];
    // 条件分岐の結果によって人数の集計をとるために使用する変数を初期化
    $counter1 = 0;
    $counter2 = 0;
    $counter3 = 0;
    $name1 = [];
    $name2 = [];
    $name3 = [];

    // $birで受け取った値が一桁の場合、SQLの抽出が正常に動作しないため
    // 条件分岐を行い一桁の場合は、メッセージの出力を行う
    if(strlen($bir) < 2){
    echo "入力に誤りがあります。";
     } else {
      $dsn='mysql:host=localhost;dbname=userList';
      $user='root';
      $password='root';

      try{
        $db=new PDO($dsn,$user,$password);
        $db ->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);
        // 顧客テーブルと利用状況テーブルを結合
        // 今後使用するカラムを抽出する（user_id,name,email,SUM(fee)）
        // 条件①：birthdayカラムの月が$birと合致するレコード（DATE_FORMAT）
        // 条件②：visitの範囲が過去1年間のレコード
        $sql="SELECT status.user_id,name,email,SUM(fee) as sumfee FROM status
        JOIN users ON status.user_id = users.user_id
        WHERE DATE_FORMAT(birthday,'%m')= $bir
        AND visit between NOW() + interval -1 year and NOW()
        GROUP BY status.user_id";
        $stmt=$db->query($sql);
        $stmt->execute();
      }catch(PDOException $error){
        echo "error".$error->getMessage();
      }
      // SQLより取り出したデータを配列に格納
      $data = $stmt->fetchAll();
      $user_id = array_column($data, 'user_id');
      $name = array_column($data, 'name');
      $email = array_column($data, 'email');
      $fee = array_column($data, 'sumfee');

      // メールの送信の際、文字化けを防ぐため下記２行を記述
      mb_language("Japanese");
      mb_internal_encoding("UTF-8");

      for ($i=0; $i < count($data); $i++) {
        // 抽出したデータを条件分岐し、
        // 各ユーザーの利用金額に合わせた内容がメールで送信されるよう設定
        if ($fee[$i]>=30000) {
          $to = $email[$i];
          $subject = "お誕生日おめでとうございます！";
          $message = $name[$i]."さん\r\n
          お誕生日おめでとうございます。\r\n
          美容院Aです。いつもご来店ありがとうございます。\r\n
          当店では、お誕生月にご来店いただきますと、ささやかではございますが、\r\n
          カット料金１回分無料とさせていただきます。\r\n
          ぜひ、ご来店ください。";
          $headers = "From: yk15.e.oras2@gmail.com";
          mb_send_mail($to, $subject, $message, $headers);

          // 条件分岐の結果ごとに集計をとるため
          // if文が実行される度、変数がインクリメントされる
          $counter1 ++;
          array_push($name1,$name[$i]);
        } else if ($fee[$i] < 30000 && $fee[$i]>=20000) {
          $to = $email[$i];
          $subject = "お誕生日おめでとうございます！";
          $message = $name[$i]."さん\r\n
          お誕生日おめでとうございます。\r\n
          美容院Aです。いつもご来店ありがとうございます。\r\n
          当店では、お誕生月にご来店いただきますと、ささやかではございますが、\r\n
          シャンプーをプレゼントさせていただきます。\r\n
          ぜひ、ご来店ください。";
          $headers = "From: yk15.e.oras2@gmail.com";
          mb_send_mail($to, $subject, $message, $headers);

          $counter2 ++;
          array_push($name2,$name[$i]);
        }else if ($fee[$i] <20000 && $fee[$i]>=10000) {
          $to = $email[$i];
          $subject = "お誕生日おめでとうございます！";
          $message = $name[$i]."さん\r\n
          お誕生日おめでとうございます。\r\n
          美容院Aです。いつもご来店ありがとうございます。\r\n
          当店では、お誕生月にご来店いただきますと、ささやかではございますが、\r\n
          500円の割引券をプレゼントさせていただきます。\r\n
          ぜひ、ご来店ください。";
          $headers = "From: yk15.e.oras2@gmail.com";
          mb_send_mail($to, $subject, $message, $headers);

          $counter3 ++;
          array_push($name3,$name[$i]);
        }
      }
      // 結果出力
      echo "【送信結果】<br />";
      // 上記条件分岐で該当したユーザーがいない場合といる場合でそれぞれ出力内容を変える
      if(empty($counter1)&&empty($counter2)&&empty($counter3)) {
        echo "キャンペーン該当者なし";
      } else {
        echo "3万以上：".$counter1."人<br />";
        for ($i=0; $i < count($name1); $i++) {
          print_r($name1[$i]." ");
        }
        echo "<br />2万以上：".$counter2."人<br />";
        for ($i=0; $i < count($name2); $i++) {
          print_r($name2[$i]." ");
        }
        echo "<br />1万以上：".$counter3."人<br />";
        for ($i=0; $i < count($name3); $i++) {
          print_r($name3[$i]." ");
        }

        echo "<br />送信完了いたしました。<br />";
      }
     }
    ?>
    <br>
    <button type=“button” onclick="location.href='./index.php'">戻る</button>
  </body>
  </html>
