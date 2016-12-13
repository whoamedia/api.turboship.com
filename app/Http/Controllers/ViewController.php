<?php
/**
 * Created by IntelliJ IDEA.
 * User: jamesweston
 * Date: 12/13/16
 * Time: 4:42 PM
 */

namespace App\Http\Controllers;


use Illuminate\Http\Request;

class ViewController
{


    public function __construct()
    {

    }


    public function index (Request $request)
    {
        return view('welcome');
    }

}