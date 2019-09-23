          <?php if(isset($page_data['hd']) && $page_data['hd']){ ?>
            <div id="hd">
                <?php foreach($page_data['hd'] as $value){
                    echo $value;
                }?>
            </div>
          <?php } ?>
          <?php if(isset($page_data['bd']) && $page_data['bd']){ ?>
            <div id="bd" style="width:100%">
                <?php foreach($page_data['bd'] as $value){
                    echo $value;
                }?>
            </div>
          <?php } ?>
          <?php if(isset($page_data['ft']) && $page_data['ft']){ ?>
            <div id="ft">
                <?php foreach($page_data['ft'] as $value){
                    echo $value;
                }?>
            </div>
          <?php } ?>
