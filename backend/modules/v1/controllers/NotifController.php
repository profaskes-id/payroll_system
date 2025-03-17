<?php

namespace app\modules\v1\controllers;

use amnah\yii2\user\models\User;
use backend\models\Message as MessageModel;
use backend\models\MessageReceiver;
use yii\rest\ActiveController;
use yii\web\Response;
use Yii;

class NotifController extends ActiveController
{
    public $modelClass = MessageModel::class;

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index']);
        return $actions;
    }

    public function actionIsAda($iduser)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $is_ada_notif =  MessageReceiver::find()
            ->where(['receiver_id' => $iduser, 'is_open' => 0])
            ->count();
        return $is_ada_notif;
    }


    public function actionIndex($id_karyawan)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $user = User::find()->where(['id_karyawan' => $id_karyawan])->asArray()->one();

        if ($user === null) {
            return [
                'success' => false,
                'message' => 'User  not found, tambahkan user terlebih dahulu dengan invite di menu karyawan',
            ];
        }

        $messages = MessageModel::find()
            ->select(['message.*', 'message_receiver.is_open'])
            ->innerJoin('message_receiver', 'message.id_message = message_receiver.message_id')
            ->where(['message_receiver.receiver_id' => $user['id']])
            ->orderBy(['message.create_at' => SORT_DESC])
            ->asArray()
            ->all();

        return $messages;
    }

    public function actionOpenMessage($id_user)
    {
        if (Yii::$app->request->isPost) {
            $messageId = Yii::$app->request->post('messageId'); // Ambil messageId dari POST
            $receiverId = $id_user; // Ambil ID penerima dari session

            // Mencari MessageReceiver berdasarkan message_id dan receiver_id
            $messageReceiver = MessageReceiver::find()
                ->where(['message_id' => $messageId, 'receiver_id' => $receiverId])
                ->one();

            if ($messageReceiver) {
                $messageReceiver->is_open = 1;
                $messageReceiver->open_at = new \yii\db\Expression('NOW()');

                if ($messageReceiver->save()) {
                    // Mengembalikan respons sukses
                    return $this->asJson([
                        'success' => true,
                        'message' => 'Message opened successfully.',
                    ]);
                } else {
                    // Mengembalikan respons gagal saat menyimpan
                    return $this->asJson([
                        'success' => false,
                        'message' => 'Failed to open message.',
                    ]);
                }
            } else {
                // Mengembalikan respons jika messageReceiver tidak ditemukan
                return $this->asJson([
                    'success' => false,
                    'message' => 'Message not found.',
                ]);
            }
        }

        // Mengembalikan respons jika bukan POST request
        return $this->asJson([
            'success' => false,
            'message' => 'Invalid request method.',
        ]);
    }
}
