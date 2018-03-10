<?php

use ultraphp\core\Helper;
use ultraphp\core\MessageBoxes;
?>
<?php Helper::view('layouts.header'); ?>
<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-push-4">
            <?php if (MessageBoxes::hasBox('register-errors')): ?>
                <?php foreach (MessageBoxes::getBox('register-errors')->getBox() as $key => $errors): ?>
                    <h4><?php echo $key; ?></h4>
                    <?php foreach ($errors as $error) : ?>                
                        <?php echo $error; ?><br>
                    <?php endforeach; ?>
                <?php endforeach; ?>
<?php endif; ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    LOGIN
                </div>
                <div class="panel-body">
                    <form name="login_form" action="<?php echo Helper::url('/authenticate'); ?>" method="POST">
                        <input type="hidden" name="_method" value="post"/>
                        <input type="hidden" name="_csrf" value="<?php echo \ultraphp\core\CSRF::token(); ?>"/>
                        <div class="form-group">
                            <input type="text" class="form-control" name="username" placeholder="USERNAME">
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" name="password" placeholder="PASSWORD">
                        </div>
                        <div class="form-group">
                            <button type="submit" name="submit" class="btn btn-primary btn-block">LOGIN</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php Helper::view('layouts.footer'); ?>