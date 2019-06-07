<?php

namespace app\models;

use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "task_list".
 *
 * @property int $id
 * @property string $name
 * @property int $id_user
 * @property int $archived
 * @property int $order
 *
 * @property WxUser $user
 * @property TaskListTaskLabelRelation[] $taskListTaskLabelRelations
 * @property TaskLabel[] $taskLabels
 *
 * @property $tasks
 * @property TaskLabel[] $labels
 */
class TaskList extends \yii\db\ActiveRecord
{
    public $labels;
    public $tasks = [
        'unfinished' => [],
        'finished' => [],
    ];

    public function fields()
    {
        return [
            'id',
            'name',
            'archived',
            'order',
            'labels',
            'tasks',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'task_list';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'id_user'], 'required'],
            [['id_user', 'order'], 'integer'],
            [['archived'], 'boolean'],
            [['name'], 'string', 'max' => 255],
            [['id_user'], 'exist', 'skipOnError' => true, 'targetClass' => WxUser::className(), 'targetAttribute' => ['id_user' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'id_user' => 'Id User',
            'archived' => 'Archived',
            'order' => 'Order',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(WxUser::className(), ['id' => 'id_user']);
    }

    /**
     * @return ActiveQuery
     */
    public function getTaskListTaskLabelRelations()
    {
        return $this->hasMany(TaskListTaskLabelRelation::className(), ['id_task_list' => 'id']);
    }

    /**
     * 获取标签
     *
     * @return ActiveQuery
     * @throws InvalidConfigException
     */
    public function getTaskLabels()
    {
        return $this->hasMany(TaskLabel::className(), ['id' => 'id_task_label'])->viaTable('task_list_task_label_relation', ['id_task_list' => 'id']);
    }

    /**
     * 获取任务
     *
     * @param $finished
     * @param null $limit
     * @return ActiveQuery
     */
    public function getTasks($finished, $limit = null)
    {
        return $this->hasMany(Task::className(), ['id_task_list' => 'id'])
            ->where(['finished' => $finished])
            ->limit($limit);
    }

    /**
     * 添加标签
     *
     * @param $user
     * @param $labels
     */
    public function addLabels($user, $labels)
    {
        foreach ($labels as $label) {
            $model = TaskLabel::findOne(['id_user' => $user->id, 'name' => $label]);
            if (!$model) {
                $model = new TaskLabel();
                $model->id_user = $user->id;
                $model->name = $label;
                $model->save();
            }
            $this->link('taskLabels', $model);
        }
    }

    /**
     * 添加附加信息
     */
    public function putExtra()
    {
        $this->labels = $this->taskLabels;
        $this->tasks['unfinished']['count'] = intval($this->getTasks(false)->count());
        $this->tasks['unfinished']['content'] = $this->getTasks(false)->all();
        $this->tasks['finished']['count'] = intval($this->getTasks(true)->count());
        $this->tasks['finished']['content'] = $this->getTasks(true)->all();
    }
}
