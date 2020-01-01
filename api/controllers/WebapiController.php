<?php
namespace api\controllers;
use api\models\V1\Advertise;
use api\models\V1\AdvertiseDetail;
use api\models\V1\AdvertiseMedia;
use api\models\V1\AdvertisePosition;
use api\models\V1\AdvertiseRelated;
use api\models\V1\AffiliatePersonal;
use api\models\V1\AffiliatePersonalDetail;
use api\models\V1\Appuser;
use api\models\V1\Attribute;
use api\models\V1\AttributeDescription;
use api\models\V1\AttributeGroup;
use api\models\V1\AttributeGroupDescription;
use api\models\V1\Business;
use api\models\V1\Category;
use api\models\V1\CategoryDescription;
use api\models\V1\CategoryStore;
use api\models\V1\CategoryStoreToProduct;
use api\models\V1\Country;
use api\models\V1\Coupon;
use api\models\V1\CouponCard;
use api\models\V1\CouponCate;
use api\models\V1\CouponCateToCoupon;
use api\models\V1\CouponGift;
use api\models\V1\CouponProduct;
use api\models\V1\District;
use api\models\V1\GroundPushPlan;
use api\models\V1\GroundPushPlanView;
use api\models\V1\GroundPushPoint;
use api\models\V1\GroundPushStock;
use api\models\V1\GroundPushStockLog;
use api\models\V1\Industry;
use api\models\V1\IndustryStoreCategory;
use api\models\V1\LegalPerson;
use api\models\V1\Manufacturer;
use api\models\V1\Option;
use api\models\V1\OptionDescription;
use api\models\V1\OptionValue;
use api\models\V1\OptionValueDescription;
use api\models\V1\Order;
use api\models\V1\OrderCycle;
use api\models\V1\OrderHistory;
use api\models\V1\OrderProduct;
use api\models\V1\OrderStatus;
use api\models\V1\Platform;
use api\models\V1\PlatformStation;
use api\models\V1\Product;
use api\models\V1\ProductBase;
use api\models\V1\ProductBaseAttribute;
use api\models\V1\ProductBaseDescription;
use api\models\V1\ProductBaseToCategory;
use api\models\V1\ProductImage;
use api\models\V1\ProductOption;
use api\models\V1\ProductOptionValue;
use api\models\V1\Promotion;
use api\models\V1\PromotionDetail;
use api\models\V1\PromotionDetailGift;
use api\models\V1\PromotionGroup;
use api\models\V1\PromotionOrder;
use api\models\V1\PromotionProductGroup;
use api\models\V1\PromotionProductGroupDetail;
use api\models\V1\ReturnBase;
use api\models\V1\ReturnHistory;
use api\models\V1\ReturnProduct;
use api\models\V1\ReturnStatus;
use api\models\V1\ReturnTotal;
use api\models\V1\SalePromotion;
use api\models\V1\SalePromotionDetail;
use api\models\V1\SalePromotionDetailExclude;
use api\models\V1\SalePromotionDetailGift;
use api\models\V1\SalePromotionDetailGiftToRule;
use api\models\V1\SalePromotionDetailProduct;
use api\models\V1\SalePromotionDetailToRule;
use api\models\V1\SaleRule;
use api\models\V1\SaleRuleAttribute;
use api\models\V1\SaleSubject;
use api\models\V1\SellerMessage;
use api\models\V1\Store;
use api\models\V1\StoreAttribute;
use api\models\V1\StoreDelivery;
use api\models\V1\StoreDeliveryDetail;
use api\models\V1\StoreDescription;
use api\models\V1\StoreTheme;
use api\models\V1\StoreThemeColumn;
use api\models\V1\StoreThemeColumnInfo;
use api\models\V1\StoreToWarehouse;
use api\models\V1\Subject;
use api\models\V1\Theme;
use api\models\V1\ThemeColumn;
use api\models\V1\Warehouse;
use api\models\V1\City;
use api\models\V1\Zone;
use api\modules\oauth2\filters\auth\CompositeAuth;
use common\component\Helper\Helper;
use common\component\Message\Msg;
use yii\base\Exception;
use yii\db\StaleObjectException;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\QueryParamAuth;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

class WebapiController extends \yii\rest\Controller {
	/**
	 * @inheritdoc
	 */
	public $status = 'FAIL';
	public $code = '500';
	public $message = '';
	public $data = [];

	public function actionIndex()
	{
		$Post_data = file_get_contents("php://input");
		if ($data = Json::decode($Post_data)) {
			if ($this->checkUser($data)) {
				if ($this->runAction(strtolower($data['a']), ['data' => $data['d']])) {
					$this->status = "OK";
					$this->code = 200;
				}
			}
		}
		return $this->response();
	}

	public function checkUser($data)
	{
		$model = Appuser::findOne(['appuser_code' => $data['m']]);
		if ($model) {
			if ($data['k'] == $this->encryptAppKey($data['t'] . $model->appuser_code . $model->appuser_key)) {
				return true;
			} else {
				$this->code = 500;
				$this->message = "接口认证失败";
				return false;
			}
		} else {
			$this->code = 403;
			$this->message = "用户不存在";
			return false;
		}
	}

	public function encryptAppKey($key)
	{
		return md5($key);
	}

	public function response()
	{
		return ['status' => $this->status, 'code' => $this->code, 'message' => $this->message, 'data' => $this->data];
	}

	//国家信息
	public function actionCountry($datas)
	{
		$transaction = \Yii::$app->db->beginTransaction();
		$status = true;
		try {
			foreach ($datas as $data) {
				if (!$model = Country::findOne(['country_code' => $data['CODE']])) {
					$model = new Country();
				}
				$model->country_code = $data['CODE'];
				$model->name = $data['NAME'];
				$model->status = $data['STATUS'] == "ACTIVE" ? 1 : 0;
				if (!$model->save()) {
					throw new \Exception(json_encode($model->errors));
				}
			}
			$transaction->commit();
		} catch (\Exception $e) {
			$status = false;
			$this->message = $e->getMessage();
			$transaction->rollBack();
		}
		return $status;
	}

	//省份信息
	public function actionZone($datas)
	{
		$transaction = \Yii::$app->db->beginTransaction();
		$status = true;
		try {
			foreach ($datas as $data) {
				if (!$model = Zone::findOne(['code' => $data['CODE']])) {
					$model = new Zone();
				}
				$Country = Country::findOne(['country_code' => $data['COUNTYE']]);
				$model->country_id = $Country ? $Country->country_id : 0;
				$model->country_code = $data['COUNTYE'];
				$model->name = $data['NAME'];
				$model->status = $data['STATUS'] == "ACTIVE" ? 1 : 0;
				if (!$model->save()) {
					throw new \Exception(json_encode($model->errors));
				}
			}
			$transaction->commit();
		} catch (\Exception $e) {
			$status = false;
			$this->message = $e->getMessage();
			$transaction->rollBack();
		}
		return $status;
	}

	//市级信息
	public function actionCity($datas)
	{
		$transaction = \Yii::$app->db->beginTransaction();
		$status = true;
		try {
			foreach ($datas as $data) {
				if (!$model = City::findOne(['code' => $data['CODE']])) {
					$model = new City();
				}
				$Zone = Zone::findOne(['code' => $data['PROVINCE']]);
				$model->zone_id = $Zone ? $Zone->zone_id : 0;
				$model->zone_code = $data['PROVINCE'];
				$model->name = $data['NAME'];
				$model->status = $data['STATUS'] == "ACTIVE" ? 1 : 0;
				if (!$model->save()) {
					throw new \Exception(json_encode($model->errors));
				}
			}
			$transaction->commit();
		} catch (\Exception $e) {
			$status = false;
			$this->message = $e->getMessage();
			$transaction->rollBack();
		}
		return $status;
	}

	//区级信息
	public function actionDistrict($datas)
	{
		$transaction = \Yii::$app->db->beginTransaction();
		$status = true;
		try {
			foreach ($datas as $data) {
				if (!$model = District::findOne(['code' => $data['CODE']])) {
					$model = new District();
				}
				$City = City::findOne(['code' => $data['CITY']]);
				$model->city_id = $City ? $City->city_id : 0;
				$model->city_code = $data['CITY'];
				$model->name = $data['NAME'];
				$model->status = $data['STATUS'] == "ACTIVE" ? 1 : 0;
				if (!$model->save()) {
					throw new \Exception(json_encode($model->errors));
				}
			}
			$transaction->commit();
		} catch (\Exception $e) {
			$status = false;
			$this->message = $e->getMessage();
			$transaction->rollBack();
		}
		return $status;
	}

	//行业信息
	public function actionIndustory($datas)
	{
		$transaction = \Yii::$app->db->beginTransaction();
		$status = true;
		try {
			foreach ($datas as $data) {
				if (!$model = Industry::findOne(['industry_code' => $data['CODE']])) {
					$model = new Industry();
					$model->date_added = $data['UPDATETIME'];
				}
				$model->industry_code = $data['CODE'];
				$model->name = $data['NAME'];
				$model->status = $data['STATUS'] == "ACTIVE" ? 1 : 0;
				$model->date_modified = $data['UPDATETIME'];
				if (!$model->save()) {
					throw new \Exception(json_encode($model->errors));
				}
			}
			$transaction->commit();
		} catch (\Exception $e) {
			$status = false;
			$this->message = $e->getMessage();
			$transaction->rollBack();
		}
		return $status;
	}

	//平台信息
	public function actionPlatform($datas)
	{
		$transaction = \Yii::$app->db->beginTransaction();
		$status = true;
		try {
			foreach ($datas as $data) {
				if (!$model = Platform::findOne(['platform_code' => $data['CODE']])) {
					$model = new Platform();
					$model->date_added = $data['UPDATETIME'];
				}
				$model->platform_code = $data['CODE'];
				$model->platform_name = $data['NAME'];
				$model->status = $data['STATUS'] == "ACTIVE" ? 1 : 0;
				$model->date_modified = $data['UPDATETIME'];
				if (!$model->save()) {
					throw new \Exception(json_encode($model->errors));
				}
			}
			$transaction->commit();
		} catch (\Exception $e) {
			$status = false;
			$this->message = $e->getMessage();
			$transaction->rollBack();
		}
		return $status;
	}

	//法人信息
	public function actionLegalperson($datas)
	{
		$transaction = \Yii::$app->db->beginTransaction();
		$status = true;
		try {
			foreach ($datas as $data) {
				if (!$model = LegalPerson::findOne(['legal_no' => $data['CODE']])) {
					$model = new LegalPerson();
				}
				$model->legal_no = $data['CODE'];
				$model->name = $data['NAME'];
				$model->status = $data['STATUS'] == "ACTIVE" ? 1 : 0;
				$model->date_modified = $data['UPDATETIME'];
				if (!$model->save()) {
					throw new \Exception(json_encode($model->errors));
				}
			}
			$transaction->commit();
		} catch (\Exception $e) {
			$status = false;
			$this->message = $e->getMessage();
			$transaction->rollBack();
		}
		return $status;
	}

	//店铺分类
	public function actionIndustorycategory($datas)
	{
		$transaction = \Yii::$app->db->beginTransaction();
		$status = true;
		try {
			foreach ($datas as $data) {
				if (!$model = IndustryStoreCategory::findOne(['industry_store_category_code' => $data['CODE']])) {
					$model = new IndustryStoreCategory();
				}
				$model->industry_store_category_code = $data['CODE'];
				$model->name = $data['NAME'];
				$Industry = Industry::findOne(['industry_code' => $data['INDUSTRYCODE']]);
				$model->industry_id = $Industry ? $Industry->industry_id : 0;
				$model->industry_code = $data['INDUSTRYCODE'];
				$model->status = $data['STATUS'] == "ACTIVE" ? 1 : 0;
				if (!$model->save()) {
					throw new \Exception(json_encode($model->errors));
				}
			}
			$transaction->commit();
		} catch (\Exception $e) {
			$status = false;
			$this->message = $e->getMessage();
			$transaction->rollBack();
		}
		return $status;
	}

	//商圈信息
	public function actionBusinesszone($datas)
	{
		$transaction = \Yii::$app->db->beginTransaction();
		$status = true;
		try {
			foreach ($datas as $data) {
				if (!$model = Business::findOne(['code' => $data['CODE']])) {
					$model = new Business();
				}
				$model->code = $data['CODE'];
				$model->name = $data['NAME'];
				$model->status = $data['STATUS'] == "ACTIVE" ? 1 : 0;
				if (!$model->save()) {
					throw new \Exception(json_encode($model->errors));
				}
			}
			$transaction->commit();
		} catch (\Exception $e) {
			$status = false;
			$this->message = $e->getMessage();
			$transaction->rollBack();
		}
		return $status;
	}

	//模板信息
	public function actionTemplate($datas)
	{
		$transaction = \Yii::$app->db->beginTransaction();
		$status = true;
		try {
			foreach ($datas as $data) {
				if (!$model = Theme::findOne(['theme_code' => $data['CODE']])) {
					$model = new Theme();
				}
				$model->theme_code = $data['CODE'];
				$model->theme_name = $data['NAME'];
				$model->theme_color_code = $data['COLORCODE'];
				$model->type = isset($data['TYPE']) ? $data['TYPE'] : 'WEB';
				$model->status = $data['STATUS'] == "ACTIVE" ? 1 : 0;
				if (!$model->save()) {
					throw new \Exception(json_encode($model->errors));
				}
				$theme_id = $model->theme_id;
				if (isset($data['DETAILS']) && $data['DETAILS']) {
					foreach ($data['DETAILS'] as $value) {
						if (!$model = ThemeColumn::findOne(['theme_column_code' => $value['CODE'], 'theme_id' => $theme_id])) {
							$model = new ThemeColumn();
						}
						$model->theme_column_code = $value['CODE'];
						$model->name = $value['NAME'];
						$model->theme_id = $theme_id;
						$model->type = $value['TYPE'];
						$model->rowslimit = $value['ROWQTY'];
						$model->remark = $value['REMARK'];
						$model->status = $data['STATUS'] == "ACTIVE" ? 1 : 0;
						if (!$model->save()) {
							throw new \Exception(json_encode($model->errors));
						}
					}
				}
			}
			$transaction->commit();
		} catch (\Exception $e) {
			$status = false;
			$this->message = $e->getMessage();
			$transaction->rollBack();
		}
		return $status;
	}

