<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>取引完了通知</title>
</head>
<body>
    <h1>取引が完了しました</h1>
    <p>{{ $transaction->seller->name }}&nbsp; &nbsp; 様</p>
    <p>購入者:{{ $transaction->buyer->name }}&nbsp; &nbsp; 様により、あなたが出品されました「{{ $transaction->item->name }}」の取引が完了しましたので</p>
    <p>評価をご確認ください。</p>
    <p>取引の詳細につきましては、システムをご確認ください。</p>
</body>
</html>
