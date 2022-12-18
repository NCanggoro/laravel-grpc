<?php

function log_helper($log_name,$controllerName='',$routeName='',$errorLine='',$errorFile='',$errorMessage='')
{
    if($log_name == null) {
        $log_name = 'Eboga-api';
    }
    
    \Illuminate\Support\Facades\DB::table('app_logs')
        ->insert([
            'log_name'=>$log_name,
            'controller_name'=>$controllerName,
            'route_name'=>$routeName,
            'error_line'=>$errorLine,
            'error_file'=>$errorFile,
            'error_message'=>$errorMessage,
            'created_at'=>date('Y-m-d H:i:s'),
            'updated_at'=>date('Y-m-d H:i:s')
        ]);

}
