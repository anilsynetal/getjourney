<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InternationalHelpAddress extends Model
{
    protected $table = 'international_help_addresses';

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
        $countries = Country::orderBy('country', 'asc')->pluck('country', 'id');

        $form_fields = [
            [
                'name' => 'country_id',
                'label' => __('translation.Country'),
                'type' => 'select',
                'id' => 'country_id',
                'class' => '',
                'required' => true,
                'placeholder' => __('translation.SelectCountry'),
                'col_size' => 'col-md-12',
                'options' => $countries
            ],
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
                'name' => 'link',
                'label' => __('translation.Link'),
                'type' => 'text',
                'id' => 'link',
                'class' => '',
                'required' => true,
                'placeholder' => __('translation.EnterLink'),
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
            'title' => __('translation.InternationalHelpAddress'),
            'module' => __('translation.ManageVisa'),
            'active_page' => __('translation.InternationalHelpAddressList'),
            'is_add' => true,
            'is_modal' => true,
            'is_modal_large' => false,
            'is_back_button' => false,
            'view_deleted_btn' => false,
            'back_route' => route('manage-visa.international-help-addresses.index'),
            'index_route' => route('manage-visa.international-help-addresses.index'),
            'create_route' => route('manage-visa.international-help-addresses.create'),
            'columns' => [
                '#',
                __('translation.Country'),
                __('translation.Title'),
                __('translation.Link'),
                __('translation.CreatedBy'),
                __('translation.Status'),
                __('translation.Action'),
            ],
            'ajax_url' => route('manage-visa.international-help-addresses.get-ajax-data'),
            'buttons' => $buttons,
            'js_columns' => [
                ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'searchable' => false, 'orderable' => false],
                ['data' => 'country_name', 'name' => 'country.name'],
                ['data' => 'title', 'name' => 'title', 'wrap' => true],
                ['data' => 'link', 'name' => 'link', 'wrap' => true],
                ['data' => 'created_by', 'name' => 'created_by_user.name'],
                ['data' => 'status', 'name' => 'status', 'searchable' => false, 'orderable' => false],
                ['data' => 'action', 'name' => 'action', 'orderable' => false, 'searchable' => false],
            ],
        );
    }
}
