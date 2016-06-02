<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
</head>

<body>
<h1>{{ $newsTitle }}</h1>
<p>
    <a href="{{ URL::route('individualNews', $newsId) }}" >{{ trans('emails\subscriptions.visitNews') }}</a>
</p>
</body>
</html>
