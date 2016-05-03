<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
    </head>

    <body>
        <h1>Aktivacija vašega računa</h1>
        <p>
            Odprite to povezavo za aktivacijo računa:
            {{ URL::to('account/activate/'. $activationCode) }}
        </p>
    </body>
</html>
