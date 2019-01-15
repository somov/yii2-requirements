<?php
/**
 *
 * User: develop
 * Date: 21.01.2019
 */

namespace somov\requirements;


class WritablePath extends RequirementBase
{

    /**
     * @var array
     */
    public $directories = [];

    /**
     * @var array
     */
    private $notWritable = [];

    /**
     * @var bool
     */
    public $mandatory = true;

    /**
     * @return string
     */
    public function getName()
    {
        return self::translate("Directory(s) is must writable: {d} ", [
            'd' => implode(";\n", $this->notWritable),
        ]);
    }

    /**
     * @return bool
     */
    protected function check()
    {
        foreach ($this->directories as $directory) {
            $dir = \Yii::getAlias("$directory");
            if (!is_writable($dir)) {
                $this->notWritable[] = dirname($dir);
            }
        }

        return count($this->notWritable) == 0;
    }
}