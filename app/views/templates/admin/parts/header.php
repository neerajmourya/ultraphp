<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo $data['title']; ?></title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <?php foreach (UltraPHP::$styles as $style): ?>
            <link rel="stylesheet" href="<?php echo $style; ?>">
        <?php endforeach; ?>

        <!-- Favicon -->
        <link rel="icon" href="<?php echo ASSETS_URL; ?>images/favicon.png" sizes="32x32" type="image/png">
        <link rel="stylesheet" href="<?php echo ASSETS_ADMIN_URL; ?>styles/style.css">
        <style>
            body{
                font-family: Helvetica, Arial, "Open Sans";                
            }
            .text-center{
                text-align: center;
            }
        </style>
    </head><!--/head-->
    <body>
        <header>
            <div class="site-logo text-center"><img src="<?php echo ASSETS_URL; ?>images/logo.png"></div>
        </header>

        