	//仓库信息
	public function actionWarehouse($datas)
	{
		$transaction = \Yii::$app->db->beginTransaction();
		$status = true;
		try {
			foreach ($datas as $data) {
				if (!$model = Warehouse::findOne(['warehouse_code' => $data['CODE']])) {
					$model = new Warehouse();
					$model->date_added = $data['UPDATETIME'];
				}
				$model->warehouse_code = $data['CODE'];
				$model->name = $data['NAME'];
				$Platform = Platform::findOne(['platform_code' => $data['MARKETCODE']]);
				$model->platform_id = $Platform ? $Platform->platform_id : 0;
				$model->platform_code = $data['MARKETCODE'];
				$model->status = $data['STATUS'] == "ACTIVE" ? 1 : 0;
				$model->date_modified = $data['UPDATETIME'];
				if (!$model->save()) {
					throw new \Exception(json_encode($model->errors));
				}
			}
			$transaction->commit();
		} catch (\Exception $e) {
			$status = false;
			$this->message = $e->getMessage();
			$transaction->rollBack();
		}
		return $status;
	}

	//分类信息
	public function actionCategory($datas)
	{
		$transaction = \Yii::$app->db->beginTransaction();
		$status = true;
		try {
			foreach ($datas as $data) {
				if (!$model = Category::findOne(['code' => $data['CODE']])) {
					$model = new Category();
					$model->date_added = $data['UPDATETIME'];
				}
				$model->code = $data['CODE'];
				$Category = Category::findOne(['code' => $data['PARENTCODE']]);
				$model->parent_id = $Category ? $Category->category_id : 0;
				$model->parentcode = $data['PARENTCODE'];
				$AttributeGroup = AttributeGroup::findOne(['attribute_group_code' => $data['ATTRIBUTEGROUPCODE']]);
				$model->attribute_group_id = $AttributeGroup ? $AttributeGroup->attribute_group_id : 0;
				$Industry = Industry::findOne(['industry_code' => $data['INDUSTORYCODE']]);
				$model->industry_id = $Industry ? $Industry->industry_id : 0;
				$model->industry_code = $data['INDUSTORYCODE'];
				$model->status = $data['STATUS'] == "ACTIVE" ? 1 : 0;
				$model->date_modified = $data['UPDATETIME'];
                $model->stock_type = $data['STOCKTYPE'] ? $data['STOCKTYPE'] : 'NUMBER';
                $model->low_limit = $data['LOWLIMIT'];//当小于该值的时候，且StockType = DESCRIPTION 时，前台库存显示紧张
				if (!$model->save()) {
					throw new \Exception(json_encode($model->errors));
				}
				$category_id = $model->category_id;
				if (!$model = CategoryDescription::findOne(['category_id' => $category_id])) {
					$model = new CategoryDescription();
				}
				$model->code = $data['CODE'];
				$model->category_id = $category_id;
				$model->language_id = 2;
				$model->name = $data['NAME'];
				$model->description = $data['DESCRIPTION'];


				if (!$model->save()) {
					throw new \Exception(json_encode($model->errors));
				}
			}
			$transaction->commit();
		} catch (\Exception $e) {
			$status = false;
			$this->message = $e->getMessage();
			$transaction->rollBack();
		}
		return $status;
	}

	//属性
	public function actionAttribute($datas)
	{
		$transaction = \Yii::$app->db->beginTransaction();
		$status = true;
		try {
			foreach ($datas as $data) {
				if (!$model = AttributeGroup::findOne(['attribute_group_code' => $data['CODE']])) {
					$model = new AttributeGroup();
				}
				$model->attribute_group_code = $data['CODE'];
				if (!$model->save()) {
					throw new \Exception(json_encode($model->errors));
				}
				$attribute_group_id = $model->attribute_group_id;
				if (!$model = AttributeGroupDescription::findOne(['attribute_group_id' => $attribute_group_id])) {
					$model = new AttributeGroupDescription();
				}
				$model->attribute_group_id = $attribute_group_id;
				$model->attribute_group_code = $data['CODE'];
				$model->language_id = 2;
				$model->name = $data['NAME'];
				if (!$model->save()) {
					throw new \Exception(json_encode($model->errors));
				}
				if (isset($data['ATTRIBUTES']) && $data['ATTRIBUTES']) {
					foreach ($data['ATTRIBUTES'] as $attribute) {
						if (!$model = Attribute::findOne(['attribute_code' => $attribute['CODE'], 'attribute_group_id' => $attribute_group_id])) {
							$model = new Attribute();
						}
						$model->attribute_code = $attribute['CODE'];
						$model->attribute_group_id = $attribute_group_id;
						$model->attribute_group_code = $data['CODE'];
						$model->is_search = $attribute['ISSEARCH'] ? 1 : 0;
						if (!$model->save()) {
							throw new \Exception(json_encode($model->errors));
						}
						$attribute_id = $model->attribute_id;
						if (!$model = AttributeDescription::findOne(['attribute_id' => $attribute_id])) {
							$model = new AttributeDescription();
						}
						$model->attribute_id = $attribute_id;
						$model->attribute_code = $attribute['CODE'];
						$model->name = $attribute['NAME'];
						$model->attribute_group_id = $attribute_group_id;
						$model->attribute_group_code = $data['CODE'];
						$model->language_id = 2;
						if (!$model->save()) {
							throw new \Exception(json_encode($model->errors));
						}
					}
				}
			}
			$transaction->commit();
		} catch (\Exception $e) {
			$status = false;
			$this->message = $e->getMessage();
			$transaction->rollBack();
		}
		return $status;
	}

	//包装规格属性
	public function actionOption($datas)
	{
		$transaction = \Yii::$app->db->beginTransaction();
		$status = true;
		try {
			foreach ($datas as $data) {
				if (!$model = Option::findOne(['code' => $data['CODE']])) {
					$model = new Option();
				}
				$model->code = $data['CODE'];
				$model->type = 'select';
				$model->sort_order = 0;
				if (!$model->save()) {
					throw new \Exception(json_encode($model->errors));
				}
				$option_id = $model->option_id;
				if (!$model = OptionDescription::findOne(['option_id' => $option_id])) {
					$model = new OptionDescription();
				}
				$model->option_id = $option_id;
				$model->code = $data['CODE'];
				$model->group_name = $data['NAME'];
				$model->language_id = 2;
				$model->name = $data['TITLE'];
				if (!$model->save()) {
					throw new \Exception(json_encode($model->errors));
				}
				if (isset($data['ATTRIBUTES']) && $data['ATTRIBUTES']) {
					foreach ($data['ATTRIBUTES'] as $attribute) {
						if (!$model = OptionValue::findOne(['option_id' => $option_id, 'option_value_code' => $attribute['CODE']])) {
							$model = new OptionValue();
						}
						$model->option_id = $option_id;
						$model->option_value_code = $attribute['CODE'];
						$model->sort_order = 0;
						$model->image = '';
						if (!$model->save()) {
							throw new \Exception(json_encode($model->errors));
						}
						$option_value_id = $model->option_value_id;
						if (!$model = OptionValueDescription::findOne(['option_value_id' => $option_value_id, 'option_value_code' => $attribute['CODE']])) {
							$model = new OptionValueDescription();
						}
						$model->option_value_id = $option_value_id;
						$model->option_id = $option_id;
						$model->language_id = 2;
						$model->option_value_code = $attribute['CODE'];
						$model->name = $attribute['NAME'];
						if (!$model->save()) {
							throw new \Exception(json_encode($model->errors));
						}

					}
				}

			}
			$transaction->commit();
		} catch (\Exception $e) {
			$status = false;
			$this->message = $e->getMessage();
			$transaction->rollBack();
		}
		return $status;
	}

	//品牌信息
	public function actionBrand($datas)
	{
		$transaction = \Yii::$app->db->beginTransaction();
		$status = true;
		try {
			foreach ($datas as $data) {
				if (!$model = Manufacturer::findOne(['code' => trim($data['CODE'])])) {
					$model = new Manufacturer();
				}
				$model->code = trim($data['CODE']);
				$model->name = $data['NAME'];
				$model->image = $data['IMAGEURL'];
				$model->story = $data['DESCRIPTION'];
				$model->status = $data['STATUS'] == 'ACTIVE' ? 1 : 0;
				if (!$model->save()) {
					throw new \Exception(json_encode($model->errors));
				}
			}
			$transaction->commit();
		} catch (\Exception $e) {
			$status = false;
			$this->message = $e->getMessage();
			$transaction->rollBack();
		}
		return $status;
	}

	//店铺信息
	public function actionStore($datas)
	{
		$transaction = \Yii::$app->db->beginTransaction();
		$status = true;
		try {
			foreach ($datas as $data) {
				$template = [];
				if (!$model = Store::findOne(['store_code' => $data['CODE']])) {
					$model = new Store();
					$model->date_added = $data['UPDATETIME'];
				}
				$model->store_code = $data['CODE'];
				$model->name = $data['NAME'];
				$model->store_type = $data['SHOPGRADE'];
				$model->image = $data['PICTUREURL'];
				$model->logo = $data['IMGURL'];
				$model->app_logo = $data['APPLOGO'];
				$Platform = Platform::findOne(['platform_code' => $data['MARKETCODE']]);
				$model->platform_id = $Platform ? $Platform->platform_id : 0;
				$model->platform_code = $data['MARKETCODE'];
				$LegalPerson = LegalPerson::findOne(['legal_no' => $data['COMPANYCODE']]);
				$model->legal_person_id = $LegalPerson ? $LegalPerson->legal_person_id : 0;
				$model->legal_person_code = $data['COMPANYCODE'];
				$Theme = Theme::findOne(['theme_code' => $data['TEMPLATECODE']]);
				$model->theme_id = $Theme ? $Theme->theme_id : 0;
				if ($Theme) {
					$template[] = $Theme->theme_id;
				}
				$model->theme_code = $data['TEMPLATECODE'];
				$H5theme = Theme::findOne(['theme_code' => isset($data['TEMPLATEH5CODE']) ? $data['TEMPLATEH5CODE'] : ""]);
				$model->h5_theme_id = $H5theme ? $H5theme->theme_id : 0;
				if ($H5theme) {
					$template[] = $H5theme->theme_id;
				}
				$model->recommend = $data['BEEXHIBIT'] ? 1 : 0;
				$City = City::findOne(['code' => $data['CITYCODE']]);
				$model->city = $City ? $City->city_id : 0;
				$District = District::findOne(['code' => $data['DISTRICTCODE']]);
				$model->district = $District ? $District->district_id : 0;
				$model->address = $data['ADDRESS'];
				$model->longitude = $data['LONGITUDE'];
				$model->latitude = $data['LATITUDE'];
				$IndustryStoreCategory = IndustryStoreCategory::findOne(['industry_store_category_code' => $data['SHOPTYPECODE']]);
				$model->industry_store_category_id = $IndustryStoreCategory ? $IndustryStoreCategory->industry_store_category_id : 0;
				$model->industry_store_category_code = $data['SHOPTYPECODE'];
				$model->minbookcash = $data['MINBOOKCASH'];
				$model->deliverycash = $data['DELIVERYCASH'];
				$model->cycle_period = $data['MAXCYCLEPERIOD'];
				$model->opening_hours = $data['OPENTIME'];
				if ($data['OPENTIME'] && strpos($data['OPENTIME'], "-")) {
					list($max_open_hour, $min_open_hour) = explode("-", str_replace("：", ":", $data['OPENTIME']));
					$model->max_open_hour = $max_open_hour;
					$model->min_open_hour = $min_open_hour;
				}
				$model->delivery_hours = $data['DELIVERYTIME'];
				if ($data['DELIVERYTIME'] && strpos($data['DELIVERYTIME'], "-")) {
					list($max_delivery_hour, $min_delivery_hour) = explode("-", str_replace("：", ":", $data['DELIVERYTIME']));
					$model->max_delivery_hour = $max_delivery_hour;
					$model->min_delivery_hour = $min_delivery_hour;
				}
				$model->is_merge = $data['BEALONE'] ? 0 : 1;
				$model->online = $data['ONLINE'] ? 1 : 0;
				$model->befreepostage = $data['BEFREEPOSTAGE'] ? 1 : 0;
				$model->besupportpos = $data['BESUPPORTPOS'] ? 1 : 0;
				$model->discount = $data['DISCOUNT'];
				$model->hotline = $data['TELEPHONE'];
				$model->max_user_number = $data['MAXUSERNUMBER'];
				$model->business_code = $data['BUSINESSZONECODE'];
				$model->business_name = $data['BUSINESSZONENAME'];
				$model->notice = $data['NOTICE'];
				$model->tags = $data['TAGS'];
				$model->description = $data['DESCRIPTION'];
				$model->is_delivery_station = isset($data['BEEXISTSELF']) && $data['BEEXISTSELF'] ? 1 : 0;
				$model->status = $data['STATUS'] == "ACTIVE" ? 1 : 0;
				$model->date_modified = $data['UPDATETIME'];
				if (!$model->save()) {
					throw new \Exception(json_encode($model->errors));
				}
				$store_id = $model->store_id;
				if (!$model = StoreDescription::findOne(['store_id' => $store_id])) {
					$model = new StoreDescription();
				}
				$model->language_id = 2;
				$model->store_id = $store_id;
				$model->title = $data['NAME'];
				$model->description = $data['DESCRIPTION'];
				$model->meta_description = $data['NOTICE'];
				$model->meta_keyword = $data['NOTICE'];
				if (!$model->save()) {
					throw new \Exception(json_encode($model->errors));
				}
				if (isset($data['DETAILS']) && $data['DETAILS']) {
					foreach ($data['DETAILS'] as $value) {
						$Warehouse = Warehouse::findOne(['warehouse_code' => $value['WAREHOUSECODE']]);
						if (!$model = StoreToWarehouse::findOne(['store_id' => $store_id, 'warehouse_id' => $Warehouse ? $Warehouse->warehouse_id : 0])) {
							$model = new StoreToWarehouse();
						}
						$model->store_id = $store_id;
						$model->store_code = $data['CODE'];
						$model->warehouse_id = $Warehouse ? $Warehouse->warehouse_id : 0;
						$model->warehouse_code = $value['WAREHOUSECODE'];
						if (!$model->save()) {
							throw new \Exception(json_encode($model->errors));
						}
					}
				}
				if ($template) {
					foreach ($template as $value) {
						if (!$model = StoreTheme::findOne(['store_id' => $store_id, 'theme_id' => $value])) {
							$model = new StoreTheme();
						}
						$model->store_id = $store_id;
						$model->theme_id = $value;
						$model->status = 1;
						if (!$model->save()) {
							throw new \Exception(json_encode($model->errors));
						}
					}
				}
			}
			$transaction->commit();
		} catch (\Exception $e) {
			$status = false;
			$this->message = $e->getMessage();
			$transaction->rollBack();
		}
		return $status;
	}

