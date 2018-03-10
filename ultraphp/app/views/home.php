<?php use ultraphp\core\Helper; ?>
<?php use ultraphp\core\Auth; ?>
<?php Helper::view("layouts.header"); ?>
<div class="text-center" style="margin-top:100px;">
    <img src="<?php echo Helper::url("/assets/images/logo.png"); ?>">
    <h1>ULTRAPHP 2.0</h1>
        
    <?php if(Auth::$user): ?>        
        Welcome <?php echo Auth::$user->username; ?>
    <?php else: ?>
        Welcome Guest
    <?php endif; ?>
</div>
<?php Helper::view("layouts.footer"); ?>