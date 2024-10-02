<?php
/* @var $content string */

use yii\bootstrap4\Breadcrumbs;

?>



<div class="content-wrapper  p-3" style="border-top-left-radius: 20px; border-top-right-radius: 20px; background-color: #f8f9fb !important; ">
    <!-- Content Header (Page header) -->
    <?php

    use common\widgets\Alert;

    echo  Alert::widget();
    ?>


    <style>
        @media screen and (max-width: 1024px) {
            .title-costume {
                font-size: 23px;
            }

        }
    </style>


    <div class="content-header">
        <div class="container-fluid">
            <div class="row justify-content-center  align-items-center">
                <div class="col-12">
                    <h2 class="m-0 title-costume" style="font-weight: bold">
                        <?php
                        if (!is_null($this->title)) {
                            echo \yii\helpers\Html::encode($this->title);
                        } else {
                            echo \yii\helpers\Inflector::camelize($this->context->id);
                        }
                        ?>
                    </h2>
                    <p class="breadcrumb-title">
                        <?php
                        echo Breadcrumbs::widget([
                            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                            'options' => [
                                'class' => 'breadcrumb  '
                            ]
                        ]);
                        ?>

                    </p>
                </div><!-- /.col -->

            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class=" content">

        <?= $content ?><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>