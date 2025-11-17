<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Benefit extends Model
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
                'name' => 'title',
                'label' => __('translation.Title'),
                'type' => 'text',
                'id' => 'title',
                'class' => '',
                'required' => true,
                'placeholder' => __('translation.EnterTitle'),
                'col_size' => 'col-md-12'
            ],

            [
                'name' => 'description',
                'label' => __('translation.Description'),
                'type' => 'textarea',
                'id' => 'description',
                'class' => '',
                'required' => true,
                'placeholder' => __('translation.EnterDescription'),
                'col_size' => 'col-md-12',
                'rows' => 5
            ],
            // [
            //     'name' => 'image',
            //     'label' => __('translation.Image'),
            //     'type' => 'file',
            //     'id' => 'image',
            //     'class' => '',
            //     'required' => false,
            //     'placeholder' => __('translation.SelectImage'),
            //     'col_size' => 'col-md-6'
            // ],
            [
                'name' => 'icon',
                'label' => __('translation.Icon'),
                'type' => 'text',
                'id' => 'icon',
                'class' => '',
                'required' => false,
                'placeholder' => 'eg. fa fa-cog',
                'col_size' => 'col-md-6'
            ],
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
            'title' => __('translation.WhyChooseUs'),
            'module' => __('translation.Dashboard'),
            'active_page' => __('translation.WhyChooseList'),
            'is_add' => auth()->user()->can('benefits.add') ? true : false,
            'is_modal' => true,
            'is_modal_large' => false,
            'is_back_button' => false,
            'view_deleted_btn' => auth()->user()->can('benefits.restore') ? false : false,
            'back_route' => route('benefits.index'),
            'index_route' => route('benefits.index'),
            'create_route' => route('benefits.create'),
            'columns' => [
                '#',
                __('translation.Title'),
                __('translation.Description'),
                __('translation.Icon'),
                // __('translation.Image'),
                __('translation.CreatedBy'),
                __('translation.Status'),
                __('translation.Action'),
            ],
            'ajax_url' => route('benefits.get-ajax-data'),
            'buttons' => $buttons,
            'js_columns' => [
                ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'searchable' => false, 'orderable' => false],
                ['data' => 'title', 'name' => 'title', 'wrap' => true],
                ['data' => 'description', 'name' => 'description', 'wrap' => true],
                ['data' => 'icon', 'name' => 'icon', 'searchable' => false, 'orderable' => false],
                // ['data' => 'image', 'name' => 'image', 'searchable' => false, 'orderable' => false],
                ['data' => 'created_by', 'name' => 'created_by_user.name'],
                ['data' => 'status', 'name' => 'status', 'searchable' => false, 'orderable' => false],
                ['data' => 'action', 'name' => 'action', 'orderable' => false, 'searchable' => false],
            ],
        );
    }
}
