<?php

namespace modules\direction\models;

use Yii;

/**
 * This is the model class for table "direction".
 *
 * @property integer $id
 * @property integer $parent_id
 * @property string $name
 *
 * @property ContractPerformerHasDirection[] $contractPerformerHasDirections
 * @property Contract[] $contracts
 * @property Direction $parent
 * @property Direction[] $directions
 * @property OrderHasDirection[] $orderHasDirections
 * @property Order[] $orders
 */
class Direction extends \yii\db\ActiveRecord
{
    protected $_children;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'direction';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id'], 'integer'],
            [['name'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
//            'id' => Yii::t('m_direction', 'ID'),
//            'parent_id' => Yii::t('m_direction', 'Parent ID'),
//            'name' => Yii::t('m_direction', 'Name'),
        ];
    }

    /**
     * $_children getter.
     *
     * @return null|array|]yii\db\ActiveRecord[] Direction children
     */
    public function getChildren()
    {
        return $this->_children;
    }

    /**
     * $_children setter.
     *
     * @param array|\yii\db\ActiveRecord[] $value Direction children
     */
    public function setChildren($value)
    {
        $this->_children = $value;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContractPerformerHasDirections()
    {
        return $this->hasMany(ContractPerformerHasDirection::className(), ['direction_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContracts()
    {
        return $this->hasMany(Contract::className(), ['id' => 'contract_id'])->viaTable('contract_performer_has_direction', ['direction_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(Direction::className(), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDirections()
    {
        return $this->hasMany(Direction::className(), ['parent_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderHasDirections()
    {
        return $this->hasMany(OrderHasDirection::className(), ['direction_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['id' => 'order_id'])->viaTable('order_has_direction', ['direction_id' => 'id']);
    }

    public static function getTree()
    {
        $models = self::find()->orderBy(['parent_id' => 'ASC', 'name' => 'ASC'])->all();

        if ($models !== null) {
            $models = self::buildTree($models);
        }

        return $models;
    }

    protected static function buildTree(&$data, $rootID = 0)
    {
        $tree = [];

        foreach ($data as $id => $node) {
            if ($node->parent_id == $rootID) {
                unset($data[$id]);
                $node->children = self::buildTree($data, $node->id);
                $tree[] = $node;
            }
        }

        return $tree;
    }

    /*
     * Возвращает массив с id направления текущего и всех дочерних
     * */
    public function getAllChildrenArray($itogArr = []){
        $children = self::find()->where(['parent_id' => $this->id])->all();
        $itogArr[] = $this->id;
        foreach($children as $child){
            $itogArr = $child->getAllChildrenArray($itogArr);
        }
        return $itogArr;
    }

    public function getDirectionlangByLang($langId=null)
    {
        return Directionlang::getDirectionlangByDirectionAndLang($this->id, $langId)->one();
    }


    public function getName(){
        $dl = $this->getDirectionlangByLang();
        return $dl ? $dl->translate : $this->name;
    }
}
