<?php
class Error404 extends Controller
{
    public function index()
    {
        $this->views->getview($this, 'index', [
            'page_name' => "Error 404. Página no encontrada",
      ]);
    }
}
