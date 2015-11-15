<?php

namespace julianb90\listview;

use yii\widgets\ListView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * This is just an example.
 */
class ListViewer extends ListView
{
    public $summary = '';

    public $layout = '{items}';

    public $rows = 2;

    /**
     * Renders all data models.
     * @return string the rendering result
     */
    public function renderItems()
    {
        $models = $this->dataProvider->getModels();
        $keys = $this->dataProvider->getKeys();
        $counter = 1;
        $rows[] = Html::beginTag('tr');
        foreach (array_values($models) as $index => $model) {
            if($counter == 0) {
                $rows[] = Html::beginTag('tr');
            }
            $rows[] = $this->renderItem($model, $keys[$index], $index);
            if($counter == $this->rows) {
                $rows[] = Html::endTag('tr');
                $counter = 0;
            }
            $counter++;
        }

        return implode($this->separator, $rows);
    }

    /**
     * Renders a single data model.
     * @param mixed $model the data model to be rendered
     * @param mixed $key the key value associated with the data model
     * @param integer $index the zero-based index of the data model in the model array returned by [[dataProvider]].
     * @return string the rendering result
     */
    public function renderItem($model, $key, $index)
    {
        if ($this->itemView === null) {
            $content = $key;
        } elseif (is_string($this->itemView)) {
            $content = $this->getView()->render($this->itemView, array_merge([
                'model' => $model,
                'key' => $key,
                'index' => $index,
                'widget' => $this,
            ], $this->viewParams));
        } else {
            $content = call_user_func($this->itemView, $model, $key, $index, $this);
        }
        $options = $this->itemOptions;
        $tag = 'td';
        if ($tag !== false) {
            $options['data-key'] = is_array($key) ? json_encode($key, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) : (string) $key;

            return Html::tag($tag, $content, $options);
        } else {
            return $content;
        }
    }
}
