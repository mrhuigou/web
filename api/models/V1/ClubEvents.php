<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%club_events}}".
 *
 * @property integer $events_id
 * @property integer $events_type
 * @property string $title
 * @property string $description
 * @property string $city
 * @property string $district
 * @property string $address
 * @property string $longitude
 * @property string $latitude
 * @property string $image_array
 * @property string $cover_image
 * @property string $created_at
 * @property string $updated_at
 * @property string $sign_start_time
 * @property string $sign_end_time
 * @property string $start_time
 * @property string $end_time
 * @property integer $by_customer_id
 * @property integer $by_group_id
 * @property integer $member_limit
 * @property string $member_gender
 * @property integer $is_assemble
 * @property string $assemble_time
 * @property string $assemble_address
 * @property integer $join_verify
 * @property integer $permission
 * @property string $tag_id
 * @property integer $has_fee
 * @property integer $is_pop
 * @property integer $is_platform
 * @property string $deleted_at
 * @property integer $status
 * @property string $qr_code
 */
class ClubEvents extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%club_events}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['events_type', 'by_customer_id', 'by_group_id', 'member_limit', 'is_assemble', 'join_verify', 'permission', 'has_fee', 'is_pop', 'is_platform', 'status'], 'integer'],
            [['description', 'image_array'], 'string'],
            [['created_at', 'updated_at', 'sign_start_time', 'sign_end_time', 'start_time', 'end_time', 'assemble_time', 'deleted_at'], 'safe'],
            [['title', 'address', 'longitude', 'latitude', 'cover_image', 'assemble_address', 'tag_id', 'qr_code'], 'string', 'max' => 255],
            [['city', 'district'], 'string', 'max' => 32],
            [['member_gender'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'events_id' => 'Events ID',
            'events_type' => 'Events Type',
            'title' => 'Title',
            'description' => 'Description',
            'city' => 'City',
            'district' => 'District',
            'address' => 'Address',
            'longitude' => 'Longitude',
            'latitude' => 'Latitude',
            'image_array' => 'Image Array',
            'cover_image' => 'Cover Image',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'sign_start_time' => 'Sign Start Time',
            'sign_end_time' => 'Sign End Time',
            'start_time' => 'Start Time',
            'end_time' => 'End Time',
            'by_customer_id' => 'By Customer ID',
            'by_group_id' => 'By Group ID',
            'member_limit' => 'Member Limit',
            'member_gender' => 'Member Gender',
            'is_assemble' => 'Is Assemble',
            'assemble_time' => 'Assemble Time',
            'assemble_address' => 'Assemble Address',
            'join_verify' => 'Join Verify',
            'permission' => 'Permission',
            'tag_id' => 'Tag ID',
            'has_fee' => 'Has Fee',
            'is_pop' => 'Is Pop',
            'is_platform' => 'Is Platform',
            'deleted_at' => 'Deleted At',
            'status' => 'Status',
            'qr_code' => 'Qr Code',
        ];
    }
    public function getEventFee()
    {
        return $this->hasMany(ClubEventsFee::className(), ['events_id' => 'events_id']);
    }
}