	//店铺属性
	public function actionStoreattribute($datas)
	{
		$transaction = \Yii::$app->db->beginTransaction();
		$status = true;
		try {
			foreach ($datas as $data) {
				$Store = Store::findOne(['store_code' => $data['SHOPCODE']]);
				$store_id = $Store ? $Store->store_id : 0;
				$Attribute = Attribute::findOne(['attribute_code' => $data['ATTRIBUTECODE']]);
				$attribute_id = $Attribute->attribute_id;
				if ($store_id && $attribute_id && $data['CONTENT'] != "") {
					if (!$model = StoreAttribute::findOne(['store_id' => $store_id, 'attribute_id' => $attribute_id])) {
						$model = new StoreAttribute();
					}
					$model->store_id = $store_id;
					$model->store_code = $data['SHOPCODE'];
					$model->attribute_id = $attribute_id;
					$model->attribute_code = $data['ATTRIBUTECODE'];
					$model->text = $data['CONTENT'];
					$model->language_id = 2;
					if (!$model->save()) {
						throw new \Exception(json_encode($model->errors));
					}
					//人均消费 属性类型
					if ($data['ATTRIBUTECODE'] == 'PERPRICE' && $store_id) {
						if ($model = Store::findOne(['store_id' => $store_id])) {
							$model->perprice = $data['CONTENT'];
							if (!$model->save()) {
								throw new \Exception(json_encode($model->errors));
							}
						}
					}
				}
			}
			$transaction->commit();
		} catch (\Exception $e) {
			$status = false;
			$this->message = $e->getMessage();
			$transaction->rollBack();
		}
		return $status;
	}

	//店铺模板及详情数据
	public function actionStorecolumn($datas)
	{
		$transaction = \Yii::$app->db->beginTransaction();
		$status = true;
		try {
			foreach ($datas as $data) {
				if (isset($data['DETAILS']) && $data['DETAILS']) {
					$Theme = Theme::findOne(['theme_code' => isset($data['TEMPLATECODE']) ? $data['TEMPLATECODE'] : '']);
					$Store = Store::findOne(['store_code' => $data['SHOPCODE']]);
					$StoreTheme = StoreTheme::findOne(['store_id' => $Store ? $Store->store_id : 0, 'theme_id' => $Theme ? $Theme->theme_id : 0]);
					$ThemeColumn = ThemeColumn::findOne(['theme_column_code' => $data['CODE'], 'theme_id' => $Theme ? $Theme->theme_id : 0]);
					$theme_column_id = $ThemeColumn ? $ThemeColumn->theme_column_id : 0;
					$store_id = $Store ? $Store->store_id : 0;
					if (!$model = StoreThemeColumn::findOne(['theme_column_id' => $theme_column_id, 'store_theme_id' => $StoreTheme ? $StoreTheme->store_theme_id : 0])) {
						$model = new StoreThemeColumn();
					}
					$model->store_id = $store_id;
					$model->store_theme_id = $StoreTheme ? $StoreTheme->store_theme_id : 0;
					$model->store_code = $data['SHOPCODE'];
					$model->theme_column_id = $theme_column_id;
					$model->theme_column_code = $data['CODE'];
					$model->theme_column_type = $data['TEMPLATETYPE'];
					$model->sort_order = isset($data['SORT_ORDER']) ? intval($data['SORT_ORDER']) : 0;
                    $model->url = $data['COLUMN_URL'] ? $data['COLUMN_URL'] :'';
					$model->name = $data['NAME'];
					$model->status = $data['STATUS'] == "ACTIVE" ? 1 : 0;
					if (!$model->save()) {
						throw new \Exception(json_encode($model->errors));
					}
					$store_theme_column_id = $model->store_theme_column_id;
					if (isset($data['DETAILS']) && $data['DETAILS']) {
						foreach ($data['DETAILS'] as $value) {
							if (!$model = StoreThemeColumnInfo::findOne(['store_theme_column_id' => $store_theme_column_id, 'store_theme_column_info_code' => $value['CODE']])) {
								$model = new StoreThemeColumnInfo();
							}
							$model->store_theme_column_info_code = $value['CODE'];
							$model->store_theme_column_id = $store_theme_column_id;
							$model->theme_column_type = $value['TYPE'];
							$model->title = $value['TITLE'];
							$model->contents = $value['CONTENT'];
							$model->sort = isset($data['SORT']) ? intval($data['SORT']) : 0;
							$model->image = $value['IMAGE'];
							$model->url = $value['URL'];
							$ProductBase = ProductBase::findOne(['product_base_code' => $value['PCODE'], 'store_id' => $store_id]);
							$product_base_id = $ProductBase ? $ProductBase->product_base_id : 0;
							$Product = Product::findOne(['product_code' => $value['PUCODE'], 'product_base_id' => $product_base_id]);
							$model->product_id = $Product ? $Product->product_id : 0;
							$model->product_code = $value['PUCODE'];
							$model->status = $value['STATUS'] == "ACTIVE" ? 1 : 0;
							if (!$model->save()) {
								throw new \Exception(json_encode($model->errors));
							}

						}
					}
				}
			}
			$transaction->commit();
		} catch (\Exception $e) {
			$status = false;
			$this->message = $e->getMessage();
			$transaction->rollBack();
		}
		return $status;
	}

	//店铺配送信息
	public function actionShopdelivery($datas)
	{
		$transaction = \Yii::$app->db->beginTransaction();
		$status = true;
		try {
			foreach ($datas as $data) {
				if (!$model = StoreDelivery::findOne(['store_delivery_code' => $data['CODE']])) {
					$model = new StoreDelivery();
				}
				$model->store_delivery_code = $data['CODE'];
				$Store = Store::findOne(['store_code' => $data['SHOPCODE']]);
				$model->store_id = $Store ? $Store->store_id : 0;
				$model->store_code = $data['SHOPCODE'];
				$model->name = $data['NAME'];
				$model->method = $data['METHOD'];
				$model->method_value = $data['RADIUS'];
				$model->is_default = $data['BEDEFAULT'] ? 1 : 0;
				$model->status = $data['STATUS'] == "ACTIVE" ? 1 : 0;
				if (!$model->save()) {
					throw new \Exception(json_encode($model->errors));
				}
				$store_id = $model->store_id;
				$store_delivery_id = $model->store_delivery_id;
				if ($data['DETAILS'] && $data['METHOD'] == 'DISTRICT') {
					foreach ($data['DETAILS'] as $value) {
						if ($model = StoreDeliveryDetail::findOne(['store_delivery_id' => $store_delivery_id])) {
							$model->delete();
							$model = new StoreDeliveryDetail();
						} else {
							$model = new StoreDeliveryDetail();
						}
						$model->store_delivery_id = $store_delivery_id;
						$model->store_delivery_code = $data['CODE'];
						$District = District::findOne(['code' => $value['DISTRICTCODE']]);
						$model->district_id = $District ? $District->district_id : 0;
						$model->district_code = $value['DISTRICTCODE'];
						$model->district_name = $value['DISTRICTNAME'];
						if (!$model->save()) {
							throw new \Exception(json_encode($model->errors));
						}
					}
				}
				//配送范围 半径类型
				if (trim($data['METHOD']) == 'RADIUS' && $store_id) {
					if ($model = Store::findOne(['store_id' => $store_id])) {
						$model->radius = $data['RADIUS'];
						if (!$model->save()) {
							throw new \Exception(json_encode($model->errors));
						}
					}

				}
			}
			$transaction->commit();
		} catch (\Exception $e) {
			$status = false;
			$this->message = $e->getMessage();
			$transaction->rollBack();
		}
		return $status;
	}

	//店铺产品分类信息
	public function actionStore_productcategory($datas)
	{
		$transaction = \Yii::$app->db->beginTransaction();
		$status = true;
		try {
			foreach ($datas as $data) {
				$Store = Store::findOne(['store_code' => $data['SHOPCODE']]);
				$store_id = $Store ? $Store->store_id : 0;
				if (!$model = CategoryStore::findOne(['store_id' => $store_id, 'category_store_code' => $data['CODE']])) {
					$model = new CategoryStore();
					$model->date_added = $data['UPDATETIME'];
				}
				$model->store_id = $store_id;
				$model->store_code = $data['SHOPCODE'];
				$model->category_store_code = $data['CODE'];
				$parent = CategoryStore::findOne(['category_store_code' => $data['PARENTCODE']]);
				$model->parent_id = $parent ? $parent->category_store_id : 0;
				$model->parent_code = $data['PARENTCODE'];
				$model->name = $data['NAME'];
				$model->status = $data['STATUS'] == "ACTIVE" ? 1 : 0;
				$model->date_modified = $data['UPDATETIME'];
				if (!$model->save()) {
					throw new \Exception(json_encode($model->errors));
				}
			}
			$transaction->commit();
		} catch (\Exception $e) {
			$status = false;
			$this->message = $e->getMessage();
			$transaction->rollBack();
		}
		return $status;
	}

	//商品基础信息
	public function actionProductbase($datas)
	{
		$transaction = \Yii::$app->db->beginTransaction();
		$status = true;
		try {
			foreach ($datas as $data) {
				if (!$model = ProductBase::findOne(['product_base_code' => $data['CODE'], 'store_code' => $data['SHOPCODE']])) {
					$model = new ProductBase();
					$model->date_added = $data['UPDATETIME'];
				}
				$model->product_base_code = $data['CODE'];
				$Manufacturer = Manufacturer::findOne(['code' => $data['BRANDCODE']]);
				$model->manufacturer_id = $Manufacturer ? $Manufacturer->manufacturer_id : 0;
				$model->manufacturer_code = $data['BRANDCODE'];
				$model->ename = $data['ENNAME'];
				$model->image = $data['IMAGEURL'];
				$model->deliverycode = $data['DELIVERYCODE'];
				if(substr($data['LIFE'],0,1) == 0){
                    $model->life = '不限';
                }else{
                    $model->life = $data['LIFE'];
                }
				$model->begift = $data['BEGIFT'] == 'true' ? 1 : 0;
				$model->becycle = $data['BECYCLE'] == 'true' ? 1 : 0;
				$model->bemanage = $data['BEMANAGE'] == 'true' ? 1 : 0;
				$model->product_model = $data['PRODUCTMODEL'] ? $data['PRODUCTMODEL'] : 'NORMAL';
				$model->back_rebate = $data['REBATE'];
				$model->expire_time = $data['EXPIRES'] ? $data['EXPIRES'] : 0;
				$model->is_merge = $data['BEALONE'] ? 0 : 1;
				$model->beintoinv = $data['BEINTOINV'] == 'true' ? 1 : 0;
				$model->bepresell = $data['BEPRESELL'] == 'true' ? 1 : 0;
				$Store = Store::findOne(['store_code' => $data['SHOPCODE']]);
				$model->store_id = $Store ? $Store->store_id : 0;
				$model->store_code = $data['SHOPCODE'];
				$model->shipping = $data['BETRANSPORT'] ? 1 : 0;
				$model->verifycodetype = $data['VERIFYCODETYPE'] ? $data['VERIFYCODETYPE'] : 'NONE';
				$model->bevirtual = $data['BEVIRTUAL'] ? 1 : 0;
				$Category = Category::findOne(['code' => $data['TYPECODE']]);
				$model->category_id = $Category ? $Category->category_id : 0;
				$model->bedisplaylife = (isset($data['BEDISPLAYLIFE']) && $data['BEDISPLAYLIFE']) ? 1 : 0;
				$model->date_modified = date('Y-m-d H:i:s', time());
                $model->can_not_return = (isset($data['CANNOTRETURN']) && $data['CANNOTRETURN']) ? 1 : 0; //1不支持7天无理由退货 0支持7天无理由退货
				if (!$model->save()) {
					throw new \Exception(json_encode($model->errors));
				}
				$product_base_id = $model->product_base_id;
				$product_base_code = $model->product_base_code;
				if (!$decriptionModel = ProductBaseDescription::findOne(['product_base_id' => $product_base_id])) {
					$decriptionModel = new ProductBaseDescription();
				}
				$decriptionModel->product_base_id = $product_base_id;
				$decriptionModel->name = $data['NAME'];
				$decriptionModel->description = $data['DESCRIPTION'];
				$decriptionModel->meta_description = $data['TITLE'];
				$decriptionModel->meta_keyword = $data['TITLE'];
				$decriptionModel->language_id = 2;
				$decriptionModel->product_base_code = $data['CODE'];
				if (!$decriptionModel->save()) {
					throw new \Exception(json_encode($decriptionModel->errors));
				}
				if (isset($data['SHOPPRODUCTTYPES']) && $data['SHOPPRODUCTTYPES']) {
					foreach ($data['SHOPPRODUCTTYPES'] as $sc) {
						if ($shopCategoryModel = CategoryStore::findOne(['category_store_code' => $sc['CODE']])) {
							if (!$catgory_to_product_model = CategoryStoreToProduct::findOne(['product_base_id' => $product_base_id, 'category_store_id' => $shopCategoryModel->category_store_id])) {
								$catgory_to_product_model = new CategoryStoreToProduct();
							}
							$catgory_to_product_model->category_store_id = $shopCategoryModel->category_store_id;
							$catgory_to_product_model->category_store_code = $shopCategoryModel->category_store_code;
							$catgory_to_product_model->product_base_id = $product_base_id;
							$catgory_to_product_model->product_base_code = $product_base_code;
							if (!$catgory_to_product_model->save()) {
								throw new \Exception(json_encode($catgory_to_product_model->errors));
							}
						}
					}
				}
			}
			$transaction->commit();
		} catch (\Exception $e) {
			$status = false;
			$this->message = $e->getMessage();
			$transaction->rollBack();
		}
		return $status;
	}

