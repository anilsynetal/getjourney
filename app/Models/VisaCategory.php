<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisaCategory extends Model
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

    //Has Many Visa Details
    public function visaDetails()
    {
        return $this->hasMany(VisaDetail::class, 'visa_category_id');
    }

    //Has Many Visas (if you plan to have visa records)
    // public function visas()
    // {
    //     return $this->hasMany(Visa::class, 'visa_category_id');
    // }

    //Form Fields
    static function fields($id = null)
    {
        $form_fields = [
            [
                'name' => 'name',
                'label' => __('translation.Name'),
                'type' => 'text',
                'id' => 'name',
                'class' => '',
                'required' => true,
                'placeholder' => __('translation.EnterCategoryName'),
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
            'title' => __('translation.VisaCategory'),
            'module' => __('translation.ManageVisa'),
            'active_page' => __('translation.VisaCategoryList'),
            'is_add' => auth()->user()->can('manage_visa.visa_categories.add') ? true : false,
            'is_modal' => true,
            'is_modal_large' => false,
            'is_back_button' => false,
            'view_deleted_btn' => auth()->user()->can('manage_visa.visa_categories.restore') ? false : false,
            'back_route' => route('manage-visa.visa-categories.index'),
            'index_route' => route('manage-visa.visa-categories.index'),
            'create_route' => route('manage-visa.visa-categories.create'),
            'columns' => [
                '#',
                __('translation.Name'),
                __('translation.CreatedBy'),
                __('translation.Status'),
                __('translation.Action'),
            ],
            'ajax_url' => route('manage-visa.visa-categories.get-ajax-data'),
            'buttons' => $buttons,
            'js_columns' => [
                ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'searchable' => false, 'orderable' => false],
                ['data' => 'name', 'name' => 'name', 'wrap' => true],
                ['data' => 'created_by', 'name' => 'created_by_user.name'],
                ['data' => 'status', 'name' => 'status', 'searchable' => false, 'orderable' => false],
                ['data' => 'action', 'name' => 'action', 'orderable' => false, 'searchable' => false],
            ],
        );
    }
}
