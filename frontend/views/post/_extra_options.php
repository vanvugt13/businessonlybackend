<?php

use frontend\models\Post;

if(in_array(Post::CATEGORY_EVENTS,$model->category_array)){
    ?>
    <div class="panel panel-default">
  <div class="panel-heading"><b>Evenement</b></div>
  <div class="panel-body">
    <?=$form->field($model,'subscribe_event')->checkbox();?>
    <?=$form->field($model,'event_date_formatted')->textInput();?>
  </div>
</div>
<?php
}
if(in_array(Post::CATEGORY_NEWS,$model->category_array)){
    echo "<b>Nieuws</b><br>";
    echo $form->field($model,'subscribe_news')->checkbox();
   }
   if(in_array(Post::CATEGORY_POST,$model->category_array)){
   // echo "het is post";
   }

?>