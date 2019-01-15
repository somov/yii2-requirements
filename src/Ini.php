<?php
/**
 *
 * User: develop
 * Date: 15.01.2019
 */

namespace somov\requirements;


class Ini extends RequirementBase
{

    public $optionName;

    public $optionValue = '0';

    /**
     * @return string
     */
    public function getName()
    {
        return $this->translate('{options} should be {state} at php.ini', [
            'options' => $this->optionName,
            'state' => $this->optionValue === '1' ? $this->translate('enabled') : $this->translate('disabled')
        ]);
    }

    /**
     * @return bool
     */
    protected function check()
   {

        $value = ini_get($this->optionName);
        if (empty($value)) {
            return $this->optionValue === '0';
        }

        return (strtolower($value) === $this->optionValue);
    }
}