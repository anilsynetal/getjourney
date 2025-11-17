<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisaInformation extends Model
{
    //Belongs To Country
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

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
                'name' => 'country_id',
                'label' => __('translation.Country'),
                'type' => 'select',
                'id' => 'country_id',
                'options' => Country::all()->pluck('country', 'id'),
                'class' => '',
                'required' => true,
                'placeholder' => __('translation.SelectCountry'),
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
                'rows' => 8
            ]
        ];
        return $form_fields;
    }

    //Get Table data
    static function getTableData()
    {
        $buttons = [
            ['extend' => 'csv', 'exportOptions' => ['columns' => ':not(.notexport)']],
            ['extend' => 'excel', 'exportOptions' => ['columns' => ':not(.notexport)']],
            ['colvis']
        ];
        return [
            'title' => __('translation.VisaInformation'),
            'module' => __('translation.ManageVisa'),
            'active_page' => __('translation.VisaInformationList'),
            'is_add' => auth()->user()->can('manage_visa.visa_information.add') ? true : false,
            'is_edit' => auth()->user()->can('manage_visa.visa_information.edit') ? true : false,
            'is_delete' => auth()->user()->can('manage_visa.visa_information.delete') ? true : false,
            'is_status' => auth()->user()->can('manage_visa.visa_information.status') ? true : false,
            'is_modal' => true,
            'is_modal_large' => false,
            'is_back_button' => false,
            'view_deleted_btn' => false,
            'back_route' => route('manage-visa.visa-information.index'),
            'index_route' => route('manage-visa.visa-information.index'),
            'create_route' => route('manage-visa.visa-information.create'),
            'columns' => [
                '#',
                __('translation.Country'),
                __('translation.Description'),
                __('translation.CreatedBy'),
                __('translation.Status'),
                __('translation.Action'),
            ],
            'ajax_url' => route('manage-visa.visa-information.get-ajax-data'),
            'buttons' => $buttons,
            'js_columns' => [
                ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'searchable' => false, 'orderable' => false],
                ['data' => 'country', 'name' => 'country.country'],
                ['data' => 'description', 'name' => 'description', 'wrap' => true],
                ['data' => 'created_by', 'name' => 'created_by_user.name'],
                ['data' => 'status', 'name' => 'status', 'searchable' => false, 'orderable' => false],
                ['data' => 'action', 'name' => 'action', 'orderable' => false, 'searchable' => false],
            ],
        ];
    }
}
