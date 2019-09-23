<?php
namespace backend\models\form;
use api\models\V1\Page;
use api\models\V1\PageDescription;
use yii\base\Model;
use Yii;
use yii\helpers\Html;

/**
 * AddressForm
 */
class PageForm extends Model {
	public $type;
	public $sort_order;
	public $status=1;
	public $image;
	public $title;
	public $description;
	public $meta_keyword;
	public $meta_description;
	public $_page;

	public function __construct($page_id = 0, $config = [])
	{
		$this->_page = Page::findOne(['page_id' => $page_id]);
		if ($this->_page) {
			$this->type = $this->_page->type;
			$this->sort_order =  $this->_page->sort_order;
			$this->status = $this->_page->status;
			$this->image = $this->_page->image;
			$this->title = $this->_page->description->title;
			$this->description = Html::decode($this->_page->description->description);
			$this->meta_keyword = $this->_page->description->meta_keyword;
			$this->meta_description = $this->_page->description->meta_description;
		}
		parent::__construct($config);
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['title', 'meta_keyword', 'meta_description'], 'required'],
			[['description', 'meta_keyword', 'meta_description'], 'string'],
			[['image','type','status','sort_order'],'safe']
		];
	}

	/**
	 * AddressForm
	 *
	 * @return address|null the saved model or null if saving fails
	 */
	public function save()
	{
		if ($this->validate()) {
			if (!$model = $this->_page) {
				$model = new Page();
				$model->date_added = date('Y-m-d H:i:s', time());
			}
			$model->type = $this->type;
			$model->sort_order = $this->sort_order;
			$model->status = $this->status;
			$model->image = $this->image;
			if($model->save()){
				if (!$description=PageDescription::findOne(['page_id'=>$model->page_id])) {
					$description=new PageDescription();
				}
				$description->page_id=$model->page_id;
				$description->title=$this->title;
				$description->description=Html::encode($this->description);
				$description->meta_keyword=$this->meta_keyword;
				$description->meta_description=$this->meta_description;
				$description->save();
			}
			return $model;
		}
		return null;
	}

	public function attributeLabels()
	{
		return ['type' => '类型',
			'sort_order' => '排序',
			'status' => '状态',
			'image' => '图片',
			'title' => '标题',
			'description'=>'内容',
			'meta_keyword'=>'关键词',
			'meta_description'=>'页面描述'
		];
	}
}
