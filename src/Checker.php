<?php

namespace somov\requirements;


use yii\base\Arrayable;
use yii\base\BaseObject;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;

/**
 *
 * User: develop
 * Date: 15.01.2019
 *
 *
 * Проверка зависимостей приложение с формированием перевода сообщения зависимости
 *
 */
class Checker extends BaseObject
{
    const I_WRITABLE_PATH = 'writable_path';
    const I_UPLOAD = 'upload';
    const I_PHP_VERSION = 'php_version';
    const I_REFLECTION = 'reflection';
    const I_PCRE = 'pcre';
    const I_SPL = 'spl';
    const I_CTYPE = 'ctype';
    const I_MBSTRNG = 'mbstring';
    const I_OPENSSL = 'openssl';
    const I_INTL = 'intl';
    const I_INTL_VERSION = 'intl_version';
    const I_ICU_VERSION = 'icu_version';
    const I_ICU_DATA_VERSION = 'icu_data_version';
    const I_DB_PDO = 'pdo';
    const I_DB_SQL_LITE = 'db_sql_lite';
    const I_DB_PDO_MYSQL = 'pdo_mysql';
    const I_FILE_INFO = 'file_info';
    const I_GD = 'gd';
    const I_IMAGICK = 'imagick';
    const I_DOM = 'don';
    const I_MEMECACHE = 'memecache';
    const I_EXPOSE = 'expose_php';
    const I_ALLOW_URL_INCLUDE = 'allow_url_include';


    /**
     * Пред настройка массива зависимостей
     * @var array|RequirementBase[]
     */
    public $requirements;


    /** Пред настройка массива расширения  базовых зависимостей
     *
     * @var array
     */
    public $extendBase = [];


    /** /** Пред настройка индексы базовых проверок исключенных из проверки
     * @var array
     */
    public $excluded = [];


    /** Проверять зависимость с получением ссылки на обработанною зависимость
     * @param mixed $requirement
     * @return bool
     */
    public function checkSingleRef(&$requirement)
    {
        $requirement = $this->createFromConfig($requirement);
        return $requirement->getType() === RequirementBase::TYPE_SUCCESS;
    }

    /** Проверять зависимость
     * @param mixed $requirement
     * @return bool
     */
    public function checkSingle($requirement)
    {
        return $this->createFromConfig($requirement)->getType() === RequirementBase::TYPE_SUCCESS;
    }


    /** Проверять зависимость по индексу из базовых завистей. Например . checkByIndex('gd');
     *
     * @param $index
     * @param null $requirement ссылка на обработанною зависимость
     * @return bool
     * @throws InvalidConfigException
     */
    public function checkByIndex($index, &$requirement = null)
    {
        $base = $this->getBaseRequirements();

        if (!isset($base[$index])) {
            throw new InvalidConfigException("Index $index not found in base configuration ");
        }

        $requirement = $base[$index];

        return $this->checkSingleRef($requirement);
    }

    /** Проверить группу зависимостей
     * @param array|null $requirements
     * @return array
     */
    public function checkGroup(array $requirements = null)
    {
        $result = [];

        if (empty($requirements)) {
            $requirements = $this->requirements;
        }

        if (!empty($this->excluded)) {
            $requirements = array_diff_key($requirements, array_flip($this->excluded));
        }

        foreach ($requirements as $index => $requirementConfig) {
            /** @var RequirementBase $requirementConfig */
            $result[$index] = $this->createFromConfig($requirementConfig);
            $result[$index]->index = $index;
        }

        return ArrayHelper::map($result, 'index', function ($r) {
            /** @var $r Arrayable */
            return $r->toArray();
        }, 'type');
    }

    /** Проверить с базовыми настройками
     * @param array $extend расширить базоывые настройки зависимостей
     * @return array
     */
    public function checkWithBase(array $extend = [])
    {
        $requirements = $this->getBaseRequirements(ArrayHelper::merge($this->extendBase, $extend));

        return $this->checkGroup($requirements);
    }

    /** Создает экземпляр зависимости
     * @param mixed $requirement
     * @return object|RequirementBase
     */
    protected function createFromConfig($requirement)
    {
        return ($requirement instanceof RequirementBase) ? $requirement :
            \Yii::createObject($requirement);
    }


