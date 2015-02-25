<?php session_start(); 

    /**
     *
     * The MIT License (MIT)
     *
     * Copyright (c) 2015 Angelito M. Goulart
     *
     * Permission is hereby granted, free of charge, to any person obtaining a copy
     * of this software and associated documentation files (the "Software"), to deal
     * in the Software without restriction, including without limitation the rights
     * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
     * copies of the Software, and to permit persons to whom the Software is
     * furnished to do so, subject to the following conditions:
     *
     * The above copyright notice and this permission notice shall be included in all
     * copies or substantial portions of the Software.
     *
     * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
     * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
     * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
     * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
     * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
     * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
     * SOFTWARE.
     */

    // Configs

    error_reporting(0);
    define('DB_FILE', './db.sqlite');
    define('SALT', 'wCB8Z3x7LPv1bZvtAiyXqMFYQZALir');
    define('UPLOADS_PATH', 'uploads/');
    define('LANG', ''); // Default en. Optional pt-br.
    $status = array('type' => '', 'message' => '');
    $connection = null;

    // Translate

    $translate = array(
        'Pages' => array('pt-br' => 'Páginas'),'Files' => array('pt-br' => 'Arquivos'),
        'User' => array('pt-br' => 'Usuário'),
        'Logout' => array('pt-br' => 'Sair'),
        'Sign In' => array('pt-br' => 'Entrar'),
        'Login error.' => array('pt-br' => 'Erro ao efetuar login.'),
        'New Page' => array('pt-br' => 'Nova Página'),
        'Name' => array('pt-br' => 'Nome'),
        'Delete' => array('pt-br' => 'Excluir'),
        'Save' => array('pt-br' => 'Salvar'),
        'Title' => array('pt-br' => 'Título'),
        'Description' => array('pt-br' => 'Descrição'),
        'Content' => array('pt-br' => 'Conteúdo'),
        'Page saved successfully.' => array('pt-br' => 'Página salva com sucesso.'),
        'Error while saving page.' => array('pt-br' => 'Erro ao salvar página.'),
        'Page removed sucessfully.' => array('pt-br' => 'Página excluída com sucesso.'),
        'Error while removing page.' => array('pt-br' => 'Erro ao excluir página.'),
        'New File' => array('pt-br' => 'Novo Arquivo'),
        'File' => array('pt-br' => 'Arquivo'),
        'File uploaded sucessfully.' => array('pt-br' => 'Arquivo enviado com sucesso.'),
        'Error while saving file.' => array('pt-br' => 'Erro ao salvar arquivo.'),
        'Error while uploading file. Uploads path is writeable?' => array(
            'pt-br' => 'Erro ao enviar arquivo. O diretório de uploads tem permissão de escrita?'
        ),
        'Error while uploading file.' => array('pt-br' => 'Erro ao enviar arquivo.'),
        'File removed sucessfully.' => array('pt-br' => 'Arquivo excluído com sucesso.'),
        'Error while removing file.' => array('pt-br' => 'Erro ao excluir arquivo.'),
        'User updated sucessfully.' => array('pt-br' => 'Usuário atualizado com sucesso.'),
        'Error while updating user.' => array('pt-br' => 'Erro ao atualizar usuário.'),
        'Username' => array('pt-br' => 'Nome de usuário'),
        'Password' => array('pt-br' => 'Senha'),

    );

    function __($str){
        global $translate;
        return (isset($translate[$str][LANG])) ? $translate[$str][LANG] : $str;
    }

    // Template functions

    function getHeader($system_active = ''){
        global $status;
        $str = '
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dirty CMS - Backend</title>

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
          <a class="navbar-brand" href="backend.php">
            <span style="color: brown;font-weight: bold;">D</span>irty CMS
          </a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
          <ul class="nav navbar-nav">
            <li class="pages_active"><a href="backend.php?action=show-pages">' . __('Pages') . '</a></li>
            <li class="files_active"><a href="backend.php?action=show-files">' . __('Files') . '</a></li>
            <li class="user_active"><a href="backend.php?action=form-user">' . __('User') . '</a></li>
            ';

        if (isset($_SESSION['username'])) $str .= '
            <li><a href="backend.php?action=do-logout">' . __('Logout') . '</a></li>
        ';

        $str .= '
          </ul>
        </div><!-- /.navbar-collapse -->
      </div><!-- /.container-fluid -->
    </nav>';

        // Select active menu
        switch ($system_active){ 
            case 'pages': $str = str_replace('pages_active', 'active', $str); break;
            case 'files': $str = str_replace('files_active', 'active', $str); break;
            case 'user': $str = str_replace('user_active', 'active', $str); break;
        }

        // Show status message
        if (!empty($status['message'])) {
            $str .= '
    <div class="alert alert-' . $status['type'] . '">
        <a href="#" class="close" data-dismiss="alert">&times;</a>
        ' . __($status['message']) . '
    </div>';
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

    // Login functions

    function formLogin(){

        $str = getHeader();
        $str .= '
    <form class="col-md-12" action="?action=do-login" method="post">
        <div class="form-group">
            <input type="text" name="username" class="form-control input-lg" placeholder="dirty">
        </div>
        <div class="form-group">
            <input type="password" name="password" class="form-control input-lg" placeholder="123456">
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-success btn-lg btn-block">' . __('Sign In') . '</button>
        </div>
    </form>
        ';
        $str .= getFooter();
        echo $str;

    }

    function doLogin($post = array()){

        if (isset($_SESSION['username'])){
            showPages();
            poweroff();
        }

        global $connection;
        global $status;

        $username = (isset($post['username'])) ? $connection->escapeString($post['username']) : '';
        $password = (isset($post['password'])) ? $connection->escapeString($post['password']) : '';
        $password = sha1($password . SALT);

        $query = $connection->query("SELECT username FROM users WHERE username = '{$username}' AND password = '{$password}'");
        $query = $query->fetchArray();
        
        if (empty($query)){
            $status['type'] = 'danger';
            $status['message'] = 'Login error.';
            formLogin();
        } else {
            $_SESSION['username'] = $query['username'];
            showPages();
        }
        
    }

    function doLogout(){

        if (isset($_SESSION['username'])) unset($_SESSION['username']);
        formLogin();
        poweroff();

    }


    // Pages functions

    function showPages(){
        global $connection;
        $str = getHeader('pages');
        $str .= '
    <div class="panel panel-default">

      <!-- Default panel contents -->
      <div class="panel-heading"><a href="backend.php?action=form-pages" class="">' . __('New Page') . '</a></div>

      <!-- Table -->
      <table class="table">
        <tr>
            <th>' . __('ID') . '</th>
            <th>' . __('Name') . '</th>
            <th></th>
        </tr>';

        $query = $connection->query("SELECT id, name FROM pages ORDER BY name");
        while ($row = $query->fetchArray()){

            $str .= '
            <tr>
                <td>' . $row['id'] . '</td>
                <td><a href="backend.php?action=form-pages&id=' . $row['id'] . '">' . $row['name'] . '</a></td>
                <td>
                    <a href="backend.php?action=delete-page&id=' . $row['id'] . '" class="btn btn-primary">
                        ' . __('Delete') . '
                    </a>
                </td>
            </tr>
            ';
        }

        $str .= '
      </table>
    </div>';
        $str .= getFooter();
        echo $str;
    }

    function formPages($id = 0){

        global $connection;

        $data = array('name' => '', 'title' => '', 'description' => '', 'content' => '');

        $query = $connection->query("SELECT * FROM pages WHERE id = {$id}");
        $query = $query->fetchArray();

        if (!empty($query)) {
            $data['name']        = $query['name'];
            $data['title']       = $query['title'];
            $data['description'] = $query['description'];
            $data['content']     = $query['content'];
        }

        $str = getHeader('pages');
        $str .= '
    <form action="backend.php?action=save-page&id=' . $id . '" method="post">
        <div class="form-group">
          <input type="text" class="form-control input-lg" placeholder="' . __('Name') . '" name="name" 
            value="' . $data['name'] . '">
        </div>

        <div class="form-group">
          <input type="text" class="form-control input-lg" placeholder="' . __('Title') . '" name="title"
            value="' . $data['title'] . '">
        </div>

        <div class="form-group">
          <textarea class="form-control input-lg" rows="3" placeholder="' . __('Description') . '" 
            name="description">' . $data['description'] . '</textarea>
        </div>

        <div class="form-group">
          <textarea name="content" class="form-control ckeditor input-lg" 
          rows="3" placeholder="' . __('Content') . '" name="content">
        ' . $data['content'] . '
          </textarea>
        </div>

        <div class="form-group">
            <button class="btn btn-success btn-lg btn-block">' . __('Save') . '</button>
        </div>

    </form>

    <script src="//cdn.ckeditor.com/4.4.7/standard/ckeditor.js"></script>
    <script>
        CKEDITOR.replace( content" );
    </script>';
        $str .= getFooter();
        echo $str;
    }

    function savePage($post = array(), $id = 0){

        if (!in_array($_SERVER['REQUEST_METHOD'], array('POST', 'PUT'))){
            showPages();
            poweroff();
        }

        global $connection;
        global $status;

        $name        = (isset($post['name']))        ? $post['name']        : '';
        $title       = (isset($post['title']))       ? $post['title']       : '';
        $description = (isset($post['description'])) ? $post['description'] : '';
        $content     = (isset($post['content']))     ? $post['content']     : '';

        if ($id > 0) {
            $sql = "UPDATE pages SET name = '{$name}', title = '{$title}', description = '{$description}',
                    content = '{$content}' WHERE id = {$id}";

        } else {
            $sql = "INSERT INTO pages (name, title, description, content) 
                VALUES ('{$name}', '{$title}', '{$description}', '{$content}')";
        }

        if ($connection->exec($sql)) {
            $status['type'] = 'success';
            $status['message'] = 'Page saved successfully.'; 
        } else {
            $status['type'] = 'danger';
            $status['message'] = 'Error while saving page.';
        }

        showPages();

    }

    function deletePage($id){

        global $connection;
        global $status;

        $sql = "DELETE FROM pages WHERE id = {$id}";
        if ($connection->exec($sql)) {
            $status['type'] = 'success';
            $status['message'] = 'Page removed sucessfully.';
        } else {
            $status['type'] = 'danger';
            $status['message'] = 'Error while removing page.';
        }

        showPages();

    }

    // Upload functions

    function showFiles(){

        global $connection;

        $str = getHeader('files');
        $str .= '
    <div class="panel panel-default">

      <!-- Default panel contents -->
      <div class="panel-heading"><a href="backend.php?action=form-files" class="">' . __('New File') . '</a></div>

      <!-- Table -->
      <table class="table">
        <tr>
            <th>' . __('ID') . '</th>
            <th>' . __('Name') . '</th>
            <th></th>
        </tr>';

        $query = $connection->query("SELECT * FROM files ORDER BY name");
        while ($row = $query->fetchArray()){

            $str .= '
            <tr>
                <td>' . $row['id'] . '</td>
                <td><a target="_blank" href="' . UPLOADS_PATH . $row['filename'] . '">' . $row['name'] . '</a></td>
                <td>
                    <a href="backend.php?action=delete-file&id=' . $row['id'] . '" class="btn btn-primary">
                        ' . __('Delete') . '
                    </a>
                </td>
            </tr>
            ';
        }

        $str .= '
      </table>
    </div>';
        $str .= getFooter();
        echo $str;

    }

    function formFiles(){

        $str = getHeader('files');
        $str .= '
    <form action="backend.php?action=save-file" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="name">' . __('Name') . '</label>
            <input type="text" class="form-control input-lg" id="name" name="name"
            value="" required>
        </div>
        <div class="form-group">
            <label for="file">' . __('File') . '</label>
            <input type="file" class="form-control input-lg" id="file" name="file" required>
        </div>
        <div class="form-group">
            <button class="btn btn-success btn-lg btn-block">' . __('Save') . '</button>
        </div>
    </form>
        ';
        $str .= getFooter();
        echo $str;

    }

    function saveFile($post = array(), $file = array()){

        if (!in_array($_SERVER['REQUEST_METHOD'], array('POST', 'PUT'))){
            showFiles();
            poweroff();
        }

        global $connection;
        global $status;

        $name = (isset($post['name'])) ? $connection->escapeString($post['name']) : '';

        if (is_file($file['file']['tmp_name'])){

            $ext = explode('.', $file['file']['name']);
            $ext = end($ext);
            $ext = strtolower($ext);
            $filename = time() . '.' . $ext;

            if (!move_uploaded_file($file['file']['tmp_name'], UPLOADS_PATH . $filename)){
                $status['type'] = 'danger';
                $status['message'] = 'Error while uploading file. Uploads path is writeable?';
                showFiles();
                poweroff();
            } else {
                $sql = "INSERT INTO files (name, filename) VALUES ('{$name}', '{$filename}')";
                if ($connection->exec($sql)) {
                    $status['type'] = 'success';
                    $status['message'] = 'File uploaded sucessfully.';
                } else {
                    $status['type'] = 'danger';
                    $status['message'] = 'Error while saving file.';
                }
            }

        } else {
            $status['type'] = 'danger';
            $status['message'] = 'Error while uploading file.';
        }

        showFiles();

    }

    function deleteFile($id){

        global $connection;
        global $status;

        $query = $connection->query("SELECT filename FROM files WHERE id = {$id}")->fetchArray();
        if (!empty($query)){
            if (is_file(UPLOADS_PATH . $query['filename'])) unlink(UPLOADS_PATH . $query['filename']);
        }


        $sql = "DELETE FROM files WHERE id = {$id}";
        if ($connection->exec($sql)) {
            $status['type'] = 'success';
            $status['message'] = 'File removed sucessfully.';
        } else {
            $status['type'] = 'danger';
            $status['message'] = 'Error while removing file.';
        }

        showFiles();       

    }

    // User functions

    function formUser(){

        global $connection;

        $username = '';

        $query = $connection->query('SELECT username FROM users WHERE id = 1 LIMIT 1');
        $query = $query->fetchArray();

        if (!empty($query)) {
            $username = $query['username'];
        } 

        $str = getHeader('user');
        $str .= '
    <form action="backend.php?action=update-user" method="post">
        <div class="form-group">
            <label for="username">' . __('Username') . '</label>
            <input type="text" class="form-control input-lg" id="username" name="username"
            value="' . $username . '">
        </div>
        <div class="form-group">
            <label for="pwd">' . __('Password') . '</label>
            <input type="password" class="form-control input-lg" id="pwd" name="password" required>
        </div>
        <div class="form-group">
            <button class="btn btn-success btn-lg btn-block">' . __('Save') . '</button>
        </div>
    </form>
        ';
        $str .= getFooter();

        echo $str;

    }

    function updateUser($data = array()) {

        if (!in_array($_SERVER['REQUEST_METHOD'], array('POST', 'PUT'))){
            formUser();
            poweroff();
        }

        global $connection;
        global $status;

        $username = (isset($data['username'])) ? $connection->escapeString($data['username']) : '';
        $password = (isset($data['password'])) ? $connection->escapeString($data['password']) : '';
        $password = sha1($password . SALT);

        $sql = "UPDATE users SET username = '{$username}', password = '{$password}' WHERE id = 1";

        if ($connection->exec($sql)) {
            $status['type'] = 'success';
            $status['message'] = 'User updated sucessfully.';
        } else {
            $status['type'] = 'danger';
            $status['message'] = 'Error while updating user.';
        }

        formUser();

    } 

    // Router

    function init(){

        global $connection;
        $connection = new SQLite3(DB_FILE, SQLITE3_OPEN_READWRITE);     

        $action = (isset($_GET['action'])) ? $_GET['action'] : '';
        $id = (isset($_GET['id'])) ? (int) $_GET['id'] : 0;

        // Check login
        if (!isset($_SESSION['username']) && $action !== 'do-login'){
            formLogin();
            poweroff();
        }

        switch($action){

            case 'show-pages': showPages(); break;
            case 'form-pages': formPages($id); break;
            case 'save-page': savePage($_POST, $id); break;
            case 'delete-page': deletePage($id); break;

            case 'show-files': showFiles(); break;
            case 'form-files': formFiles(); break;
            case 'save-file': saveFile($_POST, $_FILES); break;
            case 'delete-file': deleteFile($id); break;

            case 'form-user': formUser(); break;
            case 'update-user': updateUser($_POST); break;

            case 'do-login': doLogin($_POST); break;
            case 'do-logout': doLogout(); break;

            default: showPages();

        }

    }

    // Poweroff function

    function poweroff($msg = ''){
        global $connection;
        $connection->close();
        die($msg);
    }

    // Start app

    init();
    poweroff();

?>
