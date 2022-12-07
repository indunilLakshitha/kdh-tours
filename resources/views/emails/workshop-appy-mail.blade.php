<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title> ワークショップの詳細</title>

</head>

<body>
    お世話になっております。<br>
    BizEyesのワークショップをご予約いただきありがとうございました。<br>
    以下、申込みしたワークショップの詳細になります。<br>
    タイトル：{{ $data->name }}<br>
    日時:{{ $data->opening_date }} {{ $data->opening_time }}<br>
    ワークショップ種類：{{ $data->venue }}<br>
    @if ($data->venue = 2)
        <br>
        住所：{{ $data->place_url }}<br>
        MAP:{{ $data->place_url }}
    @else
        Zoomリンク: {{ $data->place_url }}<br>
    @endif
    以上<br>
    よろしくお願いいたします。<br>
    BizEyesチーム (edited)<br>
</body>

</html>
