
<?php if(isset($data['customer'])) { ?>
<div class="container-fulid">
    <div style="padding:15px;">
        <div class="media">
            <div class="media-left" style="display: inline-block">
                <a href="#">
                    <img class="media-object" src="<?php echo $data['customer']['avatar']; ?>" height="60" width="60" alt="user_avatar">
                </a>
            </div>
            <div class="media-body" style="display: inline-block; margin:0 0 12px 12px;">
                <h4 class="media-heading" style="font-size: 20px;"><?php echo $data['customer']['user_name']; ?></h4>
                <small>普通用户</small>
            </div>
        </div>
    </div>
</div>
<?php } ?>
<div role="tabpanel">
    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
        <!-- <li role="presentation"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">浏览记录</a></li> -->
        <li role="presentation" class="active"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">最近订单</a></li>
        <!-- <li role="presentation"><a href="#messages" aria-controls="messages" role="tab" data-toggle="tab">用户心愿单</a></li>
        <li role="presentation"><a href="#settings" aria-controls="settings" role="tab" data-toggle="tab">Settings</a></li> -->
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="profile">
            <?php if(isset($data['orders']) && $data['orders'] ) { ?>
            <ul class="list-group">
                <?php foreach($data['orders'] as $order){ ?>
                <li class="list-group-item">
                    <p class="list-group-item-text" style="line-height: 2"><span class="pull-right"><?php echo $order['order_status'];?></span>订单号：<a href="#" style="color: #428bca" data-toggle="modal" data-target="#myModal" class="show_order_detail"><?php echo $order['order_id'];?></a> 订单编号：<?php echo $order['order_number'];?> 订单时间：<?php echo $order['data_added'];?> 
                        <?php if($order['shipping']) { ?>
                            <br>收货人：<?php echo $order['shipping']['shipping_username'];?>
                            手机号码：<?php echo $order['shipping']['shipping_telephone'];?>
                            <br>收货地址：<?php echo $order['shipping']['shipping_address'];?>
                        <?php } ?>
                    </p>
                </li>
                <?php } ?>
            </ul>
            <?php }else{ ?>
                <div class="alert alert-info" role="alert">暂无记录</div>
            <?php } ?>
        </div>
        <!-- <div role="tabpanel" class="tab-pane" id="settings">...</div> -->
    </div>
</div>
