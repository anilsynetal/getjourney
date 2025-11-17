<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'created_by',
        'created_by_ip',
        'updated_by',
        'updated_by_ip',
    ];

    //Check update
    static function checkUpdate()
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => env('SUPER_ADMIN_URL') .  'api/check-version',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode(['product_id' => env('APP_PRODUCT_ID')]),
            CURLOPT_HTTPHEADER => array(
                'Accept: application/json',
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    //Get Backup Table data
    static function getBackupTableData()
    {
        $buttons = [];
        $buttons = array(
            ['extend' => 'csv', 'exportOptions' => ['columns' => ':not(.notexport)']],
            ['extend' => 'excel', 'exportOptions' => ['columns' => ':not(.notexport)']],
            ['colvis']
        );
        return array(
            'title' => __('translation.DatabaseBackup'),
            'module' => __('translation.DatabaseBackup'),
            'active_page' => __('translation.DatabaseBackupList'),
            'is_add' => auth()->user()->can('settings.database-backup.create') ? true : false,
            'is_modal' => false,
            'is_modal_large' => false,
            'is_back_button' => false,
            'view_deleted_btn' => false,
            'back_route' => route('settings.index', ['tab', 'database_backup']),
            'index_route' => route('settings.index', ['tab', 'database_backup']),
            'create_route' => route('settings.create-backup'),
            'columns' => [
                '#',
                __('translation.FileName'),
                __('translation.CreatedAt'),
                __('translation.Action')
            ],
            'ajax_url' => route('settings.get-backup-ajax-data'),
            'buttons' => $buttons,
            'js_columns' => [
                ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'orderable' => false, 'searchable' => false],
                ['data' => 'file_name', 'name' => 'file_name', 'orderable' => false, 'searchable' => false],
                ['data' => 'created_at', 'name' => 'created_at', 'orderable' => false, 'searchable' => false],
                ['data' => 'action', 'name' => 'action', 'orderable' => false, 'searchable' => false],
            ],
        );
    }
}
