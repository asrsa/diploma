<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
    </head>

    <body>
        <h1>{{ trans('emails\authorActivateMail.activateAccountTitle') }}</h1>
        <p>
            {{ trans('emails\authorActivateMail.activateAccountBody') }}
            <a href="{{ URL::to('author/activate/'. $activationCode) }}">{{ trans('emails\authorActivateMail.activate') }}</a>
        </p>
    </body>
</html>
