<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">

    <!--====== Title ======-->
    <title>eStore Template</title>

    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="shortcut icon" href="images/favicon.png" type="image/png">
    <link rel="stylesheet" href="css/slick.css">
    <link rel="stylesheet" href="css/LineIcons.css">
    <link rel="stylesheet" href="css/materialdesignicons.min.css">
    <link rel="stylesheet" href="css/jquery-ui.min.css">
    <link rel="stylesheet" href="css/nice-select.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/default.css">
    <link rel="stylesheet" href="css/style.css">
    
</head>

<body>

    <div class="preloader">
        <div class="loader">
            <div class="ytp-spinner">
                <div class="ytp-spinner-container">
                    <div class="ytp-spinner-rotator">
                        <div class="ytp-spinner-left">
                            <div class="ytp-spinner-circle"></div>
                        </div>
                        <div class="ytp-spinner-right">
                            <div class="ytp-spinner-circle"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('user/header/header')
   
    @yield("content")
  
    @include("user/footer/footer")
 
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/vendor/jquery-3.5.1.min.js"></script>
    <script src="js/vendor/modernizr-3.7.1.min.js"></script>
    <script src="js/slick.min.js"></script>
    <script src="js/jquery-vj-accordion-steps.js"></script>
    <script src="js/jquery-ui.min.js"></script>
    <script src="js/jquery.form-validator.min.js"></script>
    <script src="js/jquery.nice-select.min.js"></script>
    <script src="js/jquery.formatter.min.js"></script>
    <script src="js/count-up.min.js"></script>
    <script src="js/main.js"></script>

</body>

</html>
