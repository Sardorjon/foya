<?php include_once 'resource/Session.php'; ?>
<?php include_once 'resource/Database.php'; ?>
<?php include_once 'resource/Utilities.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <title> <?php if(isset($page_title)) echo $page_title; ?></title>
    
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
    <link rel="stylesheet" href="custom.css">
    <link rel="stylesheet" type="text/css" href="sweetalert.css">
    <script src="sweetalert.min.js"></script>
</head>
<body>    
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
      <a class="navbar-brand" href="index.php">FOYA24</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarsExampleDefault">
        <ul class="navbar-nav mr-auto"><i class="hide"><?php echo safety();?></i>
          <li>
              <a class="nav-link" href="index.php">Bosh sahifa<span class="sr-only">(current)</span></a> 
          </li>
          <?php if((isset($_SESSION['username'])|| isCookieValid($db))):?>
          <li>
            <a class="nav-link" href="profile.php">Mening Profilim</a>
          </li>
          <li>
            <a class="nav-link" href="logout.php">Saytdan chiqish</a>
          </li>
          <?php else: ?>
          <li>
            <a class="nav-link" href="login.php">Saytga ulanish</a>
          </li>
          <li>
            <a class="nav-link" href="signup.php">Saytga a'zo bölish</a>
          </li>
          <li class="dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Qöshimcha</a>
            <div class="dropdown-menu" aria-labelledby="dropdown01">
              <a class="dropdown-item" href="#">Biz haqimizda</a>
              <a class="dropdown-item" href="#">Bizga bog'lanish</a>              
            </div>
          </li>
          <?php endif ?>          
        </ul>
        <form class="form-inline my-2 my-lg-0">
          <input class="form-control mr-sm-2" type="text" placeholder="Search" aria-label="Qidirmoq">
          <button class="btn btn-outline-success my-2 my-md-0" type="submit">Qidirmoq</button>
        </form>
      </div>
    </nav>