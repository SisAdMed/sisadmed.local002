<?php
class Alerta extends Controller
{
    public function index()
    {
        $this->views->getView($this, "index", [
            'function_js' => "Logout.js"
        ]);        
    }
    public function Logout()
    {
        Auth::logout();
    }
}