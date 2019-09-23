<?php
/**
* Single upload view.
*
* @var \yii\web\View $this View
* @var \yii\helpers\Html $input Hidden input
* @var string $input Hidden input
* @var string $selector Widget ID selector
* @var string $paramName The parameter name for the file form data
* @var string $value Current file name
* @var boolean $preview Enable/disable preview
* @var boolean $crop Enable/disable crop
* @var boolean $browseGlyphicon Show/Hide browse glyphicon
*/
use common\extensions\widgets\fileapi\Widget;
use yii\helpers\Html;
?>

<style>
.uploader-preview{
    width: 200px;
    height: 200px;
    margin: 0 auto;
    display: block;
    float:none;
    margin-bottom: 20px;
}
.jcrop-holder{margin:0 auto;}
</style>
<div id="<?= $selector; ?>" class="uploader clearfix w">
    
    <div class=" w   bc " style="min-height:350px;">
        <p class="tc  f14 gray9">
            点击 '浏览'  从本地选择一张图片仅支持JPG、PNG图片文件，且文件小于1M
        </p>
        <div class="bc w140 tc gray6 p20">
        <span data-fileapi="preview" class="uploader-preview-wrapper"></span>
        </div>
        <div class="btn orgBtn uploadAvaBtn dib js-fileapi-wrapper">
            <div class="uploader-browse" data-fileapi="active.hide">
                <?php if ($browseGlyphicon === true) : ?>
                <span class="glyphicon glyphicon-picture"></span> 浏览
                <?php endif; ?>
                <span data-fileapi="browse-text" class="none <?= $value ? 'hidden' : 'browse-text' ?>">
                    <?= Widget::t('fileapi', 'BROWSE_BTN') ?>
                </span>
                <input type="file" name="<?= $paramName ?>">
            </div>
            <div class="uploader-progress" data-fileapi="active.show">
                <div class="progress progress-striped">
                    <div class="uploader-progress-bar progress-bar progress-bar-info" data-fileapi="progress" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
        <div class="dib">
            <?= Html::submitButton('保存', ['class' => 'w100 btn grayBtn ']) ?>
        </div>
        <?= $input ?>
    </div>
</div>

<?php if ($crop === true) : ?>
    <div id="modal-crop" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel"><?= Widget::t('fileapi', 'MODAL_TITLE') ?></h4>
                </div>
                <div class="modal-body">
                    <div id="modal-preview"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?= Widget::t('fileapi', 'MODAL_CANCEL') ?></button>
                    <button type="button" class="btn btn-primary crop"><?= Widget::t('fileapi', 'MODAL_SAVE') ?></button>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>