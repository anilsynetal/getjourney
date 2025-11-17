<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FAQ extends Model
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
                'name' => 'question',
                'label' => __('translation.Question'),
                'type' => 'text',
                'id' => 'question',
                'class' => '',
                'required' => true,
                'placeholder' => __('translation.EnterQuestion'),
                'col_size' => 'col-md-12'
            ],
            [
                'name' => 'answer',
                'label' => __('translation.Answer'),
                'type' => 'textarea',
                'id' => 'answer',
                'class' => '',
                'required' => true,
                'placeholder' => __('translation.EnterAnswer'),
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
            'title' => __('translation.FAQs'),
            'module' => __('translation.ManageFAQs'),
            'active_page' => __('translation.FAQList'),
            'is_add' => auth()->user()->can('faqs.add') ? true : false,
            'is_modal' => true,
            'is_modal_large' => false,
            'is_back_button' => false,
            'view_deleted_btn' => auth()->user()->can('faqs.restore') ? false : false,
            'back_route' => route('faqs.index'),
            'index_route' => route('faqs.index'),
            'create_route' => route('faqs.create'),
            'columns' => [
                '#',
                __('translation.Question'),
                __('translation.Answer'),
                __('translation.CreatedBy'),
                __('translation.Status'),
                __('translation.Action'),
            ],
            'ajax_url' => route('faqs.get-ajax-data'),
            'buttons' => $buttons,
            'js_columns' => [
                ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'searchable' => false, 'orderable' => false],
                ['data' => 'question', 'name' => 'question', 'wrap' => true],
                ['data' => 'answer', 'name' => 'answer', 'wrap' => true],
                ['data' => 'created_by', 'name' => 'created_by_user.name'],
                ['data' => 'status', 'name' => 'status', 'searchable' => false, 'orderable' => false],
                ['data' => 'action', 'name' => 'action', 'orderable' => false, 'searchable' => false],
            ],
        );
    }
}
