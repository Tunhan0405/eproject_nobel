<?php
require('layout/head.php');
require 'controller/laureatesController.php';
?>

<!DOCTYPE html>
<?php

require('layout/header.php')
?>


<main>

    <div class="body-main">
        <div class="container">
            <h2 class="mt-5">
                <?php
                echo $cate['name'] ?? "All The Lảu"
                ?>
            </h2>
            <p> <?php
                echo $cate['description'] ?? "";
                ?>
            </p>

            <div class="row">

                <?php

                renderLaureatesByCategory();
                ?>
            </div>
        </div>

</main>
</div>

<?php
require('layout/footer.php')
?>