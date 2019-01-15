<?php
/**
 *
 * User: develop
 * Date: 15.01.2019
 */

namespace somov\requirements;


use yii\base\Arrayable;
use yii\base\ArrayableTrait;
use yii\base\BaseObject;

/**
 * Class RequirementBase
 * @package somov\requirements
 *
 *
 * Интерфейс зависимости
 */
abstract class RequirementBase extends BaseObject implements Arrayable
{
    use ArrayableTrait;

    const TYPE_SUCCESS = 'success';
    const TYPE_ERROR = 'danger';
    const TYPE_WARNING = 'warning';

    /** Обязательная ли
     * @var bool string
     */
    public $mandatory = false;

    /** Внутренний индекс
     * @var string
     */
    public $index;

    /**
     * @var
     */
    public static $translatorConfig = [['\Yii', 't'], 'app'];


    /**
     * @param $message
     * @param array $params
     * @return string
     */
    protected function translate($message, $params = [])
    {
        $p = [];

        list($translator, $category) = self::$translatorConfig;

        if (isset($category)) {
            $p['category'] = $category;
        }

        $p += ['message' => $message, 'params' => $params];

        return call_user_func_array($translator, $p);

    }


    /**
     * @return string
     */
    abstract public function getName();

    /**
     * @return bool
     */
    abstract protected function check();

    /** Сообщение
     * @return string
     */
    public function getMessage()
    {
        return $this->translate('{name} is {mandatory} requirement', [
            'name' => $this->getName(),
            'mandatory' => ($this->mandatory) ? $this->translate('mandatory') : ''
        ]);
    }

    private $_checkResult;

    /**
     * Проверка зависимости
     * @return bool
     */
    private function checkInternal()
    {
        if (isset($this->_checkResult)) {
            return $this->_checkResult;
        }

        $this->_checkResult = $this->check();

        return $this->_checkResult;
    }

    /** Тип
     * @return string
     */
    public function getType()
    {
        return ($this->checkInternal()) ? self::TYPE_SUCCESS
            : (($this->mandatory) ? self::TYPE_ERROR : self::TYPE_WARNING);
    }

    /**
     * @return array
     */
    public function fields()
    {
        return [
            'type',
            'message',
            'name',
            'index'
        ];
    }


}