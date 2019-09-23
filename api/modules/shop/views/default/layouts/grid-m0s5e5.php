<div class="layout grid-m0s5e5">
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
    <div class="col-sub">
        <?php
        if(isset($layout_data['sub'])&&$layout_data['sub']){
        foreach($layout_data['sub'] as $value) {
        echo $value;
        }
        }?>
    </div>
    <div class="col-extra">
        <?php
        if(isset($layout_data['extra'])&&$layout_data['extra']){
        foreach($layout_data['extra'] as $value) {
        echo $value;
        }
        }?>
    </div>
</div>