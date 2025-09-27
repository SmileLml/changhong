<?php
namespace zin;

class password extends wg
{
    /**
     * @var mixed[]
     */
    protected static $defineProps = array(
        'id?: string="password1"',
        'name?: string="password1"',
        'checkStrength?: bool=false',
        'strengthID?: string="passwordStrength"',
        'strengthClass?: string="passwordStrength"'
    );

    public static function getPageJS()
    {
        return file_get_contents(__DIR__ . DS . 'js' . DS . 'v1.js');
    }

    /**
     * @return mixed[]|\zin\node
     */
    protected function build()
    {
        global $app, $config, $lang;
        $app->loadLang('user');
        $jsRoot = $app->getWebRoot() . 'js/';

        list($id, $name, $checkStrength, $strengthID, $strengthClass) = $this->prop(array('id', 'name', 'checkStrength', 'strengthID', 'strengthClass'));

        return $checkStrength ? array
        (
            h::jsCall('$.getLib', 'md5.js', array('root' => $jsRoot)),
            jsVar('window.strengthClass', $strengthClass),
            jsVar('window.passwordStrengthList', $lang->user->passwordStrengthList),
            inputGroup
            (
                input
                (
                    setID($id),
                    on::keyup('checkPassword'),
                    set::type('password'),
                    set::name($name),
                    set::placeholder(zget($lang->user->placeholder->passwordStrength, isset($config->safe->mode) ? $config->safe->mode : '1', ''))
                ),
                span
                (
                    setID($strengthID),
                    setClass("input-group-addon {$strengthClass} hidden")
                )
            )
        ) : input
        (
            set::type('password'),
            set::name($name),
            set($this->getRestProps())
        );
    }
}
