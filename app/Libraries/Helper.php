<?php

function debug($obj, $type=0)
{
    echo '<pre>';
    if ($type==0) {
        print_r($obj);
    } else {
        var_dump($obj);
    }
    echo '</pre>';
}

function bundle($obj='')
{
    $html = '';
    switch ($obj) {
        case 'datatables':
            $html .= '<link href="'. url('assets/libs/datatables/datatables.min.css') .'" rel="stylesheet" type="text/css" />';
            $html .= '<script src="'. url('assets/libs/datatables/datatables.min.js') .'"></script>';
               
            // $html .= '<link href="'. url('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') .'" rel="stylesheet" type="text/css" />';
            // $html .= '<link href="'. url('assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') .'" rel="stylesheet" type="text/css" />';
            // $html .= '<script src="'. url('assets/libs/datatables.net/js/jquery.dataTables.min.js') .'"></script>';
            // $html .= '<script src="'. url('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') .'"></script>';
            // $html .= '<script src="'. url('assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js') .'"></script>';
            // $html .= '<script src="'. url('assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') .'"></script>';
            // $html .= '<script src="'. url('assets/libs/jszip/jszip.min.js') .'"></script>';
            // $html .= '<script src="'. url('assets/libs/pdfmake/build/pdfmake.min.js') .'"></script>';
            // $html .= '<script src="'. url('assets/libs/pdfmake/build/vfs_fonts.js') .'"></script>';
            // $html .= '<script src="'. url('assets/libs/datatables.net-buttons/js/buttons.html5.min.js') .'"></script>';
            // $html .= '<script src="'. url('assets/libs/datatables.net-buttons/js/buttons.print.min.js') .'"></script>';
            // $html .= '<script src="'. url('assets/libs/datatables.net-buttons/js/buttons.colVis.min.js') .'"></script>';
            break;
        
        case 'jqgrid':
            // $result .= '<link href="'. url('assets/plugins/jqGrid/themes/base/theme.css') .'" rel="stylesheet" type="text/css" />';
            // $result .= '<link href="'. url('assets/plugins/jqGrid/css/ui.jqgrid.min.css') .'" rel="stylesheet" type="text/css" />';
            // $result .= '<link href="'. url('assets/plugins/jqGrid/css/ui.jqgrid.custom.css') .'" rel="stylesheet" type="text/css" />';
            // $result .= '<script src="'. url('assets/plugins/jqGrid/jquery.jqgrid.min.js') .'"></script>';
            // $result .= '<script src="'. url('assets/plugins/jqGrid/jquery.jqgrid.custom.js') .'"></script>';
            
            $html .= '<link href="'. url('assets/libs/jqgrid/css/ui.jqgrid.min.css') .'" rel="stylesheet" type="text/css" />';
            // $html .= '<link href="'. url('assets/libs/jquery-ui-bootstrap/css/custom-theme/jquery-ui-1.10.0.custom.css') .'" rel="stylesheet" type="text/css" />';
            // $html .= '<link href="'. url('assets/libs/jquery-ui-bootstrap/css/custom-theme/jquery.ui.1.10.0.ie.css') .'" rel="stylesheet" type="text/css" />';
            
            $html .= '<link href="'. url('assets/libs/jquery-ui-themes/themes/base/theme.css') .'" rel="stylesheet" type="text/css" />';
            
            $html .= '<script src="'. url('assets/libs/jqgrid/jquery.jqgrid.min.js') .'"></script>';
            break;
        
        case 'fancytree':
            $html .= '<link href="'. url('assets/libs/jquery.fancytree/dist/skin-win8/ui.fancytree.min.css') .'" rel="stylesheet" type="text/css" />';
            $html .= '<script src="'. url('assets/libs/jquery.fancytree/dist/jquery.fancytree-all-deps.min.js') .'"></script>';
            break;
    }
    return $html;
}

function sideMenu()
{
    $menu = new \App\Models\Menu();
    echo $menu->sideMenu();
}

// function getCountNotif()
// {
//     $notif = new \App\Models\Notifikasi();
//     return $notif->getCountNotif();
// }

// function getNotif()
// {
//     $notif = new \App\Models\Notifikasi();
//     echo $notif->getNotif();
// }

// function getVer()
// {
//     $notif = new \App\Models\Version();
//     echo $notif->getVer();
// }

// function getTableColumnName($table)
// {
//     $notif = new \App\Models\TableColumnName();
//     return $notif->getColumn($table);
// }

// function checkAlterTable($kolom, $table, $data) {

//     $query = "SELECT max(ordinal_position) FROM information_schema.columns WHERE table_schema = '".env('DB_SCHEMA', 'public')."' 
//     AND table_name = 'tmp_".$table."'";
//     $result = DB::select($query);
//     $rowresult = (array)$result[0];

//     $queryCekTable = "SELECT count(distinct table_name) ada FROM information_schema.columns WHERE table_schema = '".env('DB_SCHEMA', 'public')."' 
//     AND table_name = 'trn_".$table."_hist'";
//     $result2 = DB::select($queryCekTable);
//     $rowresult2 = (array)$result2[0];

//     // Log::info($kolom);
//     // Log::info($rowresult['max']);
//     // Log::info($table);
//     Log::info("ada table hist -------------->  ". $rowresult2['ada']);
//     $ada = $rowresult2['ada'];

//     if ($kolom != $rowresult['max']) {

//         Log::info("Sistem mendeteksi ada column baru di table!");

//         $array = getTableColumnName('tmp_'.$table);
        
//         foreach((array)$data[0] as $key => $r){
//             if (!in_array($key, $array)) {
//                 Log::info($key . " Key does Not exist! This is Alter Below ");
                
//                 $fieldname = strtoupper($key);
//                 $str = strtolower($key);

//                 $currType =  gettype($key);
//                 $typeColumn = '';

//                 switch($currType){
//                     case "integer" :
//                       $typeColumn = 'NUMERIC(19)';
//                       break;
//                     case "string" :
//                       $typeColumn = 'VARCHAR(255)';
//                       break;
//                     default:
//                       $typeColumn = 'VARCHAR(255)';
//                       break;
//                 }

//                 Log::info("Type data yang di alter : ". $typeColumn);

//                 $sql = 'ALTER TABLE tmp_'.$table.' ADD "'.$fieldname.'" VARCHAR(255)';
//                 DB::statement($sql);

//                 $sql = 'ALTER TABLE trn_'.$table.' ADD "'.$str.'"'. $typeColumn;
//                 DB::statement($sql);

//                 if ($ada > 0 ) {

//                     Log::info($key . " Key does Not exist! This is Alter Table Hist Bro...... : ". $str. " - " . $typeColumn);

//                     $sql = 'ALTER TABLE trn_'.$table.'_hist ADD "'.$str.'"'. $typeColumn;
//                     DB::statement($sql);
//                 }
//             }     
//         }
//     }
// }