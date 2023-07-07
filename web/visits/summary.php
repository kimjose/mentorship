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
        <div id="divChecklists">
            <h4>Departments</h4>
            <div class="row mt-3">
                <div class="col-6">
                    <ol>
                        <li>
                            <div width="100%" class="divListItem">
                                .
                            </div>
                        </li>
                        <li>
                            <div width="100%" class="divListItem">
                                .
                            </div>
                        </li>
                        <li>
                            <div width="100%" class="divListItem">
                                .
                            </div>
                        </li>
                        <li>
                            <div width="100%" class="divListItem">
                                .
                            </div>
                        </li>
                        <li>
                            <div width="100%" class="divListItem">
                                .
                            </div>
                        </li>
                    </ol>
                </div>
                <div class="col-6">
                    <ol>
                        <li>
                            <div width="100%" class="divListItem">
                                .
                            </div>
                        </li>
                        <li>
                            <div width="100%" class="divListItem">
                                .
                            </div>
                        </li>
                        <li>
                            <div width="100%" class="divListItem">
                                .
                            </div>
                        </li>
                        <li>
                            <div width="100%" class="divListItem">
                                .
                            </div>
                        </li>
                        <li>
                            <div width="100%" class="divListItem">
                                .
                            </div>
                        </li>
                    </ol>
                </div>
            </div>
        </div>

        <div id="divSummaryFindings">
            <div >
                <h4>Findings</h4>
                <div class="mt-3">
                    <ol>
                        <li>
                            <div width="100%" class="divListItem">
                                .
                            </div>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

</section>

<style>
    #sectionVisitSummary {
        padding: 8px;
        background-color: FFFEF2 !important;
        background-blend-mode: luminosity;
        margin: 10px;
    }

    .logo {
        height: 80px;
        width: 80px;
    }

    #divChecklists ol,
    #divSummaryFindings ol {
        list-style: disc;
    }

    #divChecklists .divListItem, #divSummaryFindings .divListItem {
        border-bottom: #000F00 1px solid;
    }
</style>