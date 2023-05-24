<!DOCTYPE html>
<html>

<head>
    <meta charset='utf-8'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finalisation de l'inscription</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }

        .firstContainer {

            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, .1);
            text-align: center;
        }

        h1 {
            text-align: center;
            font-size: 28px;
            margin-bottom: 30px;
        }

        p {
            font-size: 18px;
            margin-bottom: 20px;
        }

        a.button {
            display: inline-block;
            background-color: #FFC700;
            color: white;
            padding: 15px 25px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 18px;
            transition: background-color .3s;
            user-select: none;
        }

        a.button:hover {
            background-color: #FFB500;
            user-select: none;
        }

        .btn {
            text-align: center;
        }

        @media only screen and (max-width: 600px) {

            /* Réduire la largeur de la boîte de contenu pour les petits écrans */
            .container {
                width: 100%;
                max-width: none;
            }

            /* Ajouter de l'espace supplémentaire autour du contenu */
            .container {
                padding: 10px;
            }

            /* Modifier la taille de la police pour les petits écrans */
            h1 {
                font-size: 24px;
            }

            p {
                font-size: 16px;
                margin-bottom: 15px;
            }

            /* Centrer le bouton */
            .btn {
                text-align: center;
            }
        }

    </style>
</head>

<body>
    <div class='firstContainer'>
        <div class='container'>
            <h1>Finalisation de l'inscription</h1>
            <p>Pour finaliser l'inscription, veuillez cliquer sur le bouton ci-dessous. Si vous n'êtes pas à l'origine de cette demande, veuillez ignorer cet e-mail.</p>
            <p class='btn'><a href="{{ route('TokenRegisterEleve', ['token' => session('token')]) }}" class='button'>Valider mon adresse mail</a></p>
        </div>
    </div>
</body>

</html>