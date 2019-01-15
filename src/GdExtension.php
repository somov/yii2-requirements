<?php
/**
 *
 * User: develop
 * Date: 15.01.2019
 */

namespace somov\requirements;


class GdExtension extends Extension
{
    public $extension = 'gd';

    /**
     * @return bool
     */
    public function check()
    {

        if (!parent::check()) {
            return false;
        }

        $gdInfo = gd_info();
        return !empty($gdInfo['FreeType Support']);

    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return parent::getMessage() . ' ' . $this->translate('with FreeType support');
    }
}