	//商品包装信息
	public function actionProduct($datas)
	{
		$transaction = \Yii::$app->db->beginTransaction();
		$status = true;
		$spec_array = [];
		try {
			foreach ($datas as $data) {
				if (!$model = Product::findOne(['product_code' => $data['PUCODE'], 'store_code' => $data['SHOPCODE']])) {
					$model = new Product();
					$model->date_added = $data['UPDATETIME'];
				}
				$product_base = ProductBase::findOne(['store_code' => $data['SHOPCODE'], 'product_base_code' => $data['PRODUCTCODE']]);
				$model->product_code = $data['PUCODE'];
				$model->product_base_id = $product_base->product_base_id;
				$model->product_base_code = $data['PRODUCTCODE'];
				$model->store_id = Store::findOne(['store_code' => $data['SHOPCODE']])->store_id;
				$model->store_code = $data['SHOPCODE'];
				$model->upc = $data['UPC'];
				$model->unit = $data['UNIT'];
				$model->vip_price = $data['VIPSALE'];
				$model->price = $data['SALE'];
				$model->convertfigure = $data['CONVERTFIGURE'];
				$model->volume = $data['VOLUME'];
				$model->weight = $data['WEIGHT'];
				$model->format = $data['FORMAT'];
				$model->bemanage = $data['BEMANAGE'] ? 1 : 0;
				$model->beintoinv = $data['BEINTOINV'] ? 1 : 0;
				$model->bepresell = $data['BEPRESELL'] ? 1 : 0;
				$model->besale = $data['BESALE'] ? 1 : 0;
				$model->begift = $product_base->begift;
				$model->minimum = $data['MAXSALEQUANTITY'];
				$model->min_sale_qty = $data['MINSALEQUANTITY'];
				$model->max_sale_qty = $data['MAXSALEQUANTITY'];
				$model->sort_order = $data['PRIORITY'] ? $data['PRIORITY'] : 0;
				$image = [];
				if ($data['IMAGES']) {
					foreach ($data['IMAGES'] as $val) {
						$image[] = $val['FILEPATH'];
					}
					$model->image = $image ? current($image) : '';
				}
				$model->date_modified = $data['UPDATETIME'];
				if (isset($data['OPTIONS']) && $data['OPTIONS']) {
					$sku_value = [];
					$sku_name = [];
					foreach ($data['OPTIONS'] as $option) {
						$OptionModel = Option::findOne(['code' => $option['GROUPCODE']]);
						$key = $OptionModel ? $OptionModel->option_id : 0;
						$OptionValue = OptionValue::findOne(['option_value_code' => $option['SPECCODE'], 'option_id' => $key]);
						$v = $OptionValue ? $OptionValue->option_value_id : 0;
						if ($v) {
							// $product_base_sku[$model->product_base_id][$key][]=$v;
							$sku_value[$key] = $key . ":" . $v;
							$sku_name[] = $option['GROUPTITLE'] . ":" . $option['SPECNAME'];
						}
					}
					ksort($sku_value);
					$model->sku = implode(";", $sku_value);
					$model->sku_name = implode(";", $sku_name);
					if ($data['BEINTOINV'] == 'true') {
						$spec_array[$model->product_base_id][] = $model->sku;
					}
				}
				if (!$model->save()) {
					throw new \Exception(json_encode($model->errors));
				}
				ProductImage::deleteAll(['product_id' => $model->product_id]);
				if ($image) {
					foreach ($image as $key => $value) {
						if (!$imagemodel = ProductImage::findOne(['product_id' => $model->product_id, 'image' => $value])) {
							$imagemodel = new ProductImage();
						}
						$imagemodel->product_base_id = $model->product_base_id;
						$imagemodel->product_id = $model->product_id;
						$imagemodel->product_code = $model->product_code;
						$imagemodel->image = $value;
						$imagemodel->sort_order = $key;
						if (!$imagemodel->save()) {
							throw new \Exception(json_encode($imagemodel->errors));
						}
					}
				}
			}
			$transaction->commit();
		} catch (\Exception $e) {
			$status = false;
			$this->message = $e->getMessage();
			$transaction->rollBack();
		}
		if ($spec_array) {
			foreach ($spec_array as $key => $value) {
				if ($model = ProductBase::findOne(['product_base_id' => $key])) {
					$sku = implode(",", $value);
					$model->spec_array = $sku;
					$model->save();
				}
			}
		}
		return $status;

	}

	//产品属性信息
	public function actionProductattribute($datas)
	{
		$transaction = \Yii::$app->db->beginTransaction();
		$status = true;
		try {
			foreach ($datas as $data) {
				if ($data['CONTENT']) {
					$Store = Store::findOne(['store_code' => trim($data['SHOPCODE'])]);
					$ProductBase = ProductBase::findOne(['product_base_code' => trim($data['PRODUCTCODE']), 'store_id' => $Store->store_id]);
					$AttributeGroup = AttributeGroup::findOne(['attribute_group_code' => trim($data['ATTRIBUTEGROUPCODE'])]);
					$Attribute = Attribute::findOne(['attribute_code' => trim($data['ATTRIBUTECODE']), 'attribute_group_id' => $AttributeGroup->attribute_group_id]);
					if (!$model = ProductBaseAttribute::findOne(['product_base_id' => $ProductBase->product_base_id, 'attribute_id' => $Attribute->attribute_id])) {
						$model = new ProductBaseAttribute();
					}
					$model->language_id = 2;
					$model->attribute_code = trim($data['ATTRIBUTECODE']);
					$model->attribute_id = $Attribute->attribute_id;
					$model->product_base_id = $ProductBase ? $ProductBase->product_base_id : 0;
					$model->product_base_code = $data['PRODUCTCODE'];
					$model->text = $data['CONTENT'];
					if (!$model->save()) {
						throw new \Exception(json_encode($model->errors));
					}
				}
			}
			$transaction->commit();
		} catch (\Exception $e) {
			$status = false;
			$this->message = $e->getMessage();
			$transaction->rollBack();
		}
		return $status;
	}

	//优惠券分类
	public function actionCoupondisplaytype($datas)
	{
		$transaction = \Yii::$app->db->beginTransaction();
		$status = true;
		try {
			foreach ($datas as $data) {
				if (!$model = CouponCate::findOne(['code' => trim($data['CODE'])])) {
					$model = new CouponCate();
					$model->creat_at = time();
				}
				if ($parent = CouponCate::findOne(['code' => $data['PARENTCODE']])) {
					$model->pid = $parent->id;
				} else {
					$model->pid = 0;
				}
				$model->code = trim($data['CODE']);
				$model->name = $data['NAME'];
				$model->description = $data['DESCRIPTION'];
				$model->sort_order = $data['SORT'];
				$model->status = $data['STATUS'] == 'ACTIVE' ? 1 : 0;
				$model->update_at = time();
				if (!$model->save()) {
					throw new \Exception(serialize($model->errors));
				}
			}
			$transaction->commit();
		} catch (\Exception $e) {
			$status = false;
			$this->message = $e->getMessage();
			$transaction->rollBack();
		}
		return $status;
	}

	//优惠券信息
	public function actionCoupon($datas)
	{
		$transaction = \Yii::$app->db->beginTransaction();
		$status = true;
		try {
			foreach ($datas as $data) {
				if (!$model = Coupon::findOne(['code' => trim($data['CODE'])])) {
					$model = new Coupon();
				}
				$model->code = trim($data['CODE']);
				$model->name = $data['NAME'];
				$model->comment = $data['DESCRIPTION'];
				$model->image_url = $data['SOURCE_URL'];
				$model->model = $data['COUPON_TYPE'];
				$model->type = $data['REBATE_MODE'];
				$model->shipping = $data['BE_FREE_POST'] ? 1 : 0;
				$model->discount = $data['AMOUNT'];
				$model->total = $data['MIN_REBATE_AMOUNT'];
				$model->limit_min_quantity = $data['SATISFY_QUANTITY'];
				$model->limit_max_quantity = isset($data['MAX_SATISFY_QUANTITY']) ? $data['MAX_SATISFY_QUANTITY'] : '';
				$model->max_discount = $data['MAX_REBATE_AMOUNT'];
				$model->min_discount = $data['MIN_REBATE_AMOUNT'];
				$model->total = $data['MIN_PAY_MENT'];
				$model->limit_max_total = isset($data['MAX_PAY_MENT']) ? $data['MAX_PAY_MENT'] : '';
				$model->date_type = isset($data['COMPUTE_TYPE']) ? $data['COMPUTE_TYPE'] : 'TIME_SLOT';
				$model->date_start = $data['BEGIN_TIME'];
				$model->date_end = $data['END_TIME'];
				if ($model->date_type == 'DAYS') {
					$expire_seconds = isset($data['DAYS']) ? $data['DAYS'] * 60 * 60 * 24 : 0;
				} else {
					$expire_seconds = 0;
				}
				$model->expire_seconds = $expire_seconds;
				$model->quantity = $data['QUANTITY'];
				$model->user_limit = $data['LIMIT_COUNT'];
				$model->is_entity = $data['BE_ENTITY'] ? 1 : 0;
				$model->is_open = $data['BE_OPEN'] ? 1 : 0;
				$model->is_prize = isset($data['BE_PRIZE']) && $data['BE_PRIZE'] ? 1 : 0;
				$model->status = $data['STATUS'] == 'EXECUTING' ? 1 : 0;
				$Store = Store::findOne(['store_code' => $data['SHOP']]);
				$model->store_id = $Store ? $Store->store_id : 0;
				$Platform = Platform::findOne(['platform_code' => $data['MARKET']]);
				$model->platform_id = $Platform ? $Platform->platform_id : 0;
				$model->date_added = date('Y-m-d H:i:s', time());
				$model->receive_begin_date = isset($data['RECEIVE_BEGIN_DATE']) ? $data['RECEIVE_BEGIN_DATE'] : '';
				$model->receive_end_date = isset($data['RECEIVE_END_DATE']) ? $data['RECEIVE_END_DATE'] : '';
                $model->redirect_url = isset($data['LINK_URL']) ? $data['LINK_URL'] : '';

				if (!$model->save()) {
					throw new \Exception(serialize($model->errors));
				}
				$coupon_id = $model->coupon_id;
				$store_id = $model->store_id;
				if (isset($data['DISPLAYTYPE']) && $data['DISPLAYTYPE']) {
					CouponCateToCoupon::deleteAll(['coupon_id' => $coupon_id]);
					//CDT01:每日精选;
					$cateArr = explode(";", $data['DISPLAYTYPE']);
					if ($cateArr) {
						foreach ($cateArr as $arr) {
							list($code, $name) = explode(":", $arr);
							if ($cateModel = CouponCate::findOne(['code' => trim($code)])) {
								$coupon_cate = new CouponCateToCoupon();
								$coupon_cate->coupon_id = $coupon_id;
								$coupon_cate->cate_id = $cateModel->id;
								if (!$coupon_cate->save()) {
									throw new \Exception(serialize($coupon_cate->errors));
								}
							}
						}
					}
				}
				if (isset($data['COUPON_DETAILS']) && $data['COUPON_DETAILS']) {
					foreach ($data['COUPON_DETAILS'] as $value) {
						$ProductBase = ProductBase::findOne(['product_base_code' => $value['PRODUCT_CODE'], 'store_id' => $store_id]);
						$product_base_id = $ProductBase ? $ProductBase->product_base_id : 0;
						$Product = Product::findOne(['product_base_id' => $product_base_id, 'product_code' => $value['PUCODE']]);
						$product_id = $Product ? $Product->product_id : 0;
						if (!$coupon_product = CouponProduct::findOne(['coupon_id' => $coupon_id, 'product_id' => $product_id])) {
							$coupon_product = new CouponProduct();
						}
						$coupon_product->coupon_id = $coupon_id;
						$coupon_product->product_id = $product_id;
						$coupon_product->status = $value['DETAIL_STATUS'] == 'EXECUTING' ? 1 : 0;
						if (!$coupon_product->save()) {
							throw new \Exception(serialize($coupon_product->errors));
						}
					}
				}
				if (isset($data['ACTIVATE_DETAILS']) && $data['ACTIVATE_DETAILS']) {
					foreach ($data['ACTIVATE_DETAILS'] as $value) {
						if (!$coupon_cart = CouponCard::findOne(['coupon_id' => $coupon_id, 'card_code' => $value['ACTIVATE_CODE']])) {
							$coupon_cart = new CouponCard();
						}
						$coupon_cart->coupon_id = $coupon_id;
						$coupon_cart->card_code = $value['ACTIVATE_CODE'];
						$coupon_cart->card_pwd = $value['ACTIVATE_PASSWORD'];
						$coupon_cart->status = $value['ACTIVATE_STATUS'] == 'ACTIVE' ? 1 : 0;
						if (!$coupon_cart->save()) {
							throw new \Exception(serialize($coupon_cart->errors));
						}
					}
				}
				if (isset($data['COUPON_GIFT_DETAILS']) && $data['COUPON_GIFT_DETAILS']) {
					foreach ($data['COUPON_GIFT_DETAILS'] as $value) {
						if (!$product = Product::findOne(['product_code' => $value['PUCODE']])) {
							continue;
						}
						if (!$coupon_gift = CouponGift::findOne(['coupon_id' => $coupon_id, 'product_id' => $product->product_id])) {
							$coupon_gift = new CouponGift();
						}
						$coupon_gift->coupon_id = $coupon_id;
						$coupon_gift->product_id = $product->product_id;
						$coupon_gift->qty = $value['QUANTITY'];
						$coupon_gift->status = $value['GIFT_STATUS'] == 'EXECUTING' ? 1 : 0;
						if (!$coupon_gift->save()) {
							throw new \Exception(serialize($coupon_gift->errors));
						}
					}
				}
                if (isset($data['EXCEPT_TYPE_DETAILS']) && $data['EXCEPT_TYPE_DETAILS']) {
                    $except_categorys = [];
                    foreach ($data['EXCEPT_TYPE_DETAILS'] as $value){
                        $category = Category::findOne(['code'=>$value['EXCEPT_TYPE_CODE']]);
                        if($category){
                            $except_categorys[] = $category->category_id;
                        }
                    }
                    $except_categorys_string = implode(',',$except_categorys);
                    $model->except_category = $except_categorys_string;
                    $model->save();
                }

			}
			$transaction->commit();
		} catch (\Exception $e) {
			$status = false;
			$this->message = $e->getMessage();
			$transaction->rollBack();
		}
		return $status;
	}

