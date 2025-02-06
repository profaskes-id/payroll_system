<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "message_receiver".
 *
 * @property int $id
 * @property int $message_id
 * @property int $receiver_id
 * @property int $is_open
 * @property string|null $open_at
 */
class MessageReceiver extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'message_receiver';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['message_id', 'receiver_id'], 'required'],
            [['message_id', 'receiver_id', 'is_open'], 'integer'],
            [['open_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'message_id' => 'Message ID',
            'receiver_id' => 'Receiver ID',
            'is_open' => 'Is Open',
            'open_at' => 'Open At',
        ];
    }
}
