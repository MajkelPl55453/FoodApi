<?php

namespace app\Admin\Config;

class adminCfg {
    private $cfg = [];
    
    public function __construct() {
        $this->cfg = [
            'menu' => [
                'pages' => [
                    'namespace' => 'app\Admin\Controllers\Cms\cPages'
                ]
            ]
        ];
    }
    
    public function getConfig(){
        return $this->getConfig2();
    }
    
    public function getConfig2(){
        return $this->cfg;
    }
}
