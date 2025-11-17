<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    //Belongs To User Created By
    public function created_by_user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    //Belongs To User Updated By
    public function updated_by_user()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    //Form Fields
    static function fields($id = null)
    {
        $form_fields = [
            [
                'name' => 'link',
                'label' => __('translation.Link'),
                'type' => 'text',
                'id' => 'link',
                'class' => '',
                'required' => true,
                'placeholder' => __('translation.EnterURL'),
                'col_size' => 'col-md-12'
            ],

            [
                'name' => 'logo',
                'label' => __('translation.Logo'),
                'type' => 'file',
                'id' => 'logo',
                'class' => '',
                'required' => $id ? false : true,
                'placeholder' => __('translation.UploadLogo'),
                'col_size' => 'col-md-12'
            ]
        ];

        return $form_fields;
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
            'title' => __('translation.Clients'),
            'module' => __('translation.Dashboard'),
            'active_page' => __('translation.ClientList'),
            'is_add' => auth()->user()->can('clients.add') ? true : false,
            'is_modal' => true,
            'is_modal_large' => false,
            'is_back_button' => false,
            'view_deleted_btn' => auth()->user()->can('clients.restore') ? false : false,
            'back_route' => route('clients.index'),
            'index_route' => route('clients.index'),
            'create_route' => route('clients.create'),
            'columns' => [
                '#',
                __('translation.Link'),
                __('translation.Logo'),
                __('translation.CreatedBy'),
                __('translation.Status'),
                __('translation.Action'),
            ],
            'ajax_url' => route('clients.get-ajax-data'),
            'buttons' => $buttons,
            'js_columns' => [
                ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'searchable' => false, 'orderable' => false],
                ['data' => 'link', 'name' => 'link', 'wrap' => true],
                ['data' => 'logo', 'name' => 'logo', 'searchable' => false, 'orderable' => false],
                ['data' => 'created_by', 'name' => 'created_by_user.name'],
                ['data' => 'status', 'name' => 'status', 'searchable' => false, 'orderable' => false],
                ['data' => 'action', 'name' => 'action', 'orderable' => false, 'searchable' => false],
            ],
        );
    }
}
