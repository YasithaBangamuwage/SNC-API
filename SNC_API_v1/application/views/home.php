<html>
<head>
    <title>SNC API | 4th year Research</title>
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css">
    <style type="text/css">
    body {
        padding-top: 40px;
        padding-bottom: 40px;
        background-color: #eee;
    }

    .form-signin {
        max-width: 330px;
        padding: 15px;
        margin: 0 auto;
    }
    .form-signin .form-signin-heading{
        padding-bottom: 10px;
        margin-bottom: 20px;
        border-bottom: 1px #ccc dotted;
        text-align: center;
    }
    .form-signin .footer{
        padding-top: 10px;
        margin-top: 20px;
        border-top: 1px #ccc dotted;
        font-weight: 600;
    }
    .fa {
        color: #cc0000;
    }
    </style>
</head>
<body>

    <div class="container">
    <h2 align="center" class="form-signin-heading">Social Network Connector API </h2>
         <form class="form-signin">
                <h4 class="form-signin-heading">Login with Facebook</h4>
                <a href="<?= base_url('facebook_access/login') ?>" class="btn btn-lg btn-primary btn-block" role="button">Login</a>
        </form>

          <form class="form-signin">
                <h4 class="form-signin-heading">Login with Twitter</h4>
                <a href="<?= base_url('facebook_access/login') ?>" class="btn btn-lg btn-primary btn-block" role="button">Login</a>
        </form>

         <form class="form-signin">
                <h4 class="form-signin-heading">Login with Google +</h4>
                <a href="<?= base_url('facebook_access/login') ?>" class="btn btn-lg btn-primary btn-block" role="button">Login</a>
        </form>

        <div class="footer">
                <p>With <i class="fa fa-heart"></i> by YAS</a>.</p>
        </div>
    </div>

     <!-- /container -->
    <script type="text/javascript" src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
</body>
</html>