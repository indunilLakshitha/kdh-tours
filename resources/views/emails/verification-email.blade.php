<html>
<head>

</head>
<body>
@if($data['type']=='hr-verification')
        <p>この度はBizEyesをご利用いただきましてありがとうございます。<br>
            メールアドレスを確認するには、下のボタンをクリックしてください。</p>
        <a href="{{$data['content_url']}}"><b>メールアドレスを確認</b></a>
        <p>アカウントの作成にお心当たりが無い場合は、大変お手数おかけ致しますが本メールは破棄していただきますようお願い致します。
            <br>
            BizEyes</p>
@elseif($data['type']=='employee-email')
    <p>この度はBizEyesをご利用いただきましてありがとうございます。<br>
        メールアドレスを確認するには、下のボタンをクリックしてください。</p>
    <a href="{{$data['content_url']}}"><b>メールアドレスを確認</b></a>
    <p>アカウントの作成にお心当たりが無い場合は、大変お手数おかけ致しますが本メールは破棄していただきますようお願い致します。
        <br>
        BizEyes</p>
@elseif($data['type']=='password-forget')
    <p>この度はBizEyesをご利用いただきましてありがとうございます。<br>
        パスワード再発行のリクエストを受け付けました。</p>
    <a href="{{$data['content_url']}}"><b>メールアドレスを確認</b></a>
    <p>パスワードの再発行にお心当たりが無い場合は、大変お手数おかけ致しますが本メールは破棄していただきますようお願い致します。</p>
@elseif($data['type']=='training-manager-licence-confirm')
    <p>
        担当者様へ
        <br>
        以下のお客様からBizEyesライセンスの申し込みがありました。
        <br>
        会社　　　　　 =　株式会社〇〇
        <br>
        申し込担当者     =   ABC様
        <br>
        申し込み人数     =　5人
        <br>
        申し込み日　    ＝   2022/08/21
        <br>
        申し込み者氏名とメールアドレス
        <br>
        1.氏名、メールアドレス
        <br>
        2.氏名、メールアドレス
        <br>
        1.氏名、メールアドレス
        <br>
        以上
        <br>
        よろしくお願い致します。
    </p>
@endif
</body>
</html>
