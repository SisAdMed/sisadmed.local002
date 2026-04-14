<?php
// Modificado el 19-02-2026 a las 09:37:00, por José Vargas, paa agregar la funcion para guardar los registros de auditoria en las tablas con la fehc ay hora local de Venezuela, ya que se estaba guardando por defecto conla hora del servidor de la nube, daondo diferencias en horas. La funcion es getAuditoria.
function headerAdmin($data = ""){
    $view_header = "Views/Templates/header.php";
    require_once($view_header);
}
function footerAdmin($data = ""){
    $view_footer = "Views/Templates/footer.php";
    require_once($view_footer);
}
function get_favicon(){
    $path = FAVICON;
    $favicon = SITE_FAVICON;
    $type = ''; 
    $href = '';
    $placeholder = '<link rel="shortcut icon" href="%s" type="%s">';
    switch (pathinfo($path . $favicon, PATHINFO_EXTENSION)) {
        case 'ico';
        $type = 'image/vnd.microsoft.icon';
        $href = $path . $favicon;
        break;
        case 'png';
        $type = 'image/png';
        $href = $path . $favicon;
        break;
        case 'gif';
        $type = 'image/gif';
        $href = $path . $favicon;
        break;
        case 'svg';
        $type = 'image/svg+xml';
        $href = $path . $favicon;
        break;
        case 'jpg';
        $type = 'image/jpg';
        $href = $path . $favicon;
        break;
        default:
        return false;
        break;
    }
    return sprintf($placeholder, $href, $type);
}
function debug($data){
    $format = print_r('<pre>');
    $format .= print_r($data);
    $format .= print_r('</pre>');
    return $format;
}
function get_logo(){
    $default_logo = SITE_LOGO;
    $placeholder_image = 'https://via.placeholder.com/150x60';
    if (!is_file(IMAGE_PATH.$default_logo)){
        return $placeholder_image;
    }
    return IMG . $default_logo;
}
function limpiar($datos){
    $datos = trim($datos);
    $datos = htmlentities($datos, ENT_QUOTES, SITE_CHARSET);
    //$datos = utf8_decode($datos);
    $datos = iso8859_1_to_utf8($datos);
    return $datos;
}
function iso8859_1_to_utf8(string $s): string {
    $s .= $s;
    $len = \strlen($s);

    for ($i = $len >> 1, $j = 0; $i < $len; ++$i, ++$j) {
        switch (true) {
            case $s[$i] < "\x80": $s[$j] = $s[$i]; break;
            case $s[$i] < "\xC0": $s[$j] = "\xC2"; $s[++$j] = $s[$i]; break;
            default: $s[$j] = "\xC3"; $s[++$j] = \chr(\ord($s[$i]) - 64); break;
        }
    }

    return substr($s, 0, $j);
}
//Corregir acentos
function acentos_cadena($cadena) {
   $search = explode(",","á,é,í,ó,ú,ñ,Á,É,Í,Ó,Ú,Ñ,Ã¡,Ã©,Ã­,Ã³,Ãº,Ã±,ÃÃ¡,ÃÃ©,ÃÃ­,ÃÃ³,ÃÃº,ÃÃ±,Ã“,Ã ,Ã‰,Ã ,Ãš,â€œ,â€ ,Â¿,ü");
   $replace = explode(",","á,é,í,ó,ú,ñ,Á,É,Í,Ó,Ú,Ñ,á,é,í,ó,ú,ñ,Á,É,Í,Ó,Ú,Ñ,Ó,Á,É,Í,Ú,\",\",¿,&uuml;");
   $cadena= str_replace($search, $replace, $cadena);

   return $cadena;
}
function to_obj($array){
    return json_decode(json_encode($array));
}
function agrupador($agrupa){
    if($agrupa == "S"){
        echo '<option selected value="S">Si</option>
        <option value="N">No</option>';
    }elseif($agrupa == "N"){
        echo '<option selected value="N">No</option>
        <option value="S">Si</option>';
    }else{
        echo '<option value="" selected disabled>Seleccione...</option>
        <option value="S">Si</option>
        <option value="N">No</option>';
    }
}
function status($status){
    if($status == "1"){
        echo '<option selected value="1">Activo</option>
        <option value="0">Inactivo</option>
        <option value="1">Activo</option>
        <option value="9">Por aprobar</option>';
    }elseif($status == "0"){
        echo '<option selected value="0">Inactivo</option>
        <option value="0">Inactivo</option>
        <option value="1">Activo</option>
        <option value="9">Por aprobar</option>';
    } elseif ($status == "9") {
        echo '<option selected value="9">Por aprobar</option>
        <option value="0">Inactivo</option>
        <option value="1">Activo</option>
        <option value="9">Por aprobar</option>';
    }else{
        echo '<option value="" disabled>Seleccione...</option>
        <option value="0">Inactivo</option>
        <option value="1" selected>Activo</option>
        <option value="9">Por aprobar</option>';
    }
}
function statusEntidad($id){
    $p = DB::query("SELECT * FROM f0015");
    echo '<option value="">Seleccione...</option>';
    $p1 = to_obj($p);
    foreach ($p1 as $value) {
        if($id == $value->id_sta ){
            echo '<option selected value="'.$value->id_sta .'">'.$value->nom_sta.'</option>';
        }else{
            echo '<option value="'.$value->id_sta .'">'.$value->nom_sta.'</option>';
        }
    }
}
function formatFecha($fecha){
    return date("d-m-Y", strtotime($fecha));
}
function formatFechaSlash($fecha){
    return date("d/m/Y", strtotime($fecha));
}
function formatFechaHora($fecha){
    return date("d-m-Y H:i:s", strtotime($fecha));
}
function formatFechaYMD($fecha){
    return date("Y-m-d", strtotime($fecha));
}
function formatNumber($monto, $dec){ 
    return number_format(floatval($monto), $dec, ",", ".");
}
function selEmpresa($id){
    $p = DB::query("SELECT * FROM f0011");
    echo '<option value="">Seleccione...</option>';
    $p1 = to_obj($p);
    foreach ($p1 as $value) {
        if($id == $value->id_emp){
            echo '<option selected value="'.$value->id_emp.'">'.$value->nombre_emp.'</option>';
        }else{
            echo '<option value="'.$value->id_emp.'">'.$value->nombre_emp.'</option>';
        }
    }
}
function SelMonedas($id){
    $sm = DB::query("SELECT * FROM f0005");
    echo '<option value="">Seleccione...</option>';
    $m1 = to_obj($sm);
    foreach ($m1 as $value) {
        if($id == $value->id_moneda){
            echo '<option selected value="'.$value->id_moneda.'">'.$value->nombre_moneda.'</option>';
        }else{
            echo '<option value="'.$value->id_moneda.'">'.$value->nombre_moneda.'</option>';
        }
    }
}
function selPais($id){
    $sm = DB::query("SELECT * FROM f0004");
    echo '<option value="">Seleccione...</option>';
    $m1 = to_obj($sm);
    foreach ($m1 as $value) {
        if($id == $value->id_pais){
            echo '<option selected value="'.$value->id_pais.'">'.$value->nombre_pais.'</option>';
        }else{
            echo '<option value="'.$value->id_pais.'">'.$value->nombre_pais.'</option>';
        }
    }
}
function selEstado($id){
    $sm = DB::query("SELECT * FROM f00041");
    echo '<option value="">Seleccione...</option>';
    $m1 = to_obj($sm);
    foreach ($m1 as $value) {
        if($id == $value->id_edo){
            echo '<option selected value="'.$value->id_edo.'">'.$value->nombre_edo.'</option>';
        }else{
            echo '<option value="'.$value->id_edo.'">'.$value->nombre_edo.'</option>';
        }
    }
}
function selCiudad($id){
    $sm = DB::query("SELECT * FROM f00042");
    echo '<option value="">Seleccione...</option>';
    $m1 = to_obj($sm);
    foreach ($m1 as $value) {
        if($id == $value->id_ciudad){
            echo '<option selected value="'.$value->id_ciudad.'">'.$value->nombre_ciudad.'</option>';
        }else{
            echo '<option value="'.$value->id_ciudad.'">'.$value->nombre_ciudad.'</option>';
        }
    }
}
function selCteCble($id){
    $r = DB::query("SELECT * FROM f0010 WHERE status = 1 AND agrupa_cta = 'N'");
    echo '<option value="">Seleccione...</option>';
    $r = to_obj($r);
    foreach ($r as $value) {
        if($id == $value->id_cta){
            echo '<option selected value="'.$value->id_cta.'">'. str_pad($value->cod_cta, 20) . ' - ' . $value->nombre_cta.'</option>';
        }else{
            echo '<option value="'.$value->id_cta.'">'.str_pad($value->cod_cta, 20) . ' - ' . $value->nombre_cta.'</option>';
        }
    }
}
function SelAuxCtb($id){
    $r = DB::query("SELECT * FROM f0009 WHERE status_aux = 1 AND agrupa_aux = 'N'");
    echo '<option value="">Seleccione...</option>';
    $r = to_obj($r);
    foreach ($r as $value) {
        if($id == $value->id_aux){
            echo '<option selected value="'.$value->id_aux.'">'. str_pad($value->cod_aux, 20) . ' - ' . $value->nombre_aux.'</option>';
        }else{
            echo '<option value="'.$value->id_aux.'">'.str_pad($value->cod_aux, 20) . ' - ' . $value->nombre_aux.'</option>';
        }
    }
}
//Tipos de Cuentas Activo, Pasico, Capital, etc.
function seltipoCuenta($status){
    $selected = "";
    $selectedA = "";
    $selectedP = "";
    $selectedC = "";
    $selectedI = "";
    $selectedS = "";
    $selectedE = "";
    $selectedT = "";
    $selectedO = "";

    if($status == "A"){
        $selectedA = "selected";
    }elseif($status == "P"){
        $selectedP = "selected";
    }elseif($status == "C"){
        $selectedC = "selected";
    }elseif($status == "I"){
        $selectedI = "selected";
    }elseif($status == "S"){
        $selectedS = "selected";
    }elseif($status == "E"){
        $selectedE = "selected";
    }elseif($status == "T"){
        $selectedT = "selected";
    }elseif($status == "O"){
        $selectedO = "selected";
    }else{
        $selected = "selected";
    }

    //    <option '.$selectedS.'  value="S">Costo</option>

    echo '<option '.$selected.' disabled value="">Seleccione...</option>
    <option '.$selectedA.'  value="A">Activo</option>
    <option '.$selectedP.'  value="P">Pasivo</option>
    <option '.$selectedC.'  value="C">Capital</option>
    <option '.$selectedI.'  value="I">Ingreso</option>
    <option '.$selectedS.'  value="S">Costo</option>
    <option '.$selectedE.'  value="E">Egreso</option>
    <option '.$selectedT.'  value="T">Contra</option>
    <option '.$selectedO.'  value="O">Percontra</option>';
}
//Tipos de Movimientos de Inventario
function selTipMovINV($tipo){
    $selected = "";
    $selectedE = "";
    $selectedS = "";
    $selectedR = "";
    $selectedT = "";
    $selectedC = "";

    if($tipo == "E"){
        $selectedE = "selected";
    }elseif($tipo == "S"){
        $selectedS = "selected";
    }elseif($tipo == "R"){
        $selectedR = "selected";
    }elseif($tipo == "T"){
        $selectedT = "selected";
    } elseif ($tipo == "C") {
        $selectedC = "selected";
    }else{
        $selected = "selected";
    }

    echo '<option '.$selected.' value="">Seleccione..</option>
    <option '.$selectedE.' value ="E">Entrada</option>
    <option '.$selectedS.' value ="S">Salida</option>
    <option '.$selectedR.' value ="R">Reintegro</option>
    <option '.$selectedT.' value ="T">Transferencia entre Almacenes</option>
    <option '.$selectedC.' value ="C">Transferencia entre Empresas</option>';
}
//Tipos de Movimientos de Salida
function selTipMovSal($id_emp, $id){
    $r = DB::query("SELECT * FROM f4006 WHERE id_emp = {$id_emp} AND tipo_tmoinv = 'S' AND status = 1");
    echo '<option disable value="">Seleccione...</option>';
    $r = to_obj($r);
    foreach ($r as $value) {
        if($id == $value->id_tmoinv){
            echo '<option selected value="'.$value->id_tmoinv.'">'. str_pad($value->cod_tmoinv, 2) . ' - ' . $value->nom__tmoinv.'</option>';
        }else{
            echo '<option value="'.$value->id_tmoinv.'">'.str_pad($value->cod_tmoinv, 2) . ' - ' . $value->nom__tmoinv.'</option>';
        }
    }
}
//Almacen
function selAlmacen( $id){
    $r = DB::query("SELECT * FROM f4002 WHERE status = 1");
    echo '<option disable value="">Seleccione...</option>';
    $r = to_obj($r);
    foreach ($r as $value) {
        if($id == $value->id_alm){
            echo '<option selected value="'.$value->id_alm.'">'. $value->nom_alm.'</option>';
        }else{
            echo '<option value="'.$value->id_alm.'">'.$value->nom_alm.'</option>';
        }
    }
}
//Tipos de Documentos CxC
function selTipDocCxC($tipo){
    $selected = "";
    $selectedF = "";
    $selectedP = "";
    $selectedC = "";
    $selectedB = "";
    $selectedN = "";
    $selectedR = "";
    $selectedD = "";
    $selectedE = "";
    $selectedZ = "";

    if($tipo == "F"){
        $selectedF = "selected";
    }elseif($tipo == "P"){
        $selectedP = "selected";
    }elseif($tipo == "C"){
        $selectedC = "selected";
    }elseif($tipo == "B"){
        $selectedB = "selected";
    }elseif($tipo == "N"){
        $selectedN = "selected";
    }elseif($tipo == "R"){
        $selectedR = "selected";
    }elseif($tipo == "D"){
        $selectedD = "selected";
    }elseif($tipo == "E"){
        $selectedE = "selected";
    }elseif($tipo == "Z"){
        $selectedZ = "selected";
    }

    echo '<option '.$selected.' disabled value="">Seleccione..</option>
    <option '.$selectedF.' value ="F">Factura</option>
    <option '.$selectedP.' value ="P">Presupuesto</option>
    <option '.$selectedC.' value ="C">Nota de Crédito</option>
    <option '.$selectedB.' value ="B">Nota de Débito</option>
    <option '.$selectedN.' value ="N">Nota de Entrega</option>
    <option '.$selectedR.' value ="R">Recepción S.T.</option>
    <option '.$selectedD.' value ="D">Nota de Devolución</option>
    <option '.$selectedE.' value ="E">Entrega S.T.</option>
    <option '.$selectedZ.' value ="Z">Nota de Entrega No Fiscal</option>';
}
//Tipos de Documentos CxP
function selTipDocCxP($tipo){
    $selected = "";
    $selectedM = "";
    $selectedO = "";
    $selectedT = "";
    $selectedX = "";
    $selectedA = "";
    $selectedB = "";
    $selectedV = "";
    $selectedG = "";

    if($tipo == "M"){
        $selectedM = "selected";
    }elseif($tipo == "O"){
        $selectedO = "selected";
    }elseif($tipo == "T"){
        $selectedT = "selected";
    }elseif($tipo == "X"){
        $selectedX = "selected";
    }elseif($tipo == "A"){
        $selectedA = "selected";
    }elseif($tipo == "B"){
        $selectedB = "selected";
    }elseif($tipo == "V"){
        $selectedV = "selected";
    }elseif($tipo == "G"){
        $selectedG = "selected";
    }
    echo '<option '.$selected.' value="">Seleccione..</option>
    <option '.$selectedM.' value ="M">Factura</option>
    <option '.$selectedO.' value ="O">Orden de Compra</option>
    <option '.$selectedT.' value ="T">Nota de Entrega</option>
    <option '.$selectedX.' value ="X">Recepción S.T.</option>
    <option '.$selectedA.' value ="A">Nota de Crédito</option>
    <option '.$selectedB.' value ="B">Nota de Débito</option>
    <option '.$selectedV.' value ="V">Nota de Devolución</option>
    <option '.$selectedG.' value ="G">Entrega S.T.</option>';
}
//Seleccionar origen Nacional/Importado
function SelOrigen($id){
    $selected = "";
    $selectedN = "";
    $selectedI = "";

    if($id == "N"){
        $selectedN = "selected";
    }elseif($id == "I"){
        $selectedI = "selected";
    }

    echo '<option '.$selected.' value="">Seleccione...</option>
        <option '.$selectedN.' value="N">Nacional</option>
        <option '.$selectedI.' value="I">Importado</option>';
}
//Presentacion de Productos
function SelPresentacion($id){
    $r = DB::query("SELECT * FROM f4004 WHERE status = 1");
    echo '<option disable value="">Seleccione...</option>';
    $r = to_obj($r);
    foreach ($r as $value) {
        if($id == $value->id_pre){
            echo '<option selected value="'.$value->id_pre.'">'. $value->nom_pre.'</option>';
        }else{
            echo '<option value="'.$value->id_pre.'">'.$value->nom_pre.'</option>';
        }
    }
}
//Presentacion de Productos
function SelFabricante($id){
    $r = DB::query("SELECT * FROM f4003 WHERE status = 1");
    echo '<option disable value="">Seleccione...</option>';
    $r = to_obj($r);
    foreach ($r as $value) {
        if($id == $value->id_fab){
            echo '<option selected value="'.$value->id_fab.'">'. $value->nom_fab.'</option>';
        }else{
            echo '<option value="'.$value->id_fab.'">'.$value->nom_fab.'</option>';
        }
    }
}
//Grupo de Productos
function SelGrupo($id){
    $r = DB::query("SELECT * FROM f4007 WHERE status = 1 ORDER BY grupo_nombre");
    echo '<option disable value="">Seleccione...</option>';
    $r = to_obj($r);
    foreach ($r as $value) {
        if($id == $value->id_grupo){
            echo '<option selected value="'.$value->id_grupo.'">'. $value->grupo_nombre.'</option>';
        }else{
            echo '<option value="'.$value->id_grupo.'">'.$value->grupo_nombre.'</option>';
        }
    }
}
//Grupo de Productos
function SelSubGrupo($id)
{
    $r = DB::query("SELECT * FROM f40071 WHERE status = 1 ORDER BY sub_grupo_nombre");
    echo '<option disable value="">Seleccione...</option>';
    $r = to_obj($r);
    foreach ($r as $value) {
        if ($id == $value->id) {
            echo '<option selected value="' . $value->id . '">' . $value->sub_grupo_nombre . '</option>';
        } else {
            echo '<option value="' . $value->id . '">' . $value->sub_grupo_nombre . '</option>';
        }
    }
}
//Seleccionar Vendedores
function SelVendedores($id){
    $r = DB::query("SELECT * FROM f0016 WHERE status = 1");
    echo '<option disable value="">Seleccione...</option>';
    $r = to_obj($r);
    foreach ($r as $value) {
        if($id == $value->id_vend){
            echo '<option selected value="'.$value->id_vend.'">'. $value->nom_vend . ' ' .  $value->ape_vend.'</option>';
        }else{
            echo '<option value="'.$value->id_vend.'">'.$value->nom_vend . ' ' .  $value->ape_vend.'</option>';
        }
    }
}
//Obtener ID de Compañia
function getIdCompany($cia){
    return $r = DB::query("SELECT id_emp FROM f0011 WHERE nombre_emp = {$cia}");
}
//Escribir en la console
function write_to_console($data) {
    $console = $data;
    if (is_array($console)){
        $console = implode(',', $console);
    }
    echo "<script>console.log('Console: " . $console . "' );</script>";
}
//Obtener Tipo de Comprobante por defecto
function comprobante_defecto($id, $origen, $tag){
    if($origen == "O"){
        $r = DB::query("SELECT id_tipcom FROM f0013 WHERE id_emp = {$id}");
    }
    //listar_tipos_comprobantes($r->id_tipcom, $tag);
}
function SelTipoComprobantes($id){
    $r = DB::query("SELECT * FROM f0019 WHERE status = 1");
    echo '<option disable value="">Seleccione...</option>';
    $r = to_obj($r);
    foreach ($r as $value) {
        if($id == $value->id_tipcom){
            echo '<option selected value="'.$value->id_tipcom.'">'. $value->nombre_tipcom .'</option>';
        }else{
            echo '<option value="'.$value->id_tipcom.'">'.$value->nombre_tipcom . '</option>';
        }
    }
}
function curl_dolar_bcv_old() {
    //Validar si existe la tasa, si existe no se guarda;
    include ROOT .'/Models/CambiosModel.php';
    //Tasa Oficial
    $data1 = file_get_contents("https://ve.dolarapi.com/v1/dolares/oficial");
    $data2 = json_decode($data1);
    $data = array();
    $id_moneda = 2;
    $USD ='';
    $fecha = Date('Y-m-d');
    //$fecha = substr($data2->fechaActualizacion,0,10);
    $xtasa = $data2->promedio;
    $exist = CambiosModel::exist_rate_change($id_moneda, $fecha);
    if(!$exist){
        try {
        //Guardar Tasa del ultimo día
        $xtasa = 0;
        //$fecha1 = explode(' ', $fecha);
        //$fecha2 = explode('=', $fecha1[6]);
        $fecha3 = $fecha;
        $xtasa = str_replace(',', '.', $xtasa);
        $xtasa = (double)filter_var($xtasa, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

        $data = [
            'fecha_cambio' => $fecha3,
            'id_moneda' => $id_moneda,
            'cambio_compra' => $xtasa,
            'cambio_venta' => $xtasa,
            'create_user' => $_SESSION['id_user']
        ];
        $exist = CambiosModel::exist_rate_change($id_moneda, $fecha3);
        if(!$exist){
            $id = CambiosModel::guardar($data);
        }else{
        }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }finally{

        }
    }else{
        $exist = CambiosModel::exist_rate_change($id_moneda, $fecha);
        if(!$exist){
            $data = [
                'fecha_cambio' => $fecha3,
                'id_moneda' => $id_moneda,
                'cambio_compra' => $xtasa,
                'cambio_venta' => $xtasa,
                'create_user' => $_SESSION['id_user']
            ];
            $id = CambiosModel::guardar($data);
            $fecha3 = $fecha;
            $xtasa = str_replace(',', '.', $xtasa);
            $xtasa = (double)filter_var($xtasa, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $USD = $xtasa;
        }else{
            $fecha3 = $exist[0]['fecha_cambio'];
            $data = [
                'fecha_cambio' => $fecha3,
                'id_moneda' => $id_moneda,
                'cambio_compra' => $xtasa,
                'cambio_venta' => $xtasa,
                'create_user' => $_SESSION['id_user']
            ];
            $id = CambiosModel::actualizar($exist[0]['id_cambio'], $data);
            $USD = number_format($exist[0]['cambio_venta'],8);
            $USD = str_replace('.', ',', $xtasa);
            $fecha = date('d-m-Y', strtotime($exist[0]['fecha_cambio']));
        }
        
    }
    return $retorna = array('fecha' => $fecha, 'bcv' => $USD); //armo el array de retorno
}
function curl_dolar_bcv(){
    include ROOT .'/Models/CambiosModel.php';
    
    $url = 'https://www.bcv.org.ve';
    $active = pingDomain($url);
    //if($active){
        include 'simple_html_dom.php';
        $arrContextOptions = array(
            "ssl" => array(
                "verify_peer" => false,
                "verify_peer_name" => false,
            ),
        );
        $fecha = Date('Y-m-d');
        $tasa = '';
        $id_moneda = 2;
        $headers = @get_headers($url);
        $htmlsite = file_get_html($url, false, stream_context_create($arrContextOptions));
        $element_data = $htmlsite->find('div[id="dolar"]', 0);
        $element_date = $htmlsite->find('span[property="dc:date"]', 0);
        //echo $element_data->tag; 
        //echo $element_data->outertext;
        //echo $element_data->innertext;
        $texto = explode(' ', $element_data->plaintext);
        //Fecha
        $fecha_ori = explode(' ', $element_date->outertext);
        $fecha_ori = $fecha_ori[4];
        $fecha_ori = str_replace("content=", '', $fecha_ori);
        $fecha = substr($fecha_ori, 1, 10);

        //Moneda
        $moneda_ori = trim($texto[29]);
        $tasa_ori = str_replace(',', '.', $texto[85]);
        $id_moneda_ori = CambiosModel::getIdMoneda($moneda_ori);
        $id_moneda = $id_moneda_ori['id_moneda'];
        //Tasa
        $tasa = $tasa_ori;
        //Validar si existe la tasa
        $exist = CambiosModel::exist_rate_change($id_moneda, $fecha);
        $data = array();
        if (!$exist) {
            $data = [
                'fecha_cambio' => $fecha,
                'id_moneda' => $id_moneda,
                'cambio_compra' => $tasa,
                'cambio_venta' => $tasa,
                'create_user' => $_SESSION['id_user']
            ];
            $id = CambiosModel::guardar($data);
        }
        $tasa = number_format($tasa, 8, ",", ".");
        $fecha = formatFecha($fecha);
        return $retorna = array('fecha' => $fecha, 'bcv' => $tasa); //armo el array de retorno
    //}
    
}
function curl_dolar_par() {
    $data = array();
    $id_moneda = 6;
    $USD = 0;
    $fecha = Date('Y-m-d');
    $exist = CambiosModel::exist_rate_change($id_moneda, $fecha);
    if($exist){
        $xfecha = $exist[0]['fecha_cambio'];
        $fecha = date('d-m-Y', strtotime($xfecha));
        $USD = number_format($exist[0]['cambio_venta'],8);
        $USD = str_replace('.', ',', $USD);
    }else{
        $exist = CambiosModel::query("SELECT * FROM f0012 WHERE id_moneda = {$id_moneda} AND fecha_cambio <= '$fecha' ORDER BY fecha_cambio DESC LIMIT 1");
        if($exist){
            $xfecha = $exist[0]['fecha_cambio'];
            $fecha = date('d-m-Y', strtotime($xfecha));
            $USD = number_format($exist[0]['cambio_venta'],8);
            $USD = str_replace('.', ',', $USD);
        }
    }

    /*
    if(!$exist){
        //En sustitución del paralelo
        //$data =  file_get_contents("https://ve.dolarapi.com/v1/dolares/paralelo");
        //Usamos el BitCoin
        $data =  file_get_contents("https://ve.dolarapi.com/v1/dolares/bitcoin");
        $data_json = json_decode($data, false);
        $USD =  $data_json->promedio;
        $fecha = $data_json->fechaActualizacion;
        $pos = strpos($fecha, 'T');
        $fecha = substr($fecha, 0, $pos);

        $xtasa = 0;
        $fecha3 = $fecha;
        $xtasa = str_replace(',', '.', $USD);
        $xtasa = (double)filter_var($xtasa, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

        $data = [
            'fecha_cambio' => $fecha3,
            'id_moneda' => $id_moneda,
            'cambio_compra' => $xtasa,
            'cambio_venta' => $xtasa,
            'create_user' => $_SESSION['id_user']
        ];
        $exist = CambiosModel::exist_rate_change($id_moneda, $fecha3);
        if(!$exist){
            $id = CambiosModel::guardar($data);
        }else{
            $id_cambio = $exist[0]['id_cambio'];
            $id = CambiosModel::actualizar($id_cambio, $data);
            //
            $fecha = date('d-m-Y', strtotime($fecha3));
            $USD = number_format($xtasa,8);
            $USD = str_replace('.', ',', $USD);
            }
    }else{
        $id_cambio = $exist[0]['id_cambio'];
        //En sustitución del paralelo
        //$data =  file_get_contents("https://ve.dolarapi.com/v1/dolares/paralelo");
        //Usamos el BitCoin
        $data =  file_get_contents("https://ve.dolarapi.com/v1/dolares/bitcoin");

        $data_json = json_decode($data, false);
        $USD =  $data_json->promedio;
        $fecha = $data_json->fechaActualizacion;
        $pos = strpos($fecha, 'T');
        $fecha = substr($fecha, 0, $pos);

        $xtasa = 0;
        $fecha3 = $fecha;
        $xtasa = str_replace(',', '.', $USD);
        $xtasa = (double)filter_var($xtasa, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

        $data = [
            'fecha_cambio' => $fecha3,
            'id_moneda' => $id_moneda,
            'cambio_compra' => $xtasa,
            'cambio_venta' => $xtasa,
            'create_user' => isset($_SESSION['id_user'])
        ];
        $id = CambiosModel::actualizar($id_cambio, $data);
        $xfecha = $exist[0]['fecha_cambio'];
        $fecha = date('d-m-Y', strtotime($xfecha));
        $USD = number_format($exist[0]['cambio_venta'],8);
        $USD = str_replace('.', ',', $USD);

    }
    */
    return $retorna = array('fecha' => $fecha, 'bcv' => $USD); //armo el array de retorno
}
//Funcion para quitar format a un campo monto formateado
function convert_string_to_number($num){
    // Eliminar todos los puntos
    $numero_sin_puntos = preg_replace('/\./', '', $num);
    // Reemplazar la coma por un punto
    $numero_formateado = str_replace(',', '.', $numero_sin_puntos);
    return floatval($numero_formateado);
}
//Función para saber la tasa de IVA a una Fecha determianda
function xvatTax($fecha, $vatTax){
     $r = VatTaxModel::ratevatTax($fecha, $vatTax);
     return $r;
     //echo json_encode($r, JSON_UNESCAPED_UNICODE);
}
//Validar empresa de Cia y cambio
function datos_cia($id_emp, $fecha){
    $r = CotizacionesModel::consulta_adic01($id_emp, $fecha);
    return $r;
}
//Validar si un pagina web esta activa
function pingDomain($url){
    $headers = @get_headers($url);
    if ($headers && strpos($headers[0], '200')) {
        return true;
    } else {
        return false;
    }
}
function html($string){
    return htmlspecialchars($string, REPLACE_FLAGS, CHARSET);
}
//Obtener fecha y hora para guardar en los campos de auditoria
function getAuditoria(){
    // 1. Definir la zona horaria deseada
    $timezone = new DateTimeZone('America/Caracas'); // Ejemplo: Venezuela
    // 2. Crear objeto DateTime con la hora actual en esa zona
    $date = new DateTime('now', $timezone);
    // 3. Obtener el timestamp (segundos desde 1970)
    $timestamp = $date->format('Y-m-d H:i:s');
    return $timestamp;
}