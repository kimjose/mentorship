<?php
require_once __DIR__ . "/../../vendor/autoload.php";
$baseUrl = $_ENV['APP_URL'];
?>
<section class="" id="sectionVisitSummary">

    <div class="header">

        <div class="row">
            <div class="col-3">
                <img class="logo" src="<?php echo $baseUrl ?>web/assets/img/visit.png" alt="MOH Logo" srcset="">
            </div>
            <div class="col-6">
                <h5 class="text-center">TA Visit Summary</h5>
                <h5 class="text-center">Embakasi Health Centre</h5>
                <h5 class="text-center">Visit Date: <span class="text-primary"> 2023-07-06 </span> </h5>
            </div>
            <div class="col-3">
                <img class="logo" src="<?php echo $baseUrl ?>web/assets/img/visit.png" alt="CIHEB Logo" srcset="">
            </div>
        </div>
    </div>
    <hr>
    <div class="body">

    </div>

</section>

<style>
    #sectionVisitSummary {
        padding: 8px;
        background-color: FFFEF2;
        background-blend-mode: luminosity;
        margin: 10px;
    }

    .logo {
        height: 80px;
        width: 80px:;
    }
</style>