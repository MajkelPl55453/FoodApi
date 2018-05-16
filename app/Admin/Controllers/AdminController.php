<?php

namespace app\Admin\Controllers;

use app\Http\Controllers\Controller;
use Illuminate\Http\Request;
use app\Admin\Config\adminCfg;

class AdminController extends Controller
{
    private $cfg = [];
    private $slug = null;
    
    public function __construct($slug) {
        $this->getConfig();
        $this->slug = $slug;
    }
    
    public function getIndex(){
        $slugs = explode('/', $this->slug);
        if(isset($this->cfg['menu'][$slugs[1]]))
        {
            $nameSpace = $this->cfg['menu'][$slugs[1]]['namespace'];
            $className = $nameSpace.'\\'.ucfirst($slugs[1]).'Controller';
            $controller = new $className;
            return $controller->getIndex();
        }
    }
    
    private function getConfig(){
        $config = new adminCfg();
        $this->cfg = $config->getConfig();
    }
}