<?php
class Notification extends Controller{
    public function __construct(){
        Auth::noAuth();
        parent::__construct();
        Permisos::getPermisos();
    }
    public function notify(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $r = HeaderModel::totmsj();
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
}