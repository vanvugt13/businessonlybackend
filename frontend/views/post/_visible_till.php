<?php

use frontend\models\Post;
?>
<?php if($model->isNewRecord):?>
<?= $form->field($model, 'visible_till_options')->radioList(Post::VisibleOptions()) ?>
<?php else:?>
<?= $form->field($model, 'visible_till')->textInput() ?>
<?php endif;?>
