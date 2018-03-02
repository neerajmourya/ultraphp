<?php

use ultraphp\core\Helper;
use ultraphp\core\MessageBoxes;
?>
<form action="<?php echo Helper::url('/user/store'); ?>" method="POST">
    <p>
        <?php if (MessageBoxes::hasBox('register-errors')): ?>
            <?php foreach (MessageBoxes::getBox('register-errors')->getBox() as $key => $errors): ?>
                <h4><?php echo $key; ?></h4>
                <?php foreach ($errors as $error) : ?>                
                    <?php echo $error; ?><br>
                <?php endforeach; ?>
            <?php endforeach; ?>
        <?php endif; ?>

    </p>
    <input type="hidden" name="_method" value="post">
    
    <label>First Name</label><br>
    <input name="first_name" type="text" value="<?php Helper::old('first_name', ''); ?>"><br>

    <label>Last Name</label><br>
    <input name="last_name" type="text" value="<?php Helper::old('last_name', ''); ?>"><br>

    <label>Email</label><br>
    <input name="email" type="text" value="<?php Helper::old('email', ''); ?>"><br>

    <label>Phone</label><br>
    <input name="phone" type="text" <?php Helper::old('phone', ''); ?>><br>

    <label>Amount</label><br>
    <input name="amount" type="text" <?php Helper::old('amount', ''); ?>><br>
    <button type="submit">Submit</button>
</form>