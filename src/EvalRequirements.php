<?php
/**
 *
 * User: develop
 * Date: 15.01.2019
 */

namespace somov\requirements;

require_once \Yii::getAlias("@yii/requirements/YiiRequirementChecker.php");

use somov\common\traits\ContainerCompositions;

/**
 * @property \YiiRequirementChecker yiiRequirement
 */
class EvalRequirements extends RequirementBase
{

    use ContainerCompositions;

    /**
     * @var string
     */
    public $expression;

    /**
     * @var string
     */
    public $name;


    /**
     * @var string
     */
    public $addMessage;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    public function getMessage()
    {
        return $this->translate('{name} is required. {addMessage}', [
            'name' => $this->name,
            'addMessage' => $this->addMessage
        ]);
    }

    /**
     * @return bool
     */
    protected function check()
    {
        return $this->evaluateExpression($this->expression);
    }


    /**
     * @return \YiiRequirementChecker
     */
    protected function getYiiRequirement()
    {
        return $this->getComposition(\YiiRequirementChecker::class);
    }

    /**
     * Evaluates a PHP expression under the context of this class.
     * @param string $expression a PHP expression to be evaluated.
     * @return mixed the expression result.
     */
    function evaluateExpression($expression)
    {
        return eval('return ' . $expression . ';');
    }

    /**
     * Checks if the given PHP extension is available and its version matches the given one.
     * @param string $extensionName PHP extension name.
     * @param string $version required PHP extension version.
     * @param string $compare comparison operator, by default '>='
     * @return bool if PHP extension version matches.
     */
    function checkPhpExtensionVersion($extensionName, $version, $compare = '>=')
    {
        return $this->yiiRequirement->checkPhpExtensionVersion($extensionName, $version, $compare);
    }


    /**
     * Compare byte sizes of values given in the verbose representation,
     * like '5M', '15K' etc.
     * @param string $a first value.
     * @param string $b second value.
     * @param string $compare comparison operator, by default '>='.
     * @return bool comparison result.
     */
    function compareByteSize($a, $b, $compare = '>=')
    {
        return $this->yiiRequirement->compareByteSize($a, $b, $compare);
    }

    /**
     * Gets the size in bytes from verbose size representation.
     * For example: '5K' => 5*1024
     * @param string $verboseSize verbose size representation.
     * @return int actual size in bytes.
     */
    function getByteSize($verboseSize)
    {
        return $this->yiiRequirement->getByteSize($verboseSize);
    }

    /**
     * Checks if upload max file size matches the given range.
     * @param string|null $min verbose file size minimum required value, pass null to skip minimum check.
     * @param string|null $max verbose file size maximum required value, pass null to skip maximum check.
     * @return bool success.
     */
    function checkUploadMaxFileSize($min = null, $max = null)
    {
        return $this->yiiRequirement->checkUploadMaxFileSize($min, $max);
    }


}