<?php 
$page_title ="FOYA24 - Account Activation";
include_once 'partials/headers.php';
include_once 'partials/parsesignup.php';
?>
<div class="container">
    <div class="flag">
        <h1 style="text-align:center;">FOYA24 - Ajoyib Narsa Sodir Bölishi Kerak!</h1><br>
        <?php if(isset($result)) echo $result; ?>
    </div>
</div>
<?php include_once 'partials/footers.php'; ?>
</body>
</html>