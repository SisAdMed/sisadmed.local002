<?php
class Permisos{
  public static function getPermisos(int $id_Menu){
    if (!empty($_SESSION['user_data'])) {
      $intRol = $_SESSION['user_data']['id_rol'];
      $arrPermisos = self::permisosbyPage($intRol);
      $permisos = '';
      $permisosMod = '';
      if (count($arrPermisos) > 0) {
        $permisos = $arrPermisos;
        $permisosMod = isset($arrPermisos[$id_Menu]) ? $arrPermisos[$id_Menu] : "";
      }
      $_SESSION['permisos'] = $permisos;
      $_SESSION['permisosMod'] = $permisosMod;
    }
  }
  private static function permisosbyPage(int $idRol){
    $sql = DB::SQL("SELECT * FROM f0008 p INNER JOIN f0001 pg on p.id_menu = pg.id_menu WHERE p.id_rol = {$idRol}");
    $arrPermisos = [];
    for ($i = 0; $i < count($sql); $i++) {
      $arrPermisos[$sql[$i]['id_menu']] = $sql[$i];
    }
    return $arrPermisos;
  }
  public static function nav(){
    $sql = DB::SQL("SELECT id_menu, nombre_menu, icono_menu FROM f0001 ORDER BY orden_menu");
    foreach ($sql as $item) {
      if (!empty($_SESSION['permisos'][$item['id_menu']]['r'])) {
        echo " <li class='nav-item'>
          <a href='" . base_url . "/" . $item['nombre_menu'] . "' class='nav-link'>
              <i class=' " . $item['icono_menu'] . "'></i>
              " . $item['nombre_menu'] . " <i class='right'></i>
          </a>
      </li>";
      }
    }
  }
  public static function menuMulti(){
    $sql1 = "SELECT id_menu, padre_menu, nombre_menu, page_menu, icono_menu FROM f0001 WHERE `padre_menu` IS NULL";
    $sql2 = "SELECT id_menu, padre_menu, nombre_menu, page_menu, icono_menu FROM f0001 WHERE `padre_menu` != 0";
    $menuPpal = DB::query($sql1);
    $menuHijo = DB::query($sql2);
    foreach ($menuPpal as $menu) {
      foreach ($menuHijo as $sub) {
        if ($menu['page_menu'] == '#') {
          if (!empty($_SESSION['permisos'][$menu['id_menu']]['r'])) {
            echo '<li class="nav-item">
          <a href="#" class="nav-link"> 
              <i class="' . $menu['icono_menu'] . '"></i>
              <p>
              ' . $menu['nombre_menu'] . '
                  <i class="right fas fa-angle-left"></i>
              </p>
          </a>
          <ul class="nav nav-treeview" style="display: none;">
              <li class="nav-item">
                  <a href="' . base_url . "/" . $sub['page_menu'] . '" class="nav-link">
                      <i class="' . $sub['icono_menu'] . '"></i>
                      <p>' . $sub['nombre_menu'] . ' v1</p>
                  </a>
              </li>
          </ul>
      </li>';
          }
        } else {
          if (!empty($_SESSION['permisos'][$menu['id_menu']]['r'])) {
            echo '<li class="nav-item">
          <a href="' . base_url . "/" . $menu['page_menu'] . '" class="nav-link"> 
              <i class="' . $menu['icono_menu'] . '"></i>
              <p>' . $menu['nombre_menu'] . '                 
              </p>
          </a>
          </li>';
          }
        }
      }
    }
  }
  public static function getmenus(){
    //$rows = DB::SQL("SELECT * FROM f0001 ORDER BY padre_menu");
    $rows = DB::SQL("SELECT m1.id_menu, m1.nombre_menu, m1.desc_menu, m1.page_menu, m1.icono_menu, m1.padre_menu FROM `f0001` m1 where m1.padre_menu is null");
    $menus = [];    
    foreach ($rows as $index => $row) {
      if ($row['padre_menu']) {
        $id = $row['padre_menu'];
        $menus['menu_' . $id]['submenu'][] = [
          'id' => $row['id_menu'],
          'titulo' => $row['nombre_menu'],
          'page' => $row['page_menu'],
          'icono' => $row['icono_menu']
        ];
      } else {
        $id = $row['id_menu'];
        $menus['menu_' . $id] = [
          'id' => $row['id_menu'],
          'titulo' => $row['nombre_menu'],
          'page' => $row['page_menu'],
          'icono' => $row['icono_menu']
        ];
      }
    }
    return $menus;
  }
  public static function crear_menu(int $id_padre){
    // En esta línea va la conexión a tu base de datos, es decir, la cadena de conexión.    
    $rows = DB::SQL("SELECT * FROM f0001 where padre_menu = $id_padre ORDER BY orden_menu");
    $html = ''; //Vaciamos la variable menú 
    foreach ($rows as $index => $row) {
      if (!empty($_SESSION['permisos'][$row['id_menu']]['r'])) {
        if ($row['page_menu'] == '#') {
          $html .= '<li class = "nav-item"> <a class="nav-link" href="#" title = "'.$row['desc_menu'].'"> <i  class="nav-icon  ' . $row['icono_menu'] . '"></i><p> ' . $row['nombre_menu'] . '<i class="right fas fa-angle-left"></i></p></a>';
          $html .= '<ul class="nav nav-treeview" style="display: none;">' . self::crear_menu($row['id_menu']) . "</ul>"; //LLamada recursiva para generar todos los niveles del menú   
        } else {
          $html .= '<li class = "nav-item"> <a class="nav-link" title = "'.$row['desc_menu'].'" href="' . base_url . "/" . $row['page_menu'] . '"> <i class="nav-icon ' . $row['icono_menu'] . '"></i> <p>' . $row['nombre_menu'] . '</p></a>';
          //$html .= '<ul>' . self::crear_menu($row['id_menu']) . "</ul>"; //LLamada recursiva para generar todos 
        }
      }
      $html .= "</li>";
    }
    return $html;
  }
  public static function mostrarMenu(){
    $menus = self::getmenus();
    if (!$menus) {
      return 'No existen ningun menu en la base de datos';
    }    
    $html = '';
    foreach ($menus as $menu) {
      if (isset($menu['submenu'])) {
        #/*Title del menu */
        if (!empty($_SESSION['permisos'][$menu['id']]['r'])) {
          if ($menu['page']) {
            $html .= '<li class = "nav-item">
            <a class="nav-link" href="#"><p> <i class="' . $menu['icono'] . '"></i> ' . $menu['titulo'] . '<i class="right fas fa-angle-left"></i></p></a>';
          } else {
            $html .= '<li class = "nav-item"> 
            <a class="nav-link"><i class ="' . $menu['icono'] . '"></i><p> ' . $menu['titulo'] . '</p></a>';
          }
          /**fin title del menu */
          /**Title sub menu*/
          if (is_array($menu['submenu'])) {
            $html .= '<ul class="nav nav-treeview" style="display: none;">';
            foreach ($menu['submenu'] as $submenu) {
              if (!empty($_SESSION['permisos'][$submenu['id']]['r'])) {
                if ($submenu['page']) {
                  $html .= '<li class="nav-item"><a class="nav-link" href="' . base_url . "/" . $submenu['page'] . '"><p><i class = "' . $submenu['icono'] . '"></i>' . $submenu['titulo'] . '</p></a></li>';
                } else {
                  $html .= '<li class="nav-link"><a href="#"><p><i class = "' . $submenu['icono'] . '">' . $submenu['titulo'] . '</i></p></a></li>';
                }
              }
            }
            $html .= '</ul>';
            $html .= '</li>';
          }
        }
        /*end Item de submenu */
      } else {
        /* Menu padre*/
        if (!empty($_SESSION['permisos'][$menu['id']]['r'])) {
          if ($menu['page']) {
            $html .= '<li class="nav-item">
          <a class="nav-link" href="' . base_url . "/" . $menu['page'] . '"><i class="' . $menu['icono'] . '"></i><p> ' . $menu['titulo'] . '</p>
          </a></li>';
          } else {
            $html .= '<li class="nav-item">
          <a class="nav-link"><i class="' . $menu['icono'] . '"></i><p> ' . $menu['titulo'] . '
          </p></a></li>';
          }
        }
        /* end Menu principal */
      }
    }
    return $html;
  }
  public static function read(){
    if (!empty($_SESSION['permisosMod']['r'])) {
      return true;
    }
  }
  public static function create(){
    if (!empty($_SESSION['permisosMod']['c'])) {
      return true;
    }
  }
  public static function updater(){
    if (!empty($_SESSION['permisosMod']['u'])) {
      return true;
    }
  }
  public static function deleter(){
    if (!empty($_SESSION['permisosMod']['d'])) {
      return true;
    }
  }
}
