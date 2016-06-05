<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
</head>

<body>
<h1>{{ trans('emails\subscriptions.emailTitle') . $categoryName }}</h1>
<p>
    {{ trans('emails\subscriptions.visitNews') }}
    <a href="{{ URL::route('individualNews', $newsId) }}" >{{ $newsTitle }}</a>
</p>
</body>
</html>
