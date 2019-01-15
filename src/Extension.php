<?php
/**
 *
 * User: develop
 * Date: 15.01.2019
 */

namespace somov\requirements;


class Extension extends RequirementBase
{
    /**
     * @var string|array
     */
    public $extension;


    /**
     * @return array
     */
    public function getExtensions()
    {
        return (array)$this->extension;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->translate('Extension PHP {extension}', ['extension' => implode(',', $this->getExtensions())]);
    }

    /**
     * @return bool
     */
    public function check()
    {
        foreach ($this->getExtensions() as $extension) {
            if (extension_loaded($extension)) {
                return true;
            }
        }
        return false;
    }

}