    /** маасив базовых настроек
     * @param array $extend
     * @return array
     */
    public function getBaseRequirements($extend = [])
    {
        return ArrayHelper::merge([

            self::I_WRITABLE_PATH => [
                'class' => '\somov\requirements\WritablePath',
                'directories' => [
                    '@runtime'
                ]
            ],

            self::I_UPLOAD => [
                'class' => '\somov\requirements\EvalRequirements',
                'expression' => '$this->checkUploadMaxFileSize(\'2M\')',
                'name' => 'Minimum size file upload is 2M',
                'mandatory' => true,
            ],

            self::I_PHP_VERSION => [
                'class' => '\somov\requirements\EvalRequirements',
                'expression' => "version_compare(PHP_VERSION, '5.4.0', '>=')",
                'name' => 'Php 5.4.0',
                'mandatory' => true,
            ],

            self::I_REFLECTION => [
                'class' => '\somov\requirements\EvalRequirements',
                'expression' => "class_exists('Reflection', false)",
                'name' => 'Reflection extension',
                'mandatory' => true,
            ],

            self::I_PCRE => [
                'class' => '\somov\requirements\Extension',
                'extension' => 'pcre',
                'mandatory' => true,
            ],

            self::I_SPL => [
                'class' => '\somov\requirements\Extension',
                'extension' => 'SPL',
                'mandatory' => true,
            ],

            self::I_CTYPE => [
                'class' => '\somov\requirements\Extension',
                'extension' => 'ctype',
                'mandatory' => true,
            ],


            self::I_MBSTRNG => [
                'class' => '\somov\requirements\Extension',
                'extension' => 'mbstring',
                'mandatory' => true,
            ],

            self::I_OPENSSL => [
                'class' => '\somov\requirements\Extension',
                'extension' => 'openssl',
            ],


            self::I_INTL => [
                'class' => '\somov\requirements\Extension',
                'extension' => 'intl',
            ],


            self::I_INTL_VERSION => [
                'class' => '\somov\requirements\EvalRequirements',
                'expression' => '$this->checkPhpExtensionVersion(\'intl\', \'1.0.2\', \'>=\')',
                'name' => 'PHP Intl extension 1.0.2 or higher',
            ],

            self::I_ICU_VERSION => [
                'class' => '\somov\requirements\EvalRequirements',
                'expression' => "defined('INTL_ICU_VERSION') && version_compare(INTL_ICU_VERSION, '49', '>=')",
                'name' => 'ICU 49.0 or higher',
            ],

            self::I_ICU_DATA_VERSION => [
                'class' => '\somov\requirements\EvalRequirements',
                'expression' => "defined('INTL_ICU_DATA_VERSION') && version_compare(INTL_ICU_DATA_VERSION, '49.1', '>=')",
                'name' => 'ICU Data 49.1 or higher',
            ],

            self::I_DB_PDO => [
                'class' => '\somov\requirements\Extension',
                'extension' => 'pdo',
                'mandatory' => true,
            ],


            self::I_DB_SQL_LITE => [
                'class' => '\somov\requirements\Extension',
                'extension' => 'pdo_sqlite',
            ],

            self::I_DB_PDO_MYSQL => [
                'class' => '\somov\requirements\Extension',
                'extension' => 'pdo_mysql',
                'mandatory' => true,
            ],


            self::I_FILE_INFO => [
                'class' => '\somov\requirements\Extension',
                'extension' => 'fileinfo',
            ],

            self::I_DOM => [
                'class' => '\somov\requirements\Extension',
                'extension' => 'dom',
            ],

            self::I_MEMECACHE => [
                'class' => '\somov\requirements\Extension',
                'extension' => ['memecache', 'memecached']
            ],

            self::I_GD => '\somov\requirements\GdExtension',

            self::I_IMAGICK => '\somov\requirements\ImagickExtension',

            self::I_EXPOSE => [
                'class' => '\somov\requirements\Ini',
                'optionName' => 'expose_php'
            ],

            self::I_ALLOW_URL_INCLUDE => [
                'class' => '\somov\requirements\Ini',
                'optionName' => 'allow_url_include'
            ],

        ], $extend);
    }


}