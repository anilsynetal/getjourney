<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TourPackage extends Model
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

    //Belongs To Country
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

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
                'placeholder' => __('translation.EnterName'),
                'col_size' => 'col-md-12'
            ],
            [
                'name' => 'duration_days',
                'label' => __('translation.DurationDays'),
                'type' => 'text',
                'id' => 'duration_days',
                'class' => '',
                'required' => true,
                'placeholder' => __('translation.EnterDurationDays'),
                'col_size' => 'col-md-6',
                'onkeyup' => 'this.value = this.value.replace(/[^0-9]/g, \'\');',
            ],
            [
                'name' => 'country_id',
                'label' => __('translation.Country'),
                'type' => 'select',
                'id' => 'country_id',
                'options' => \App\Models\Country::all()->pluck('country', 'id'),
                'class' => '',
                'required' => false,
                'placeholder' => __('translation.SelectCountry'),
                'col_size' => 'col-md-6'
            ],
            [
                'name' => 'image',
                'label' => __('translation.Image'),
                'type' => 'file',
                'id' => 'image',
                'class' => '',
                'required' => false,
                'placeholder' => __('translation.SelectImage'),
                'col_size' => 'col-md-6'
            ],
            [
                'name' => 'highlights',
                'label' => __('translation.Highlights'),
                'type' => 'textarea',
                'id' => 'highlights',
                'class' => 'tinymce-editor',
                'required' => true,
                'placeholder' => __('translation.EnterHighlights'),
                'col_size' => 'col-md-12',
                'rows' => 3
            ],
            [
                'name' => 'description',
                'label' => __('translation.Description'),
                'type' => 'textarea',
                'id' => 'description',
                'class' => 'tinymce-editor',
                'required' => true,
                'placeholder' => __('translation.EnterDescription'),
                'col_size' => 'col-md-12',
                'rows' => 5
            ],
            [
                'name' => 'itinerary',
                'label' => __('translation.Itinerary'),
                'type' => 'textarea',
                'id' => 'itinerary',
                'class' => 'tinymce-editor',
                'required' => true,
                'placeholder' => __('translation.EnterItinerary'),
                'col_size' => 'col-md-12',
                'rows' => 10
            ],
            [
                'name' => 'inclusions',
                'label' => __('translation.Inclusions'),
                'type' => 'textarea',
                'id' => 'inclusions',
                'class' => 'tinymce-editor',
                'required' => true,
                'placeholder' => __('translation.EnterInclusions'),
                'col_size' => 'col-md-12',
                'rows' => 5
            ],
            [
                'name' => 'exclusions',
                'label' => __('translation.Exclusions'),
                'type' => 'textarea',
                'id' => 'exclusions',
                'class' => 'tinymce-editor',
                'required' => true,
                'placeholder' => __('translation.EnterExclusions'),
                'col_size' => 'col-md-12',
                'rows' => 5
            ],
            [
                'name' => 'pricing',
                'label' => __('translation.Pricing'),
                'type' => 'textarea',
                'id' => 'pricing',
                'class' => 'tinymce-editor',
                'required' => false,
                'placeholder' => __('translation.EnterPricing'),
                'col_size' => 'col-md-12',
                'rows' => 10
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
            'title' => __('translation.ManageTourPackages'),
            'module' => __('translation.Dashboard'),
            'active_page' => __('translation.TourPackageList'),
            'is_add' => auth()->user()->can('tour-packages.add') ? true : false,
            'is_modal' => false,
            'is_modal_large' => false,
            'is_back_button' => false,
            'view_deleted_btn' => auth()->user()->can('tour-packages.restore') ? false : false,
            'back_route' => route('tour-packages.index'),
            'index_route' => route('tour-packages.index'),
            'create_route' => route('tour-packages.create'),
            'columns' => [
                '#',
                __('translation.Name'),
                __('translation.Country'),
                __('translation.DurationDays'),
                __('translation.ShortDescription'),
                __('translation.Image'),
                __('translation.CreatedBy'),
                __('translation.Status'),
                __('translation.Action'),
            ],
            'ajax_url' => route('tour-packages.get-ajax-data'),
            'buttons' => $buttons,
            'js_columns' => [
                ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'searchable' => false, 'orderable' => false],
                ['data' => 'name', 'name' => 'name', 'wrap' => true],
                ['data' => 'country', 'name' => 'country.country'],
                ['data' => 'duration_days', 'name' => 'duration_days'],
                ['data' => 'description', 'name' => 'description', 'wrap' => true],
                ['data' => 'image', 'name' => 'image', 'searchable' => false, 'orderable' => false],
                ['data' => 'created_by', 'name' => 'created_by_user.name'],
                ['data' => 'status', 'name' => 'status', 'searchable' => false, 'orderable' => false],
                ['data' => 'action', 'name' => 'action', 'orderable' => false, 'searchable' => false],
            ],
        );
    }
}