	public function actionPromotionsubject($datas)
	{
		$transaction = \Yii::$app->db->beginTransaction();
		$status = true;
		try {
			foreach ($datas as $data) {
				if (!$subject = Subject::findOne(['code' => trim($data['CODE'])])) {
					$subject = new Subject();
					$subject->code = trim($data['SPECIAL']);
				}
				$subject->name = $data['NAME'];
				$subject->image = $data['IMAGEURL'];
				$subject->description = $data['SUBJECT_DESCRIPTION'];
				$subject->status = $data['STATUS'] == 'ACTIVE' ? 1 : 0;
				if (!$subject->save()) {
					throw new \Exception(json_encode($subject->errors));
				}
			}
			$transaction->commit();
		} catch (\Exception $e) {
			$status = false;
			$this->message = $e->getMessage();
			$transaction->rollBack();
		}
		return $status;
	}

	//促销方案信息
	public function actionPromotion($datas)
	{
		$transaction = \Yii::$app->db->beginTransaction();
		$status = true;
		try {
			foreach ($datas as $data) {
				if (!$model = Promotion::findOne(['code' => $data['CODE']])) {
					$model = new Promotion();
					$model->date_added = $data['UPDATETIME'];
				}
				$model->code = $data['CODE'];
				$model->name = $data['NAME'];
				$model->type = $data['TYPE'];
				$model->priority = $data['PRIORITY'];
				$model->status = $data['STATUS'] == "EXECUTING" ? 1 : 0;
				$model->date_start = $data['BEGINDATE'];
				$model->date_end = $data['ENDDATE'];
				$model->description = $data['DESCRIPTION'];
				$model->source = $data['SOURCE'];
				$model->image_url = isset($data['SOURCEURL']) ? $data['SOURCEURL'] : '';
				$Platform = Platform::findOne(['platform_code' => $data['MARKETCODE']]);
				$model->platform_id = $Platform ? $Platform->platform_id : 0;
				$model->platform_code = $data['MARKETCODE'];
				$model->subject = $data['SPECIAL'];
				if ($data['SPECIAL']) {
					$subject = Subject::findOne(['code' => trim($data['SPECIAL'])]);
					$model->subject_id = $subject ? $subject->id : 0;
				}
				$model->date_modified = $data['UPDATETIME'];
				if (!$model->save()) {
					throw new \Exception(json_encode($model->errors));
				}
				$promotion_id = $model->promotion_id;
				foreach ($data['DETAILS'] as $value) {
					$Store = Store::findOne(['store_code' => isset($value['SHOPCODE']) ? $value['SHOPCODE'] : 0]);
					$store_id = $Store ? $Store->store_id : 0;
					$store_code = isset($data['SHOPCODE']) ? $data['SHOPCODE'] : "";
					if (in_array($data['TYPE'], ['SINGLE', 'FREEDELIVERY', 'BRAND', 'DISPLAY'])) {
						if (!$model = PromotionDetail::findOne(['promotion_id' => $promotion_id, 'promotion_detail_code' => $value['PROMOTIONDETAILCODE']])) {
							$model = new PromotionDetail();
						}
						$model->promotion_detail_code = strval($value['PROMOTIONDETAILCODE']);
						$model->promotion_detail_title = $value['TITLE'];
						$model->promotion_detail_image = $value['IMGURL'];
						$ProductBase = ProductBase::findOne(['product_base_code' => $value['PRODUCTCODE'], 'store_id' => $store_id]);
						$product_base_id = $ProductBase ? $ProductBase->product_base_id : 0;
						$Product = Product::findOne(['product_base_id' => $product_base_id, 'product_code' => $value['PUCODE']]);
						$model->product_id = $Product ? $Product->product_id : 0;
						$model->product_code = $value['PUCODE'];
					} else {
						if (!$model = PromotionOrder::findOne(['promotion_id' => $promotion_id, 'promotion_order_code' => $value['PROMOTIONDETAILCODE']])) {
							$model = new PromotionOrder();
						}
						$model->promotion_order_code = strval($value['PROMOTIONDETAILCODE']);
						$model->promotion_order_title = $value['TITLE'];
						$model->promotion_order_image = $value['IMGURL'];
					}
					$model->promotion_id = $promotion_id;
					$model->promotion_code = $data['CODE'];
					$model->customer_group_id = 1;
					$model->member_type_code = $value['MEMBERTYPE'];
					$model->store_id = $store_id;
					$model->store_code = $store_code;
					$model->need_hour = $value['NEEDHOUR'] ? 1 : 0;
					$model->begin_date = $value['BEGINDATE'];
					$model->end_date = $value['ENDDATE'];
					$model->date_start = $value['BEGINTIME'];
					$model->date_end = $value['ENDTIME'];
					$model->stairtype = $value['STAIRTYPE'];
					$model->begin_quantity = $value['BEGINQTY'];
					$model->end_quantity = $value['ENDQTY'];
					$model->begin_amount = $value['BEGINAMOUNT'];
					$model->end_amount = $value['ENDAMOUNT'];
					$model->priority = $value['PRIORITY'];
					$model->pricetype = $value['PRICETYPE'];
					$model->price = $value['PRICE'];
					$model->rebate = $value['REBATE'];
					$model->uplimit_type = $value['UPLIMITTYPE'];
					$model->uplimit_quantity = $value['UPLIMITQTY'];
					$model->limit_quantity = $value['UPLIMITQTY'];
					$model->stop_buy_type = $value['STOPBUYTYPE'];
					$model->stop_buy_quantity = $value['STOPBUYQTY'];
					$model->behave_gift = $value['BEHAVEGIFT'] ? 1 : 0;
					$model->status = $value['STATUS'] == 'EXECUTING' ? 1 : 0;
					if (!$model->save()) {
						throw new \Exception(json_encode($model->errors));
					}
				}
			}
			$transaction->commit();
		} catch (\Exception $e) {
			$status = false;
			$this->message = $e->getMessage();
			$transaction->rollBack();
		}
		return $status;
	}

	public function actionPromotiongift($datas)
	{
		$transaction = \Yii::$app->db->beginTransaction();
		$status = true;
		try {
			foreach ($datas as $data) {
				$Store = Store::findOne(['store_code' => trim($data['SHOPCODE'])]);
				$store_id = $Store ? $Store->store_id : 0;
				if (!$model = PromotionDetailGift::findOne(['store_id' => $store_id, 'promotion_detail_gift_code' => strval($data['GIFTDETAILCODE'])])) {
					$model = new PromotionDetailGift();
				}
				$model->promotion_detail_gift_code = strval($data['GIFTDETAILCODE']);
				if (trim($data['PROMOTIONTYPE']) == 'SINGLE') {
					$PromotionDetail = PromotionDetail::findOne(['promotion_detail_code' => $data['PROMOTIONDETAILCODE']]);
					$promotion_detail_id = $PromotionDetail ? $PromotionDetail->promotion_detail_id : 0;
				} else {
					$PromotionOrder = PromotionOrder::findOne(['promotion_order_code' => $data['PROMOTIONDETAILCODE']]);
					$promotion_detail_id = $PromotionOrder ? $PromotionOrder->promotion_order_id : 0;
				}
				$model->promotion_detail_id = $promotion_detail_id;
				$model->promotion_detail_code = strval($data['PROMOTIONDETAILCODE']);
				$model->promotion_type = trim($data['PROMOTIONTYPE']);
				$model->store_id = $store_id;
				$model->store_code = trim($data['SHOPCODE']);
				$Product = Product::findOne(['product_code' => $data['PUCODE'], 'store_id' => $store_id]);
				$model->product_id = $Product ? $Product->product_id : 0;
				$model->product_code = $data['PUCODE'];
				$model->type = $data['TYPE'];
				$model->base_number = $data['BASEQTY'];
				$model->quantity = $data['QUANTITY'];
				$model->price = $data['PRICE'];
				$model->old_price = $data['OLDPRICE'];
				$model->uplimit_quantity = $data['UPLIMITQTY'];
				$model->gift_type = isset($data['GIFTTYPE']) ? $data['GIFTTYPE'] : '';
				$model->coupon_code = isset($data['COUPONCODE']) ? $data['COUPONCODE'] : '';
				if ($coupon = Coupon::findOne(['code' => $model->coupon_code])) {
					$model->coupon_id = $coupon->coupon_id;
				}
				$model->status = 1;
				if (!$model->save()) {
					throw new \Exception(json_encode($model->errors));
				}
			}
			$transaction->commit();
		} catch (\Exception $e) {
			$status = false;
			$this->message = $e->getMessage();
			$transaction->rollBack();
		}
		return $status;

	}

	//促销商品组及明细信息
	public function actionProductgroup($datas)
	{
		$transaction = \Yii::$app->db->beginTransaction();
		$status = true;
		try {
			foreach ($datas as $data) {
				if (!$model = PromotionProductGroup::findOne(['code' => $data['PRODUCTGROUPCODE']])) {
					$model = new PromotionProductGroup();
				}
				$model->code = $data['PRODUCTGROUPCODE'];
				$model->name = $data['PRODUCTGROUPNAME'];
				if (!$model->save()) {
					throw new \Exception(json_encode($model->errors));
				}
				$promotion_product_group_id = $model->promotion_product_group_id;
				if ($model = PromotionProductGroupDetail::findOne(['promotion_product_group_id' => $promotion_product_group_id])) {
					$model->delete();
				}
				if ($data['GROUPDETAIL']) {
					foreach ($data['GROUPDETAIL'] as $value) {
						$model = new PromotionProductGroupDetail();
						$model->promotion_product_group_id = $promotion_product_group_id;
						$Product = Product::findOne(['product_code' => $value['PUCODE'], 'store_code' => $value['SHOPCODE']]);
						$model->product_id = $Product ? $Product->product_id : 0;
						$model->product_code = $value['PUCODE'];
						$model->base_quantity = $value['BASEQTY'];
						$model->price = $value['PRICE'];
						$model->uplimit_type = $value['UPLIMITTYPE'];
						$model->uplimit_quantity = $value['UPLIMITQTY'];
						$Store = Store::findOne(['store_code' => $value['SHOPCODE']]);
						$model->store_id = $Store ? $Store->store_id : 0;
						$model->store_code = $value['SHOPCODE'];
						if (!$model->save()) {
							throw new \Exception(json_encode($model->errors));
						}
					}
				}
			}
			$transaction->commit();
		} catch (\Exception $e) {
			$status = false;
			$this->message = $e->getMessage();
			$transaction->rollBack();
		}
		return $status;
	}

	//广告媒体类型定义接口
	public function actionAdvertisemedia($datas)
	{
		$transaction = \Yii::$app->db->beginTransaction();
		$status = true;
		try {
			foreach ($datas as $data) {
				if (!$model = AdvertiseMedia::findOne(['code' => $data['CODE']])) {
					$model = new AdvertiseMedia();
					$model->date_added = date('Y-m-d H:i:s', time());
				}
				$model->code = $data['CODE'];
				$model->name = $data['NAME'];
				$model->type = $data['TYPE'];
				$model->description = $data['DESCRIPTION'];
				$model->status = $data['STATUS'] == "ACTIVE" ? 1 : 0;
				$model->date_modified = date('Y-m-d H:i:s', time());
				if (!$model->save()) {
					throw new \Exception(json_encode($model->errors));
				}
			}
			$transaction->commit();
		} catch (\Exception $e) {
			$status = false;
			$this->message = $e->getMessage();
			$transaction->rollBack();
		}
		return $status;
	}

	//广告位定义接口
	public function actionAdvertiseposition($datas)
	{
		$transaction = \Yii::$app->db->beginTransaction();
		$status = true;
		try {
			foreach ($datas as $data) {
				if (!$model = AdvertisePosition::findOne(['code' => $data['CODE']])) {
					$model = new AdvertisePosition();
					$model->date_added = date('Y-m-d H:i:s', time());
				}
				$model->code = $data['CODE'];
				$model->name = $data['NAME'];
				$model->width = $data['WIDTH'];
				$model->height = $data['HEIGHT'];
				$model->size = $data['SIZE'];
				$AdvertisePosition = AdvertisePosition::findOne(['code' => $data['PARENTCODE']]);
				$model->parent_id = $AdvertisePosition ? $AdvertisePosition->advertise_position_id : 0;
				$model->parent_code = $data['PARENTCODE'];
				$model->uplimit_quantity = $data['LIMITQTY'];
				$model->group_type = $data['GROUPTYPE'];
				$model->priority = $data['SORT'];
				$model->status = $data['STATUS'] == "ACTIVE" ? 1 : 0;
				$model->date_modified = date('Y-m-d H:i:s', time());
				if (!$model->save()) {
					throw new \Exception(json_encode($model->errors));
				}
			}
			$transaction->commit();
		} catch (\Exception $e) {
			$status = false;
			$this->message = $e->getMessage();
			$transaction->rollBack();
		}
		return $status;
	}

