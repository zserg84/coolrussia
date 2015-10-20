<?php
namespace modules\rbac\commands;

use modules\rbac\models\AuthAssignment;
use modules\rbac\models\AuthItem;
use modules\rbac\models\AuthItemChild;
use Yii;
use yii\console\Controller;
use modules\users\models\User;
use modules\rbac\rules\AuthorRule;
use yii\rbac\Assignment;

class ExportController extends Controller {

    /** @var \yii\rbac\DbManager $auth */
    private $auth;


    public function actionIndex() {
        $this->auth = Yii::$app->authManager;

        $this->auth->removeAll();

        // Rules
        echo "Rules\r\n";
        $authorRule = new AuthorRule();
        $this->auth->add($authorRule);
        echo "---------\r\n\r\n";

        // Items
        $file = Yii::getAlias('@vova07/rbac/data/items.php');
        if (file_exists($file)) {
            $items = require($file);
            echo "Items:\r\n";
            foreach ($items as $name => $data) {
                $type = intval($data['type']);
                $description = (isset($data['description'])) ? $data['description'] : '';
                if ($type === 2) {
                    $permission = $this->auth->createPermission($name);
                    $permission->description = $description;
                    $this->auth->add($permission);
                    echo "add permission '{$name}'\r\n";
                } elseif ($type === 1) {
                    $role = $this->auth->createRole($name);
                    $role->description = $description;
                    $this->auth->add($role);
                    echo "add role '{$name}'\r\n";
                }
            }

            foreach ($items as $name => $data) {
                $type = intval($data['type']);
                if (isset($data['children'])) {
                    $children = $data['children'];
                    if ($type === 2) {
                        $permission = $this->auth->getPermission($name);
                        if (is_null($permission)) continue;
                        foreach ($children as $childName) {
                            if ($child = $this->getChild($childName)) {
                                $this->auth->addChild($permission, $child);
                                echo "add children '{$childName}' to '{$name}'\r\n";
                            }
                        }
                    } elseif ($type === 1) {
                        $role = $this->auth->getRole($name);
                        if (is_null($role)) continue;
                        foreach ($children as $childName) {
                            if ($child = $this->getChild($childName)) {
                                $this->auth->addChild($role, $child);
                                echo "add children '{$childName}' to '{$name}'\r\n";
                            }
                        }
                    }
                }
            }
        } else {
            echo "file {$file} NOT EXIST\r\n";
        }
        echo "---------\r\n\r\n";


        // Assignments
        $file = Yii::getAlias('@vova07/rbac/data/assignments.php');
        if (file_exists($file)) {
            $assignments = require($file);
            echo "Assignments:\r\n";
            foreach ($assignments as $uid => $roles) {
                if ($user = User::find()->where(['id' => $uid])->one()) {
                    echo "user {$uid}:\r\n";
                    foreach ($roles as $name) {
                        if ($role = $this->auth->getRole($name)) {
                            echo " add Role '{$name}'\r\n";
                            $this->auth->assign($role, $uid);
                        }
                    }
                    echo "---\r\n";
                }
            }
        } else {
            echo "file {$file} NOT EXIST\r\n";
        }
    }


    private function getChild($name) {
        $child = $this->auth->getPermission($name);
        if (!$child) {
            $child = $this->auth->getRole($name);
        }
        return $child;
    }

}