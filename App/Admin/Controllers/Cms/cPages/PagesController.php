<?php

namespace App\Admin\Controllers\Cms\cPages;

use Illuminate\Http\Request;

class PagesController extends \App\Admin\Controllers\AdminController
{
    public function __construct() {
        
    }
    
    public function getIndex(){
        return view('Admin.Cms.Pages.index');
    }
}
