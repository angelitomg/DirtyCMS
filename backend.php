<?php session_start(); 

    // Template functions
    function getHeader($system_active = ''){
        $str = '
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap 101 Template</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn\'t work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body class="container">
    
    <nav class="navbar navbar-default">
      <div class="container-fluid">

        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">Dirty CMS</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
          <ul class="nav navbar-nav">
            <li class="pages_active"><a href="#">Pages</a></li>
            <li class="files_active"><a href="#">Files</a></li>
            <li class="settings_active"><a href="#">Settings</a></li>
          </ul>
        </div><!-- /.navbar-collapse -->
      </div><!-- /.container-fluid -->
    </nav>';

        // Select active menu
        switch ($system_active){ 
            case 'pages': $str = str_replace('pages_active', 'active', $str); break;
            case 'files': $str = str_replace('files_active', 'active', $str); break;
            case 'settings': $str = str_replace('settings_active', 'active', $str); break;
        }

        return $str;
    }

    function getFooter(){
        $str .= '
    <!-- jQuery (necessary for Bootstrap\'s JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
  </body>
</html>'; 
        return $str;
    }

    function showPages(){
        $str = getHeader('pages');
        $str .= '
    <div class="panel panel-default">

      <!-- Default panel contents -->
      <div class="panel-heading"><a href="#" class="">New Page</a></div>

      <!-- Table -->
      <table class="table">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th></th>
        </tr>
        <tr>
            <td>1</td>
            <td><a href="#">about-us</a></td>
            <td>
                <a href="#" class="btn btn-primary">Delete</a>
            </td>
        </tr>
      </table>
    </div>';
        $str .= getFooter();
        echo $str;
    }

    function formPages(){
        $str = getHeader('pages');
        $str .= '
    <form>
        <div class="form-group">
          <input type="text" class="form-control input-lg" placeholder="Name">
        </div>

        <div class="form-group">
          <input type="text" class="form-control input-lg" placeholder="Title">
        </div>

        <div class="form-group">
          <textarea class="form-control input-lg" rows="3" placeholder="Description"></textarea>
        </div>

        <div class="form-group">
          <textarea name="content" class="form-control ckeditor input-lg" rows="3" placeholder="Content"></textarea>
        </div>

        <div class="form-group">
            <button class="btn btn-success btn-lg btn-block">Save</button>
        </div>

    </form>

    <script src="//cdn.ckeditor.com/4.4.7/standard/ckeditor.js"></script>
    <script>
        CKEDITOR.replace( content" );
    </script>';
        $str .= getFooter();
        echo $str;
    }

    function formSettings(){
        $str = getHeader('settings');
        $str .= '
    <form>
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" class="form-control input-lg" id="username">
        </div>
        <div class="form-group">
            <label for="pwd">Password</label>
            <input type="password" class="form-control input-lg" id="pwd">
        </div>
        <div class="form-group">
            <button class="btn btn-success btn-lg btn-block">Save Settings</button>
        </div>
    </form>
        ';
        $str .= getFooter();
        echo $str;
    }

    function formLogin(){
        $str = getHeader();
        $str .= '
    <form class="col-md-12">
        <div class="form-group">
            <input type="text" class="form-control input-lg" placeholder="dirty">
        </div>
        <div class="form-group">
            <input type="password" class="form-control input-lg" placeholder="123456">
        </div>
        <div class="form-group">
            <button class="btn btn-success btn-lg btn-block">Sign In</button>
        </div>
    </form>
        ';
        $str .= getFooter();
        echo $str;
    }

    // Router
    function init(){

        $action = (isset($_GET['action'])) ? $_GET['action'] : '';

        switch($action){
            case 'show-pages': showPages(); break;
            case 'form-pages': formPages(); break;
            case 'form-settings': formSettings(); break;
            case 'form-login': formLogin(); break;
        }

    }

    init();

?>