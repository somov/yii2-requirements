<?php
/**
 *
 * User: develop
 * Date: 15.01.2019
 */

namespace somov\requirements;


class ImagickExtension extends Extension
{
    public $extension = 'imagick';

    /**
     * @return bool
     */
    public function check()
    {

        if (!parent::check()) {
            return false;
        }

        return (in_array('PNG', (new \Imagick())->queryFormats('PNG')));

    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return parent::getMessage() . ' ' . $this->translate('with FreeType support');
    }
}