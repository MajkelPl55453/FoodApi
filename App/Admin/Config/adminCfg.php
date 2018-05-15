<?php

namespace App\Admin\Config;

class adminCfg {
    private $cfg = [];
    
    public function __construct() {
        $this->cfg = [
            'menu' => [
                'pages' => [
                    'namespace' => 'App\Admin\Controllers\Cms\cPages'
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
