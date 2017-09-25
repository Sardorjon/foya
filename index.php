<?php
$page_title="FOYA24 - Bosh sahifa";
include_once 'partials/headers.php';
include_once 'resource/Utilities.php';
?>

    <div class="container">
               
      <div class="starter">
        <h1>FOYA24 - Ajoyib Narsa Sodir Bölishi Kerak.</h1>
        <p class="lead">FOYA orzularingizni amalga oshirishda yordam beradi! Siz öylaganingizdan köprog'iga           qodirsiz!</p>
          
        <?php if(!isset($_SESSION['username'])):?>
        <p class="lead"> Siz hozirda saytga ulanmagansiz <a href="login.php">Ulaning!</a> Hali saytga a'zo emasmisiz?! <a href="signup.php">A'zo böling!</a>
        </p>
        <?php else: ?>
        <p class="lead"> Siz saytga <?php if(isset($_SESSION['username'])) echo $_SESSION ['username']; ?> sifatida ulandingiz. <a href="logout.php">Saytdan chiqish</a>    
        </p>
        <?php endif ?>
                          
      </div>
       
    </div>         
<?php include_once 'partials/footers.php'; ?>
</body>
</html>