	//广告明细信息
	public function actionAdvertise($datas)
	{
		$transaction = \Yii::$app->db->beginTransaction();
		$status = true;
		try {
			foreach ($datas as $data) {
				if (!$model = Advertise::findOne(['code' => $data['CODE']])) {
					$model = new Advertise();
					$model->date_added = date('Y-m-d H:i:s', time());
				}
				$model->code = $data['CODE'];
				$model->name = $data['NAME'];
				$model->date_start = $data['STARTTIME'];
				$model->date_end = $data['ENDTIME'];
				$AdvertisePosition = AdvertisePosition::findOne(['code' => $data['POSITIONCODE']]);
				$model->advertise_position_id = $AdvertisePosition ? $AdvertisePosition->advertise_position_id : 0;
				$model->advertise_position_code = $data['POSITIONCODE'];
				$LegalPerson = LegalPerson::findOne(['legal_no' => $data['COMPANYCODE']]);
				$model->legal_person_id = $LegalPerson ? $LegalPerson->legal_person_id : 0;
				$model->legal_person_code = $data['COMPANYCODE'];
				$Platform = Platform::findOne(['platform_code' => $data['PLATFORMCODE']]);
				$model->platform_id = $Platform ? $Platform->platform_id : 0;
				$model->platform_code = $data['PLATFORMCODE'];
				$model->priority = $data['PRIORITY'];
				$model->status = $data['STATUS'] == "EXECUTING" ? 1 : 0;
				$model->date_modified = date('Y-m-d H:i:s', time());
				if (!$model->save()) {
					throw new \Exception(json_encode($model->errors));
				}
				if (isset($data['DETAILS']) && $data['DETAILS']) {
					foreach ($data['DETAILS'] as $value) {
						if (!$detail = AdvertiseDetail::findOne(['advertise_detail_code' => $value['CODE'], 'advertise_id' => $model->advertise_id])) {
							$detail = new AdvertiseDetail();
							$detail->date_added = date('Y-m-d H:i:s', time());
						}
						$detail->advertise_detail_code = $value['CODE'];
						$detail->advertise_id = $model->advertise_id;
						$detail->advertise_code = $model->code;
						$advertise_media = AdvertiseMedia::findOne(['code' => $value['MEDIACODE']]);
						$detail->advertise_media_id = $advertise_media ? $advertise_media->advertise_media_id : 0;
						$detail->advertise_media_code = $value['MEDIACODE'];
						$detail->advertise_media_type = $value['MEDIATYPE'];
						$detail->advertise_position_id = $AdvertisePosition ? $AdvertisePosition->advertise_position_id : 0;
						$detail->advertise_position_code = $AdvertisePosition ? $AdvertisePosition->code : '';
						$detail->width = $AdvertisePosition ? $AdvertisePosition->width : '';
						$detail->height = $AdvertisePosition ? $AdvertisePosition->height : '';
						$detail->position_priority = $AdvertisePosition ? $AdvertisePosition->priority : 0;
						$detail->category_display_id = $AdvertisePosition ? $AdvertisePosition->category_display_id : '';
						$detail->group_type = $AdvertisePosition ? $AdvertisePosition->group_type : '';
						$detail->content = $value['CONTENT'];
						$detail->title = $value['TITLE'];
						$detail->date_start = $value['STARTTIME'];
						$detail->date_end = $value['ENDTIME'];
						$detail->link_url = $value['LINKURL'];
						$detail->source_url = $value['SOURCEURL'];
						$detail->priority = $value['PRIORITY'];
						$detail->main_priority = $data['PRIORITY'];
						$detail->status = $value['STATUS'] == 'EXECUTING' ? 1 : 0;
						$detail->legal_person_id = $LegalPerson ? $LegalPerson->legal_person_id : 0;
						$detail->legal_person_code = $LegalPerson ? $LegalPerson->legal_no : '';
						$detail->platform_id = $Platform ? $Platform->platform_id : 0;
						$detail->platform_code = $Platform ? $Platform->platform_code : '';
						$store = Store::findOne(['store_code' => $value['SHOPCODE']]);
						$detail->store_id = $store ? $store->store_id : 0;
						$detail->store_code = $value['SHOPCODE'];
						$product = Product::findOne(['product_code' => $value['PACKCODE']]);
						$detail->product_id = $product ? $product->product_id : 0;
						$detail->product_code = $value['PACKCODE'];
						$manufacturer = Manufacturer::findOne(['code' => $value['BRANDCODE']]);
						$detail->manufacturer_id = $manufacturer ? $manufacturer->manufacturer_id : 0;
						$detail->manufacturer_code = $value['BRANDCODE'];
						$detail->sort_order = $value['ORDER'];
						$detail->date_modified = date('Y-m-d H:i:s', time());
						if (!$detail->save()) {
							throw new \Exception(json_encode($detail->errors));
						}
					}
				}
				if (isset($data['RELATEDDETAILS']) && $data['RELATEDDETAILS']) {
					foreach ($data['RELATEDDETAILS'] as $value) {
						$productBase = ProductBase::findOne(['product_base_code' => trim($value['PRODUCTCODE'])]);
						$product = Product::findOne(['product_code' => trim($value['PUCODE'])]);
						if (!$relate_model = AdvertiseRelated::findOne(['advertise_id' => $model->advertise_id, 'product_base_id' => $productBase ? $productBase->product_base_id : '0', 'product_id' => $product ? $product->product_id : '0'])) {
							$relate_model = new AdvertiseRelated();
						}
						$relate_model->advertise_id = $model->advertise_id;
						$relate_model->product_base_id = $productBase ? $productBase->product_base_id : 0;
						$relate_model->product_id = $product ? $product->product_id : 0;
						$relate_model->status = $value['STATUS'] == 'EXECUTING' ? 1 : 0;
						if (!$relate_model->save()) {
							throw new \Exception(json_encode($relate_model->errors));
						}
					}
				}
			}
			$transaction->commit();
		} catch (\Exception $e) {
			$status = false;
			$this->message = $e->getMessage();
			$transaction->rollBack();
		}
		return $status;
	}

	//促销专题接口
	public function actionSubject($data)
	{
		$transaction = \Yii::$app->db->beginTransaction();
		$status = true;
		try {
			if (!$model = SaleSubject::findOne(['code' => $data['code']])) {
				$model = new SaleSubject();
			}
			$model->code = $data['code'];
			$model->name = $data['name'];
			$model->status = $data['status'] == 'ACTIVE' ? 1 : 0;
			if (!$model->save()) {
				throw new \Exception("数据异常");
			}
			$transaction->commit();
		} catch (\Exception $e) {
			$status = false;
			$this->message = $e->getMessage();
			$transaction->rollBack();
		}
		return $status;
	}

	public function actionSale_promotion($data)
	{
		$transaction = \Yii::$app->db->beginTransaction();
		$status = true;
		try {
			if (!$model = SalePromotion::findOne(['code' => strval($data['code'])])) {
				$model = new SalePromotion();
			}
			$model->code = strval($data['code']);
			$model->name = $data['name'];
			$model->start_time = $data['startTime'];
			$model->end_time = $data['endTime'];
			$model->terminal = $data['terminal'];
			$model->ctype = $data['ctype'];
			$model->sub_type = $data['subType'];
			$model->csource = $data['csource'];
			$model->thumbnail = $data['thumbnail'];
			$subject = SaleSubject::findOne(['code' => $data['subjectCode']]);
			$model->subject_id = $subject ? $subject->id : 0;
			if (!$model->save()) {
				throw new \Exception(json_encode($model->errors));
			}
			$promotion_id = $model->id;
			if (isset($data['details']) && $data['details']) {
				foreach ($data['details'] as $detail) {
					if (!$model = SalePromotionDetail::findOne(['code' => strval($detail['code']), 'promotion_id' => $promotion_id])) {
						$model = new SalePromotionDetail();
					}
					$model->promotion_id = $promotion_id;
					$store = Store::findOne(['store_code' => $detail['shopCode']]);
					$model->store_id = $store ? $store->store_id : 0;
					$model->code = strval($detail['code']);
					$model->title = $detail['title'];
					$model->start_time = $detail['startTime'];
					$model->end_time = $detail['endTime'];
					$model->image = $detail['imgURL'];
					$model->status = $detail['status'] == 'EXECUTING' ? 1 : 0;
					$model->be_have_gift = $detail['beHaveGift'] ? 1 : 0;
					$model->be_free_develiery = $detail['beFreeDelivery'] ? 1 : 0;
					$model->be_have_discount = $detail['beHaveDiscount'] ? 1 : 0;
					$model->be_have_limit = $detail['beHaveLimit'] ? 1 : 0;
					$model->be_have_stop_buy = $detail['beHaveStopBuy'] ? 1 : 0;
					if (!$model->save()) {
						throw new \Exception(json_encode($model->errors));
					}
					$promotion_detail_id = $model->id;
					if ($data['ctype'] == 'PRODUCT') {
						SalePromotionDetailProduct::deleteAll(['promotion_detail_id' => $promotion_detail_id]);
						$model = new SalePromotionDetailProduct();
						$model->promotion_detail_id = $promotion_detail_id;
						$productBase = ProductBase::findOne(['product_base_code' => $detail['productCode'], 'store_id' => $store ? $store->store_id : 0]);
						$product = Product::findOne(['product_code' => $detail['puCode'], 'product_base_id' => $productBase ? $productBase->product_base_id : 0]);
						$model->product_base_id = $productBase ? $productBase->product_base_id : 0;
						$model->product_id = $product ? $product->product_id : 0;
						if (!$model->save()) {
							throw new \Exception(json_encode($model->errors));
						}
					}
					if (isset($detail['excludeProducts']) && $detail['excludeProducts']) {
						foreach ($detail['excludeProducts'] as $exclude) {
							$store = Store::findOne(['store_code' => $exclude['shopCode']]);
							$productBase = ProductBase::findOne(['product_base_code' => $exclude['productCode'], 'store_id' => $store ? $store->store_id : 0]);
							$product = Product::findOne(['product_code' => $exclude['puCode'], 'product_base_id' => $productBase ? $productBase->product_base_id : 0]);
							if (!$model = SalePromotionDetailExclude::findOne(['promotion_detail_id' => $promotion_detail_id, 'product_id' => $product ? $product->product_id : 0])) {
								$model = new SalePromotionDetailExclude();
							}
							$model->promotion_detail_id = $promotion_detail_id;
							$model->store_id = $store ? $store->store_id : 0;
							$model->product_base_id = $productBase ? $productBase->product_base_id : 0;
							$model->product_id = $product ? $product->product_id : 0;
							$model->status = $exclude['status'] == 'ACTIVE' ? 1 : 0;
							if (!$model->save()) {
								throw new \Exception(json_encode($model->errors));
							}
						}
					}
					if (isset($detail['rule']) && $detail['rule']) {
						foreach ($detail['rule'] as $rule) {
							$rule_id = $this->saleRule($rule);
							if (!$model = SalePromotionDetailToRule::findOne(['promotion_detail_id' => $promotion_detail_id, 'rule_id' => $rule_id])) {
								$model = new SalePromotionDetailToRule();
							}
							$model->promotion_detail_id = $promotion_detail_id;
							$model->rule_id = $rule_id;
							$model->status = $rule['status'] == 'ACTIVE' ? 1 : 0;
							if (!$model->save()) {
								throw new \Exception(json_encode($model->errors));
							}
						}
					}
					if (isset($detail['gift']) && $detail['gift']) {
						foreach ($detail['gift'] as $gift) {
							$store = Store::findOne(['store_code' => $gift['shopCode']]);
							$productBase = ProductBase::findOne(['product_base_code' => $gift['productCode'], 'store_id' => $store ? $store->store_id : 0]);
							$product = Product::findOne(['product_code' => $gift['puCode'], 'product_base_id' => $productBase ? $productBase->product_base_id : 0]);
							if (!$model = SalePromotionDetailGift::findOne(['code' => $gift['code'], 'promotion_detail_id' => $promotion_detail_id])) {
								$model = new SalePromotionDetailGift();
							}
							$model->code = strval($gift['code']);
							$model->promotion_detail_id = $promotion_detail_id;
							$model->store_id = $store ? $store->store_id : 0;
							$model->product_base_id = $productBase ? $productBase->product_base_id : 0;
							$model->product_id = $product ? $product->product_id : 0;
							$model->qty = $gift['qty'];
							$model->be_have_limit = $gift['beHaveLimit'] ? 1 : 0;
							$model->be_have_money = $gift['beHaveMoney'] ? 1 : 0;
							$model->be_need_money = $gift['beNeedMoney'] ? 1 : 0;
							$model->status = $gift['status'] == 'ACTIVE' ? 1 : 0;
							if (!$model->save()) {
								throw new \Exception(json_encode($model->errors));
							}
							$promotion_detail_gift_id = $model->id;
							if (isset($gift['rule']) && $gift['rule']) {
								$rule_id = $this->saleRule($rule);
								if (!$model = SalePromotionDetailGiftToRule::findOne(['promotion_detail_gift_id' => $promotion_detail_gift_id, 'rule_id' => $rule_id])) {
									$model = new SalePromotionDetailGiftToRule();
								}
								$model->promotion_detail_gift_id = $promotion_detail_gift_id;
								$model->rule_id = $rule_id;
								$model->status = $rule['status'] == 'ACTIVE' ? 1 : 0;
								if (!$model->save()) {
									throw new \Exception(json_encode($model->errors));
								}
							}
						}
					}
				}
			}
			$transaction->commit();
		} catch (\Exception $e) {
			$status = false;
			$this->message = $e->getMessage();
			$transaction->rollBack();
		}
		return $status;

	}

