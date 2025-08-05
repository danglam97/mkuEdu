<?php

namespace App\Http\Controllers\web\post_new;

use App\Http\Controllers\Controller;
use App\Http\Controllers\web\BaseWebController;

class PostNewController extends BaseWebController
{
    public function postNew (){
        return view('web.post_new.index');
    }
    public function detailPostNew ($slug){
        return view('web.post_new.detail');
    }
}
