<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2017/8/14
 * Time: 11:17
 */
?>
<?php if($model){?>
    <?php
    if($model->type_invoice == 2){
        $invoice_type = '企业增值税普通发票';
    }elseif($model->type_invoice == 3){
        $invoice_type = '企业增值税专用发票';
    }else{
        $invoice_type = '个人发票';
    }
    ?>
    <?php echo $invoice_type ?>
    <br>
    <?php echo $model->title_invoice ?>

<?php }else{?>
    <?php echo "不需要发票" ?>

<?php }?>
