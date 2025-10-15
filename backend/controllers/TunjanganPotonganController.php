<?php

namespace backend\controllers;

use backend\models\PotonganDetailSearch;
use backend\models\TunjanganDetailSearch;

class TunjanganPotonganController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $searchTunjanganModel = new TunjanganDetailSearch();
        $dataTunjanganProvider = $searchTunjanganModel->search($this->request->queryParams);
        // dd($dataTunjanganProvider->models);

        $searcPotonganhModel = new PotonganDetailSearch();
        $dataPotonganProvider = $searcPotonganhModel->search($this->request->queryParams);
        // dd($dataPotonganProvider->models);

        return $this->render('index', [
            'searchTunjanganModel' => $searchTunjanganModel,
            'dataTunjanganProvider' => $dataTunjanganProvider,
            'searcPotonganhModel' => $searcPotonganhModel,
            'dataPotonganProvider' => $dataPotonganProvider,
        ]);
    }
}
