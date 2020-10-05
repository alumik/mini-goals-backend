<?php

namespace app\controllers\api;

use app\components\api\v1\TaskAction;
use app\components\api\v1\TaskLabelAction;
use app\components\api\v1\TaskListAction;
use app\components\api\v1\UserAction;
use yii\filters\ContentNegotiator;
use yii\web\Controller;
use yii\web\Response;

class V1Controller extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => ContentNegotiator::class,
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'user' => UserAction::class,
            'task-list' => TaskListAction::class,
            'task-label' => TaskLabelAction::class,
            'task' => TaskAction::class,
        ];
    }
}