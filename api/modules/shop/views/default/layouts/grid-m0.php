<div class="layout grid-m0 J_TLayout">
    <div class="col-main">
        <div class="main-wrap">
            <?php
             if(isset($layout_data['main'])&&$layout_data['main']){
            foreach($layout_data['main'] as $value) {
            echo $value;
            }
            }?>
        </div>
    </div>
</div>