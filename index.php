<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>hair salon</title>
  </head>
  <body>
    <div class="top">
      <h1>Hair Salon</h1>
      <h2>【DM用顧客管理フォーム】</h2>
    </div>
    <!-- 入力データをanswer.phpに送信 -->
    <form  method="POST" action="answer.php">
      <label><h3>誕生月：</h3>
        <p>【必須】※誕生月が一桁の場合は、先頭に0を入力してください。（例：1月→01）</p>
        <input type="text" id="bir" name="bir" value="" required></label><br>
        <br>
        <input type="submit" value="表示">
      </form>
    </body>
</html>
