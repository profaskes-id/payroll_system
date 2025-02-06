<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "faq".
 *
 * @property int $id_faq
 * @property string $question
 * @property string $answer
 * @property int $status
 */
class Faq extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'faq';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['question', 'answer'], 'required'],
            [['question', 'answer'], 'string'],
            [['status'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_faq' => 'Id Faq',
            'question' => 'Question',
            'answer' => 'Answer',
            'status' => 'Status',
        ];
    }
}
