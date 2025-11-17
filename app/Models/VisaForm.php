<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisaForm extends Model
{
    protected $table = 'visa_forms';

    //Belongs To Country
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    //Belongs To VisaCategory
    public function visa_category()
    {
        return $this->belongsTo(VisaCategory::class, 'visa_category_id');
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
        $countries = Country::orderBy('country', 'asc')->pluck('country', 'id');
        $visa_categories = VisaCategory::where('status', 1)->orderBy('name', 'asc')->pluck('name', 'id');
        $cities = VisaForm::distinct()->pluck('city')->sort()->values();

        $form_fields = [
            [
                'name' => 'country_id',
                'label' => __('translation.Country'),
                'type' => 'select',
                'id' => 'country_id',
                'class' => '',
                'required' => true,
                'placeholder' => __('translation.SelectCountry'),
                'col_size' => 'col-md-6',
                'options' => $countries
            ],
            [
                'name' => 'city',
                'label' => __('translation.City'),
                'type' => 'text',
                'id' => 'city',
                'class' => '',
                'required' => true,
                'placeholder' => __('translation.EnterCity'),
                'col_size' => 'col-md-6',
                'datalist' => $cities
            ],
            [
                'name' => 'visa_category_id',
                'label' => __('translation.VisaCategory'),
                'type' => 'select',
                'id' => 'visa_category_id',
                'class' => '',
                'required' => true,
                'placeholder' => __('translation.SelectVisaCategory'),
                'col_size' => 'col-md-12',
                'options' => $visa_categories
            ],
            [
                'name' => 'visa_form',
                'label' => __('translation.VisaForm'),
                'type' => 'file',
                'id' => 'visa_form',
                'class' => '',
                'required' => false,
                'placeholder' => __('translation.SelectFile'),
                'col_size' => 'col-md-12',
                'accept' => 'application/pdf'
            ],
            [
                'name' => 'application_form_url',
                'label' => __('translation.ApplicationFormURL'),
                'type' => 'url',
                'id' => 'application_form_url',
                'class' => '',
                'required' => false,
                'placeholder' => 'https://example.com/form',
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
            'title' => __('translation.VisaForm'),
            'module' => __('translation.ManageVisa'),
            'active_page' => __('translation.VisaFormList'),
            'is_add' => auth()->user()->can('manage_visa.visa_forms.add') ? true : false,
            'is_modal' => true,
            'is_modal_large' => true,
            'is_back_button' => false,
            'view_deleted_btn' => false,
            'back_route' => route('manage-visa.visa-forms.index'),
            'index_route' => route('manage-visa.visa-forms.index'),
            'create_route' => route('manage-visa.visa-forms.create'),
            'columns' => [
                '#',
                __('translation.Country'),
                __('translation.City'),
                __('translation.VisaCategory'),
                __('translation.VisaForm'),
                __('translation.ApplicationFormURL'),
                __('translation.CreatedBy'),
                __('translation.Status'),
                __('translation.Action'),
            ],
            'ajax_url' => route('manage-visa.visa-forms.get-ajax-data'),
            'buttons' => $buttons,
            'js_columns' => [
                ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'searchable' => false, 'orderable' => false],
                ['data' => 'country', 'name' => 'country.country'],
                ['data' => 'city', 'name' => 'city'],
                ['data' => 'category', 'name' => 'visa_category.name'],
                ['data' => 'visa_form', 'name' => 'visa_form', 'searchable' => false, 'orderable' => false],
                ['data' => 'application_form_url', 'name' => 'application_form_url'],
                ['data' => 'created_by', 'name' => 'created_by_user.name'],
                ['data' => 'status', 'name' => 'status', 'searchable' => false, 'orderable' => false],
                ['data' => 'action', 'name' => 'action', 'orderable' => false, 'searchable' => false],
            ],
        );
    }
}
