<?php
/**
 *
 * User: develop
 * Date: 21.01.2019
 */

namespace somov\requirements;


class ShellExec extends RequirementBase
{

    /**
     * @var
     */
    public $executableFile;

    /**
     * @return string
     */
    public function getName()
    {
        return self::translate("Application {e} ", ['e' => $this->executableFile]);
    }

    /**
     * @return bool
     */
    protected function check()
    {
        return !empty(trim(shell_exec('type -P ' . $this->executableFile)));
    }
}