<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
    </head>

    <body>
        <h1>{{ trans('emails\registerMail.activateAccountTitle') }}</h1>
        <p>
            {{ trans('emails\registerMail.activateAccountBody') }}
            <a href="{{ URL::to('account/activate/'. $activationCode) }}" >{{ trans('emails\registerMail.activate') }}</a>
        </p>
    </body>
</html>
