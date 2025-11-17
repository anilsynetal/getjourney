<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{

    //Get Created By User
    public function created_by_user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    //Get Table data
    static function getTableData()
    {
        $buttons = [];
        $buttons = array(
            ['extend' => 'csv', 'exportOptions' => ['columns' => ':not(.notexport)']],
            ['extend' => 'excel', 'exportOptions' => ['columns' => ':not(.notexport)']],
            ['colvis']
        );
        return array(
            'title' => __('translation.ActivityLog'),
            'module' => __('translation.ActivityLog'),
            'active_page' => __('translation.ActivityList'),
            'index_route' => route('home.activity-logs'),
            'columns' => [
                '#',
                __('translation.ModuleName'),
                __('translation.Action'),
                __('translation.IPAddress'),
                __('translation.UserAgent'),
                __('translation.Browser'),
                __('translation.Url'),
                __('translation.CreatedBy'),
                __('translation.CreatedAt'),
            ],
            'ajax_url' => route('home.get-activity-log-ajax-data'),
            'buttons' => $buttons,
            'js_columns' => [
                ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'orderable' => false, 'searchable' => false],
                ['data' => 'module_name', 'name' => 'module_name'],
                ['data' => 'action', 'name' => 'action'],
                ['data' => 'ip_address', 'name' => 'ip_address'],
                ['data' => 'user_agent', 'name' => 'user_agent'],
                ['data' => 'browser', 'name' => 'browser'],
                ['data' => 'url', 'name' => 'url'],
                ['data' => 'created_by', 'name' => 'created_by_user.name', 'orderable' => false, 'searchable' => false],
                ['data' => 'created_at', 'name' => 'created_at'],
            ],
        );
    }
}
