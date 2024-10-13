<html lang="{{ App::getLocale() }}">
    <head>
        <style>
            :root {
                --primary-color: #328E1A;
                --secondary-color: #323232;
                --danger-color: #dc3737;
            }

            .bouton-email{
                margin : auto;
                border-radius : .25rem;
                background-color : #328E1A;
                border: none;
                color: white;
                padding: 15px 32px;
                text-decoration: none;
                display: inline-block;
                font-size: 16px;
                font-weight : bold;
            }

            .bouton-email span{
                color : #ffffff;
            }

            .wrap-email-content{
                color : #212529;
                max-width : 570px;
                margin : auto;
            }

            .logo-email{
                max-width : 300px;
            }

            .footer-email{
                font-style: italic;
            }

            .wrap-email{
                text-align : center;
            }

            .wrap-email p{
                font-size : 16px;
            }


        </style>
    </head>
    <div class="wrap-email">
        <body>
            <div class="wrap-email-content">
                <img class="logo-email" src="https://sunsimiao.fr/img/logo.jpg" alt="Sunsimiao">
                @yield('content')
                <br>
                <p class="footer-email">© <?php echo date("Y"); ?> Sunsimiao - <a href="https://www.impact-web.com">Réalisation Impact Web</a></p>
            </div>
        </body>
    </div>

</html>
