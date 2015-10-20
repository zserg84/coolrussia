<?php
/**
 * Created by PhpStorm.
 * User: sz
 * Date: 06.05.15
 * Time: 10:51
 */

namespace common\components;


use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class Menu extends \yii\widgets\Menu
{
    public $submenuTemplate = "\n<ul class='user-submenu'>\n{items}\n</ul>\n";

    public function run()
    {
        parent::run();
        $items = $this->normalizeItems($this->items, $hasActiveChild);
        echo $this->renderSubMenu($items);
    }

    protected function renderItems($items)
    {
        $n = count($items);
        $lines = [];
        foreach ($items as $i => $item) {
            $options = array_merge($this->itemOptions, ArrayHelper::getValue($item, 'options', []));
            $tag = ArrayHelper::remove($options, 'tag', 'li');
            $class = [];
            if ($item['active']) {
                $class[] = $this->activeCssClass;
            }
            if ($i === 0 && $this->firstItemCssClass !== null) {
                $class[] = $this->firstItemCssClass;
            }
            if ($i === $n - 1 && $this->lastItemCssClass !== null) {
                $class[] = $this->lastItemCssClass;
            }
            if (!empty($class)) {
                if (empty($options['class'])) {
                    $options['class'] = implode(' ', $class);
                } else {
                    $options['class'] .= ' ' . implode(' ', $class);
                }
            }

            $menu = $this->renderItem($item);

            if ($tag === false) {
                $lines[] = $menu;
            } else {
                $lines[] = Html::tag($tag, $menu, $options);
            }
        }

        return implode("\n", $lines);
    }

    protected function renderSubMenu($items){
        $submenu = '';
        foreach ($items as $i => $item) {
            if (!empty($item['items']) && isset($item['active']) && $item['active']) {
                $submenuTemplate = ArrayHelper::getValue($item, 'submenuTemplate', $this->submenuTemplate);
                $submenuItems = $this->renderItems($item['items']);
                $submenu = strtr($submenuTemplate, [
                    '{items}' => $submenuItems,
                ]);
            }
        }
        return $submenu;
    }

    protected function isItemActive($item)
    {
        if (!parent::isItemActive($item)){
            if(isset($item['items'])){
                foreach($item['items'] as $it){
                    $active = $this->isItemActive($it);
                    if($active)
                        return true;
                }
            }
        }
        else
            return true;
    }
} 