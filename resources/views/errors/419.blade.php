<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

    <title>419 {{ __('resubscribe.Error 419') }}</title>

    <!-- Google font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Custom stlylesheet -->

    <style>
        * {
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
        }

        body {
            padding: 0;
            margin: 0;
        }

        #notfound {
            position: relative;
            height: 100vh;
        }

        #notfound .notfound {
            position: absolute;
            left: 50%;
            top: 50%;
            -webkit-transform: translate(-50%, -50%);
            -ms-transform: translate(-50%, -50%);
            transform: translate(-50%, -50%);
        }

        .notfound {
            max-width: 520px;
            width: 100%;
            line-height: 1.4;
        }

        .notfound>div:first-child {
            padding-left: 200px;
            padding-top: 12px;
            height: 170px;
            margin-bottom: 20px;
        }

        .notfound .notfound-404 {
            position: absolute;
            left: 0;
            top: 0;
            width: 170px;
            height: 170px;
            background: #e01818;
            border-radius: 7px;
            -webkit-box-shadow: 0px 0px 0px 10px #e01818 inset, 0px 0px 0px 20px #fff inset;
            box-shadow: 0px 0px 0px 10px #e01818 inset, 0px 0px 0px 20px #fff inset;
        }

        .notfound .notfound-404 h1 {
            font-family: 'Cairo', cursive;
            color: #fff;
            font-size: 118px;
            margin: 0px;
            position: absolute;
            left: 50%;
            top: 50%;
            -webkit-transform: translate(-50%, -50%);
            -ms-transform: translate(-50%, -50%);
            transform: translate(-50%, -50%);
            display: inline-block;
            height: 60px;
            line-height: 60px;
        }

        .notfound h2 {
            font-family: 'Cairo', cursive;
            font-size: 68px;
            color: #222;
            font-weight: 400;
            text-transform: uppercase;
            margin: 0px;
            line-height: 1.1;
        }

        .notfound p {
            font-family: 'Cairo', sans-serif;
            font-size: 16px;
            font-weight: 400;
            color: #222;
            margin-top: 5px;
        }

        .notfound a {
            font-family: 'Cairo', sans-serif;
            color: #e01818;
            font-weight: 400;
            text-decoration: none;
        }

        @media only screen and (max-width: 480px) {
            .notfound {
                padding-left: 15px;
                padding-right: 15px;
            }
            .notfound>div:first-child {
                padding: 0px;
                height: auto;
            }
            .notfound .notfound-404 {
                position: relative;
                margin-bottom: 15px;
            }
            .notfound h2 {
                font-size: 42px;
            }
        }
    </style>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

<div id="notfound">
    <div class="notfound">
        <div>
            <div class="notfound-404">
                <h1>!</h1>
            </div>
            <h2>{{ __('resubscribe.Error') }} 419</h2>
        </div>
        <p>{{ __('resubscribe.Error 419') }} <a href="mailto:help@furqangroup.com">help@furqangroup.com</a></p>
    </div>
</div>

</body><!-- This templates was made by Colorlib (https://colorlib.com) -->

</html>