	public function actionPushmessage($datas)
	{
		$transaction = \Yii::$app->db->beginTransaction();
		$status = true;
		try {
			foreach ($datas as $data) {
				if (isset($data['detail'])) {
					foreach ($data['detail'] as $value) {
						$model = new SellerMessage();
						$model->message_type = $data['message_type'];
						$model->title = $data['title'];
						$model->content = $data['content'];
						$model->open_id = $value['open_id'];
						$model->message_source = $data['source'];
						$model->data_added = date('Y-m-d H:i:s', time());
						$model->data_modify = date('Y-m-d H:i:s', time());
						if (!$model->save()) {
							throw new \Exception(json_encode($model->errors));
						}
					}
				}
			}
			$transaction->commit();
		} catch (\Exception $e) {
			$status = false;
			$this->message = $e->getMessage();
			$transaction->rollBack();
		}
		return $status;
	}

	public function actionOrderstatus($datas)
	{
		$transaction = \Yii::$app->db->beginTransaction();
		$status = true;
		try {
			foreach ($datas as $data) {
				if (strpos($data['CODE'], 'RO') !== false) {
					$return_status = ReturnStatus::findOne(['code' => $data['STATUS']]);
					if ($return_status) {
						if ($model = ReturnBase::findOne(['return_code' => $data['CODE']])) {
							if ($model->return_status_id !== $return_status->return_status_id) {
								$model->date_modified = $data['UPDATETIME'];
								$model->return_status_id = $return_status->return_status_id;
								if (!$model->save()) {
									throw new \Exception(json_encode($model->errors));
								}
								$return_id = $model->return_id;
								$model = new ReturnHistory();
								$model->return_id = $return_id;
								$model->return_status_id = $return_status->return_status_id;
								$model->notify = 0;
								$model->comment = "你的订单状态更新为:" . $return_status->name;;
								$model->date_added = $data['UPDATETIME'];
								if (!$model->save()) {
									throw new \Exception(json_encode($model->errors));
								}
							}
						}
					}
				} else {
					$order_status = OrderStatus::findOne(['code' => trim($data['STATUS'])]);
					if ($order_status) {
						$order_id = trim($data['CODE'], 'O');
						if (strpos($data['CODE'], '-')) {
							list($order_id, $order_cycle_id) = explode('-', $order_id);
							$order_id = intval($order_id);
							if ($model = OrderCycle::findOne(['order_id' => $order_id, 'order_cycle_id' => $order_cycle_id])) {
								if ($model->order_status_id !== $order_status->order_status_id) {
									$model->order_status_id = $order_status->order_status_id;
									$model->save();
								}
							}
						} else {
							if (strpos($order_id, "_")) {
								$order_id = substr($order_id, strpos($order_id, "_"));
							}
							$order_id = intval($order_id);
							if ($model = Order::findOne(['order_id' => $order_id])) {
								if ($model->order_status_id !== $order_status->order_status_id) {
									$model->date_modified = $data['UPDATETIME'];
									$model->order_status_id = $order_status->order_status_id;
									if (!$model->save()) {
										throw new \Exception(json_encode($model->errors));
									}
									$order = $model;
									$model = new OrderHistory();
									$model->order_id = $order_id;
									$model->order_status_id = $order_status->order_status_id;
									$model->notify = 0;
									$model->comment = "你的订单状态更新为:" . $order_status->name;
									$model->date_added = $data['UPDATETIME'];
									if (!$model->save()) {
										throw new \Exception(json_encode($model->errors));
									}
									if ($order->order_status_id == 10) {
										if ($open_id = $order->customer->getWxOpenId()) {
											$message = "感谢您此次购物，您可点击详情对本次服务进行评价！您的评价对物流小哥，很重要哟！祝您购物愉快！";
											$notice = new \common\component\Notice\WxNotice();
											$notice->shouhuo($open_id, "https://m.mrhuigou.com/order/delivery?order_no=" . $order->order_no, ['title' => '尊敬的每日惠购会员,您的订单号' . $order->order_no . "已经收货！", 'address' => $order->orderShipping->shipping_address_1,'order_no'=>$order->order_no, 'date_time' => date('Y-m-d H:i:s', time()), 'remark' => $message]);
										}
									}
								}
							}
						}
					}
				}
			}
			$transaction->commit();
		} catch (\Exception $e) {
			$status = false;
			$this->message = $e->getMessage();
			$transaction->rollBack();
		}
		return $status;
	}

	//平台自提点数据
	public function actionSelfdelivery($datas)
	{
		$transaction = \Yii::$app->db->beginTransaction();
		$status = true;
		try {
			foreach ($datas as $data) {
				if (!$model = PlatformStation::findOne(['code' => $data['CODE']])) {
					$model = new PlatformStation();
				}
				$model->code = $data['CODE'];
				$model->name = $data['NAME'];
				$model->address = $data['ADDRESS'];
				$model->contact_name = $data['CONTACT_NAME'];
				$model->telephone = $data['TELEPHONE'];
				$model->latitude = $data['LATITUDE'];
				$model->longitude = $data['LONGITUDE'];
				$model->open_time = $data['OPEN_TIME'];
				$model->is_open = $data['BE_PUBLIC'] ? 1 : 0;
				$model->is_fresh = $data['BE_FRESH'] ? 1 : 0;
				$model->description = $data['DESCRIPTION'];
				$model->status = $data['STATUS'] == 'ACTIVE' ? 1 : 0;
				$platform = Platform::findOne(['platform_code' => $data['MARKET']]);
				$model->platform_id = $platform ? $platform->platform_id : 0;
				$store = Store::findOne(['store_code' => $data['SHOP']]);
				$model->store_id = $store ? $store->store_id : 0;
				$zone = Zone::findOne(['code' => $data['PROVINCE']]);
				$model->zone_id = $zone ? $zone->zone_id : 0;
				$city = City::findOne(['code' => $data['CITY']]);
				$model->city_id = $city ? $city->city_id : 0;
				$district = District::findOne(['code' => $data['DISTRICT']]);
				$model->district_id = $district ? $district->district_id : 0;
				if (!$model->save()) {
					throw new \Exception(json_encode($model->errors));
				}
			}
			$transaction->commit();
		} catch (\Exception $e) {
			$status = false;
			$this->message = $e->getMessage();
			$transaction->rollBack();
		}
		return $status;

	}

	public function actionPaytoshop($datas)
	{
		if ($datas) {
			$request_data = $datas[0];
//		$request_data=[
//			'bank_flag'=>0,
//			'bankStoreage'=>1,
//			'cert_no'=>date('YmdHis',time()),
//			'rcv_bank_name'=>'交行水清沟支行',
//			'rcv_acname'=>'青岛每日惠购电子科技有限公司',
//			'rcv_acno'=>'372005551018010059577',
//			'cur_code'=>'CNY',
//			'amt'=>'1.00',
//			'summary'=>'测试转账',
//			'accountType'=>1,
//			'bank_flag'=>0,
//			'area_flag'=>0,
//			'tr_acdt'=>date('Ymd',time()),//交易日期
//			'tr_time'=>date('His',time()),//时间
//		];
			$tr_code = '210201';
			if ($request_data['bank_flag'] == 0 && preg_match("/^622260\d{13}/", $request_data['rcv_acno'])) {
				$tr_code = '330002';
			}
			if (isset($request_data['bankStoreage']) && $request_data['bankStoreage'] && isset($request_data['accountType']) && $request_data['accountType'] == 1) {
				$tr_code = '330002';
			}
			if ($tr_code == '210201') {
				$arrray['head'] = [
					'tr_code' => $tr_code,//交易码 对外转帐系统
					'corp_no' => '8000427588',//企业代码
					'user_no' => '00005',//企业用户号
					'req_no' => '0001',//发起方序号
					'tr_acdt' => $request_data['tr_acdt'],//date('Ymd',time()),//交易日期
					'tr_time' => $request_data['tr_time'],//date('His',time()),//时间
					'atom_tr_count' => '1',//原子交易数
					'channel' => '0',
					'reserved' => ''//保留字段
				];
				$arrray['body'] = [
					'pay_acno' => '372005500018010301879',
					'pay_acname' => Helper::auto_charset('青岛每日惠购电子科技有限公司', 'utf-8', 'gbk'),
					'rcv_bank_name' => Helper::auto_charset($request_data['rcv_bank_name'], 'utf-8', 'gbk'),
					'rcv_acno' => $request_data['rcv_acno'],//'372005551018010059577',
					'rcv_acname' => Helper::auto_charset($request_data['rcv_acname'], 'utf-8', 'gbk'),//auto_charset('青岛每日惠购电子科技有限公司','utf-8','gbk'),
					'cur_code' => $request_data['cur_code'],//'CNY',
					'amt' => sprintf("%.2f", $request_data['amt']),//'1.00',
					'cert_no' => $request_data['cert_no'],//'201409190110',
					'summary' => Helper::auto_charset($request_data['summary'], 'utf-8', 'gbk'),//auto_charset('测试转账','utf-8','gbk'),
					'bank_flag' => $request_data['bank_flag'],//0
					'area_flag' => $request_data['area_flag']//'0'
				];
			} else {
				$arrray['head'] = [
					'tr_code' => $tr_code,//交易码 对外转帐系统
					'corp_no' => '8000427588',//企业代码
					'user_no' => '00005',//企业用户号
					'req_no' => '0001',//发起方序号
					'tr_acdt' => $request_data['tr_acdt'],//date('Ymd',time()),//交易日期
					'tr_time' => $request_data['tr_time'],//date('His',time()),//时间
					'atom_tr_count' => '1',//原子交易数
					'channel' => '0',
					'reserved' => ''//保留字段
				];
				$arrray['body'] = [
					'cert_no' => $request_data['cert_no'],
					'pay_acno' => '372005500018010301879',
					'type' => 'f',
					'sum' => 1,
					'sum_amt' => sprintf("%.2f", $request_data['amt']),
					'pay_month' => date("Ym", time()),
					'summary' => Helper::auto_charset($request_data['summary'], 'utf-8', 'gbk'),//auto_charset('测试转账','utf-8','gbk'),
					'busi_no' => '3720007537',
					'mailflg' => 'Y',
					'tran' => [
						'rcd' => [
							'card_no' => $request_data['rcv_acno'],
							'acname' => Helper::auto_charset($request_data['rcv_acname'], 'utf-8', 'gbk'),
							'card_flag' => $request_data['bankStoreage'] == 1 ? 0 : 1,
							'amt' => sprintf("%.2f", $request_data['amt']),
							'busino' => $request_data['cert_no']
						]
					]
				];
			}
			$post_data = Helper::arrayToxml(['ap' => $arrray]);
			$url = 'http://192.168.0.92:8899';
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
			ob_start();
			curl_exec($ch);
			$result = ob_get_contents();
			ob_end_clean();
			$result = Helper::auto_charset($result, 'gbk', 'utf-8');
			$xml = simplexml_load_string($result);
			$result_data = json_decode(json_encode($xml), true);
			if ($result_data) {
				if (isset($result_data['head']['particular_code']) && $result_data['head']['particular_code'] == '0000') {
					$this->message = "转账成功!";
					$this->data = [
						'tr_code' => $result_data['head']['tr_code'],
						'req_no' => $result_data['head']['req_no'],
						'cert_no' => $request_data['cert_no'],
						'serial_no' => $result_data['head']['serial_no'],
						'tr_acdt' => $result_data['head']['tr_acdt'],
						'tr_time' => $result_data['head']['tr_time'],
						'particular_code' => $result_data['head']['particular_code'],
						'particular_info' => $result_data['head']['particular_info'],
					];
					return true;
				} else {
					$this->code = $result_data['head']['particular_code'];
					$this->message = $result_data['head']['particular_info'];
					$this->data = [
						'tr_code' => $result_data['head']['tr_code'],
						'req_no' => $result_data['head']['req_no'],
						'cert_no' => $request_data['cert_no'],
						'serial_no' => $result_data['head']['serial_no'],
						'tr_acdt' => $result_data['head']['tr_acdt'],
						'tr_time' => $result_data['head']['tr_time'],
						'particular_code' => $result_data['head']['particular_code'],
						'particular_info' => $result_data['head']['particular_info'],
					];
					return false;
				}
			} else {
				$this->code = 500;
				$this->message = "结算服务器异常!";
				$this->data = [
					'particular_code' => 500,
					'particular_info' => '结算服务器异常!',
				];
				return false;
			}
		} else {
			$this->code = 500;
			$this->message = "数据不能为空!";
			$this->data = [
				'particular_code' => 500,
				'particular_info' => '数据不能为空!',
			];
			return false;
		}
	}

	//接收参数
	public function bindActionParams($action, $params)
	{
		return $params;
	}

	public function saleRule($rule)
	{
		$transaction = \Yii::$app->db->beginTransaction();
		try {
			if (!$model = SaleRule::findOne(['code' => strval($rule['code'])])) {
				$model = new SaleRule();
			}
			$model->code = strval($rule['code']);
			$model->name = $rule['name'];
			$model->ref_type = $rule['refType'];
			$model->ref_sub_type = $rule['refSubType'];
			$model->be_main = $rule['beMain'] ? 1 : 0;
			if (!$model->save()) {
				throw new \Exception(json_encode($model->errors));
			}
			$rule_id = $model->id;
			if (isset($rule['attrs']) && $rule['attrs']) {
				foreach ($rule['attrs'] as $attr) {
					if (!$model = SaleRuleAttribute::findOne(['code' => strval($attr['name']), 'rule_id' => $rule_id])) {
						$model = new SaleRuleAttribute();
					}
					$model->rule_id = $rule_id;
					$model->code = strval($attr['name']);
					$model->name = $attr['name'];
					$model->value = $attr['value'];
					if (!$model->save()) {
						throw new \Exception(json_encode($model->errors));
					}
				}
			}
			$transaction->commit();
		} catch (\Exception $e) {
			$transaction->rollBack();
			throw new \Exception($e->getMessage());
		}
		return $rule_id;
	}

