<?php

namespace backend\controllers;

use backend\models\Message;
use backend\models\MessageReceiver;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AdminNotificationController implements the CRUD actions for Absensi model.
 */
class AdminNotificationController extends Controller
{
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),

                ],
                'access' => [
                    'class' => \yii\filters\AccessControl::class,
                    'rules' => [

                        [
                            'allow' => true,
                            'roles' => ['@'], // Allow authenticated users
                            'matchCallback' => function ($rule, $action) {
                                $user = Yii::$app->user;
                                // Check if the user does  have the 'admin' or 'super admin' role
                                return $user->can('admin') || $user->can('super_admin');
                            },
                        ],
                    ],
                ],
            ]
        );
    }


    public function beforeAction($action)
    {

        if ($action->id == 'open-message') {
            // Menonaktifkan CSRF verification untuk aksi 'view'
            $this->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    }

    /**
     * Lists all Absensi models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $messages = Message::find()
            ->select(['message.*', 'message_receiver.is_open'])
            ->innerJoin('message_receiver', 'message.id_message = message_receiver.message_id')
            ->where(['message_receiver.receiver_id' => Yii::$app->user->id])
            ->orderBy(['message.create_at' => SORT_DESC])
            ->asArray()
            ->all();

        return $this->render('index', [
            'messages' => $messages,
        ]);
    }

    public function actionOpenMessage()
    {
        if (Yii::$app->request->isPost) {
            $messageId = Yii::$app->request->post('messageId'); // Ambil messageId dari POST
            $nama_transaksi = Yii::$app->request->post('nama_transaksi'); // Ambil messageId dari POST
            $id_transaksi = Yii::$app->request->post('id_transaksi'); // Ambil messageId dari POST
            $receiverId = Yii::$app->user->id; // Ambil ID penerima dari session
            $messageReceiver = MessageReceiver::find()
                ->where(['message_id' => $messageId, 'receiver_id' => $receiverId])
                ->one();

            if ($messageReceiver) {
                $messageReceiver->is_open = 1;
                $messageReceiver->open_at = new \yii\db\Expression('NOW()');
                if ($messageReceiver->save()) {

                    return $this->redirect($nama_transaksi . $id_transaksi);
                }
            }
        }

        // Jika tidak berhasil, arahkan ke halaman error atau kembali ke halaman sebelumnya
        return $this->redirect(['/panel']); // Ganti dengan URL yang sesuai
    }
}