	public function actionSms($datas)
	{
		$message = [
			'suspend' => 492770,
		];
		$status = true;
		if ($datas) {
			try {
				foreach ($datas as $data) {
					$sms = new Msg();
					$respose = $sms->sendTemplateSMS($data['to'], $data['params'], $message[$data['type']]);
					if (isset($respose['statusCode']) && $respose['statusCode'] == '000000') {
						$status = true;
					} else {
						$status = false;
						$this->message = "参数错误";
					}
				}

			} catch (\Exception $e) {
				$status = false;
				$this->message = $e->getMessage();
			}
		}
		return $status;
	}

	public function actionGroundpush($datas)
	{
        $status = false;
		$transaction = \Yii::$app->db->beginTransaction();
		try {
			foreach ($datas as $data) {
				if (!$model = GroundPushPoint::findOne(['code' => $data['CODE']])) {
					$model = new GroundPushPoint();
					$model->create_at = time();
				}
				$model->code = $data['CODE'];
                $model->type = $data['TYPE'];
				$model->name = $data['NAME'];
				$model->zone_code = $data['PROVINCE_CODE'];
				$model->city_code = $data['CITY_CODE'];
				$model->district_code = $data['DISTRICT_CODE'];
				$model->address = $data['ADDRESS'];
				$model->contact_name = $data['CONTACT_NAME'];
				$model->contact_tel = $data['TELEPHONE'];
				$model->status = $data['STATUS'] = $data['STATUS'] == 'ACTIVE' ? 1 : 0;
                $model->pass = $data['PASSWORD'];
                $model->leaflet = $data['LEAFLETS'];
				$model->update_at = time();
				if (!$model->save(false)) {
					throw new \Exception(json_encode($model->errors));
				}
			}
			$transaction->commit();
			$status = true;
		} catch (\Exception $e) {
			$transaction->rollBack();
			throw new \Exception($e->getMessage());
		}
		return $status;
	}

	public function actionGroundpushplan($datas)
	{
        $status = false;
		$transaction = \Yii::$app->db->beginTransaction();
		try {
			foreach ($datas as $data) {
				if (!$plan = GroundPushPlan::findOne(['code' => $data['CODE']])) {
					$plan = new GroundPushPlan();
					$plan->create_at = time();
				}
				$ground_push = GroundPushPoint::findOne(['code' => $data['POINT_CODE']]);
				$plan->ground_push_point_id = $ground_push ? $ground_push->id : 0;
				$plan->code = $data['CODE'];
				$plan->name = $data['NAME'];
                $plan->type = $data['TYPE'];
				$plan->begin_date_time = $data['BEGIN_DATE'];
				$plan->end_date_time = $data['END_DATE'];
				$plan->shipping_end_time = $data['SELF_END_DATE'];
				$plan->contact_name = $data['CONTACT_NAME'];
				$plan->contact_tel = $data['TELEPHONE'];
				$plan->status = $data['STATUS'] = $data['STATUS'] == 'EXECUTING' ? 1 : 0;
				if (!$plan->save(false)) {
					throw new \Exception(json_encode($plan->errors));
				}
				if (isset($data['DETAILS']) && $data['DETAILS']) {
					foreach ($data['DETAILS'] as $detail) {
						if (!$plan_view = GroundPushPlanView::findOne(['code' => $detail['DETAIL_CODE']])) {
							$plan_view = new GroundPushPlanView();
						}
                        $plan_view->code = $detail['DETAIL_CODE'];
						$plan_view->ground_push_plan_id = $plan->id;
						$plan_view->product_code = $detail['PUCODE'];
						$plan_view->price = $detail['PRICE'];
						$plan_view->max_buy_qty = $detail['MAX_LIMIT_QUANTITY'];
						$plan_view->sort_order = $detail['SORT'];
						$plan_view->status = $detail['DETAIL_STATUS'] == 'EXECUTING' ? 1 : 0;
						if (!$plan_view->save(false)) {
							throw new \Exception(json_encode($plan_view->errors));
						}
					}
				}
			}
			$transaction->commit();
            $status = true;
		} catch (\Exception $e) {
			$transaction->rollBack();
			throw new \Exception($e->getMessage());
		}
		return $status;
	}

	public function actionGroundpushstock($datas)
	{
        $status = false;
		$transaction = \Yii::$app->db->beginTransaction();
		try {
			foreach ($datas as $data) {
				if (!$model = GroundPushPoint::findOne(['code' => $data['PUSH_POINT_CODE']])) {
					continue;
				}
				$stock_log = new GroundPushStockLog();
				$stock_log->ground_push_point_id = $model->id;
				$stock_log->type = $data['TYPE'];
				$stock_log->product_code = $data['PUCODE'];
				$stock_log->qty = $data['QUANTITY'];
				$stock_log->last_time = $data['DATE_TIME'];
				if (!$stock_log->save(false)) {
					throw new \Exception(json_encode($stock_log->errors));
				}
				$fn = function ($model, $stock_log) use (&$fn) {
					if ($stock_log->type == 'ADD') {
						if (!$stock = GroundPushStock::findOne(['ground_push_point_id' => $model->id, 'product_code' => $stock_log->product_code])) {
							$stock = new GroundPushStock();
							$stock->ground_push_point_id = $model->id;
							$stock->product_code = $stock_log->product_code;
							$stock->qty = $stock_log->qty;
							$stock->tmp_qty = 0;
							$stock->last_time = date('Y-m-d H:i:s', time());
							if (!$stock->save(false)) {
								throw new \Exception(json_encode($stock->errors));
							}
						} else {
							try {
								$stock->ground_push_point_id = $model->id;
								$stock->product_code = $stock_log->product_code;
								$stock->qty = $stock->qty + $stock_log->qty;
								$stock->last_time = date('Y-m-d H:i:s', time());
								if (!$stock->save(false)) {
									throw new \Exception(json_encode($stock->errors));
								}
							} catch (StaleObjectException $e) {
								//重新验证下状态
								$fn($model, $stock_log);
							}
						}
					} else {
						if (!$stock = GroundPushStock::findOne(['ground_push_point_id' => $model->id, 'product_code' => $stock_log->product_code])) {
							$stock = new GroundPushStock();
							$stock->ground_push_point_id = $model->id;
							$stock->product_code = $stock_log->product_code;
							$stock->qty = $stock->qty - $stock_log->qty;
							$stock->tmp_qty = 0;
							$stock->last_time = date('Y-m-d H:i:s', time());
							if (!$stock->save(false)) {
								throw new \Exception(json_encode($stock->errors));
							}
						} else {
							try {
								$stock->ground_push_point_id = $model->id;
								$stock->product_code = $stock_log->product_code;
								$stock->qty = $stock->qty - $stock_log->qty;
								$stock->last_time = date('Y-m-d H:i:s', time());
								if (!$stock->save(false)) {
									throw new \Exception(json_encode($stock->errors));
								}
							} catch (StaleObjectException $e) {
								//重新验证下状态
								$fn($model, $stock_log);
							}
						}
					}
				};
				$fn($model, $stock_log);
			}
			$transaction->commit();
            $status = true;
		} catch (\Exception $e) {
		    \Yii::error($e->getMessage().''.$e->getLine());
			$transaction->rollBack();
			throw new \Exception($e->getMessage());
		}
		return $status;
	}
	public function actionRfreturnorder($datas)
    {
        $status = false;
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            foreach ($datas as $data) {
                if($return_status = ReturnStatus::findOne(['code'=>$data['STATUS']])){

                    if(!$model = ReturnBase::findOne(['return_code'=>$data['CODE']])){
                        $model = new  ReturnBase();
                    }

                    $model->return_code = $data['CODE'];
                    $model->order_id = substr($data['RELATEDBILL1'],1);
                    $order = Order::findOne(['order_id'=>$model->order_id]);
                    $model->order_code = $data['RELATEDBILL1'];
                    $model->order_no = $order->order_no;
                    $model->date_ordered = $order->date_added;
                    $model->customer_id = $order->customer_id;
                    $model->firstname = $data['CONTACTNAME'];
                    $model->email = "";
                    $model->telephone = $data['MOBILE'];
                    $model->return_status_id = $return_status->return_status_id;
                    $model->comment = $data['DESCRIPTION'];
                    $model->total = $data['PAYMENT'];//退款金额
                    if($model->isNewRecord){
                        $model->date_added = date('Y-m-d H:i:s');
                    }

                    $model->date_modified = date('Y-m-d H:i:s');
                    $model->send_status = 1;
                    $model->is_all_return = $data['RETURNTYPE'] == 'PART' ? 0 : 1 ;
                    $model->return_method = $data['RETURN_DEAL_METHOD'];


                    $model->save(false);
                    if($model->hasErrors()){
                        throw  new  Exception("model:".json_encode($model->errors));
                    }


                    $product_types = ['order_gift','order_product','product'];
                    $order_total_types = ['sub_total','shipping','coupon','total','credit','order_promotion','change_total','change','order','points','other'];
                    if($data['DETAILS']){
                        ReturnTotal::deleteAll(['return_id'=>$model->return_id]);
                        foreach ($data['DETAILS'] as $product_data){
                            if( in_array(strtolower($product_data['TYPE']),$product_types)){
                                if(!$return_product = ReturnProduct::findOne(['return_code'=>$product_data['ORDERCODE'],'product_code'=>$product_data['PUCODE']])){
                                    $return_product = new ReturnProduct();
                                }
                                $order_product = OrderProduct::findOne(['order_id'=>$model->order_id,'product_code'=>$product_data['PUCODE']]);
                                $store = Store::findOne(['store_code'=>$product_data['SHOPCODE']]);
                                $return_product->order_product_id = $order_product->order_product_id;
                                $return_product->return_code = $product_data['ORDERCODE'];
                                $return_product->return_id = $model->return_id;
                                $return_product->product_base_id = $order_product->product_base_id;
                                $return_product->product_base_code = $order_product->product_base_code;
                                $return_product->store_code = $product_data['SHOPCODE'];
                                $return_product->store_id = $store->store_id;
                                $return_product->product_id = $order_product->product_id;
                                $return_product->product_code = $order_product->product_code;
                                $return_product->model = 'default';
                                $return_product->name = $order_product->name;
                                $return_product->quantity = $product_data['QUANTITY'];
                                $return_product->total = $product_data['AMOUNT'];
                                $return_product->product_total = $product_data['PAYMENT'];
                                $return_product->unit = $order_product->unit;
                                $return_product->format = $order_product->format;
                                $return_product->opened = 0;
                                $return_product->comment = $product_data['DESCRIPTION'];
                                $return_product->return_reason_id = 4;
                                $return_product->return_action_id = 0;
                                $return_product->from_table = 'order_product';
                                $return_product->from_id = $order_product->order_product_id;
                                $return_product->save();
                                if($return_product->hasErrors()){
                                    throw  new  Exception("return_product:".json_encode($return_product->errors));
                                }
                            }
                            if( in_array(strtolower($product_data['TYPE']),$order_total_types)){
                                $return_total = new  ReturnTotal();
                                $return_total->return_id = $model->return_id;
                                $return_total->code = $product_data['TYPE'];
                                $return_total->title = $product_data['DESCRIPTION'];
                                $return_total->text = '￥'.$product_data['AMOUNT'];
                                $return_total->value = $product_data['AMOUNT'];
                                $return_total->sort_order = $product_data['LINENO'];
                                $return_total->save();
                                if($return_total->hasErrors()){
                                    throw  new  Exception("return_total:".json_encode($return_total->errors));
                                }
                            }
                        }
                    }

                }
            }
            $transaction->commit();
            $status = true;
        } catch (\Exception $e) {
            $transaction->rollBack();
            $status = false;
            throw new \Exception($e->getMessage());
        }

        return $status;
    }
    public function actionAffiliatepersonal($datas){
        $status = false;
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            foreach ($datas as $data) {
                if (!$aff_personal = AffiliatePersonal::findOne(['code' => $data['CODE']])) {
                    $aff_personal = new AffiliatePersonal();
                    $aff_personal->date_added = time();
                }

                $aff_personal->code = $data['CODE'];
                $aff_personal->name = $data['NAME'];
                $aff_personal->type = isset($data['TYPE']) ? $data['TYPE'] :'PRODUCT';
                $aff_personal->date_start = $data['BEGIN_DATE'];
                $aff_personal->date_end = $data['END_DATE'];
                $aff_personal->description = $data['DESCRIPTION'];
                $aff_personal->img = isset($data['IMG']) ? $data['IMG'] :'';
                $aff_personal->status = $data['STATUS'] = $data['STATUS'] == 'EXECUTING' ? 1 : 0;
                $aff_personal->date_update = date('Y-m-d H:i:s');
                if (!$aff_personal->save(false)) {
                    throw new \Exception(json_encode($aff_personal->errors));
                }
                if (isset($data['DETAILS']) && $data['DETAILS']) {
                    foreach ($data['DETAILS'] as $detail) {
                        if (!$aff_personal_detail = AffiliatePersonalDetail::findOne(['detail_code' => $detail['DETAIL_ID']])) {
                            $aff_personal_detail = new AffiliatePersonalDetail();
                        }
                        $aff_personal_detail->affiliate_personal_id = $aff_personal->affiliate_personal_id;
                        $aff_personal_detail->detail_code = $detail['DETAIL_ID'];
                        $aff_personal_detail->product_code = $detail['PUCODE'];
                        $product = Product::findOne(['product_code'=>$detail['PUCODE']]);
                        $aff_personal_detail->product_id = $product->product_id;
                        if($detail['BACK_TYPE'] == 'PERCENTAGE'){
                            $aff_personal_detail->commission_type = 'P';
                            $aff_personal_detail->commission = $detail['RATE'];
                        }else{
                            $aff_personal_detail->commission_type = 'F';
                            $aff_personal_detail->commission = $detail['BACK_AMOUNT'];
                        }

                        if (!$aff_personal_detail->save(false)) {
                            throw new \Exception(json_encode($aff_personal_detail->errors));
                        }
                    }
                }
            }
            $transaction->commit();
            $status = true;
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw new \Exception($e->getMessage());
        }
        return $status;
    }
}
