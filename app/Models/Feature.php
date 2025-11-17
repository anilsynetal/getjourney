<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feature extends Model
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
                'name' => 'feature_name',
                'label' => __('translation.FeatureName'),
                'type' => 'text',
                'id' => 'name',
                'class' => '',
                'required' => true,
                'placeholder' => __('translation.FeatureName'),
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
            [
                'name' => 'icon',
                'label' => __('translation.Icon'),
                'type' => 'file',
                'id' => 'icon',
                'class' => '',
                'required' => false,
                'placeholder' => __('translation.UploadIcon'),
                'col_size' => 'col-md-6'
            ],
            [
                'name' => 'image',
                'label' => __('translation.Image'),
                'type' => 'file',
                'id' => 'image',
                'class' => '',
                'required' => false,
                'placeholder' => __('translation.UploadImage'),
                'col_size' => 'col-md-6'
            ],
            [
                'name' => 'is_core_feature',
                'label' => __('translation.IsCoreFeature'),
                'type' => 'checkbox',
                'id' => 'is_core_feature',
                'class' => '',
                'required' => false,
                'placeholder' => '',
                'col_size' => 'col-md-6',
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
            'title' => __('translation.Feature'),
            'module' => __('translation.Dashboard'),
            'active_page' => __('translation.FeatureList'),
            'is_add' => auth()->user()->can('features.add') ? true : false,
            'is_modal' => true,
            'is_modal_large' => false,
            'is_back_button' => false,
            'view_deleted_btn' => auth()->user()->can('features.restore') ? false : false,
            'back_route' => route('features.index'),
            'index_route' => route('features.index'),
            'create_route' => route('features.create'),
            'columns' => [
                '#',
                __('translation.Name'),
                __('translation.Description'),
                __('translation.Icon'),
                __('translation.Image'),
                __('translation.IsCoreFeature'),
                __('translation.CreatedBy'),
                __('translation.Status'),
                __('translation.Action'),
            ],
            'ajax_url' => route('features.get-ajax-data'),
            'buttons' => $buttons,
            'js_columns' => [
                ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'searchable' => false, 'orderable' => false],
                ['data' => 'feature_name', 'name' => 'feature_name', 'wrap' => true],
                ['data' => 'description', 'name' => 'description', 'wrap' => true],
                ['data' => 'icon', 'name' => 'icon', 'searchable' => false, 'orderable' => false],
                ['data' => 'image', 'name' => 'image', 'searchable' => false, 'orderable' => false],
                ['data' => 'is_core_feature', 'name' => 'is_core_feature', 'searchable' => false, 'orderable' => false],
                ['data' => 'created_by', 'name' => 'created_by_user.name'],
                ['data' => 'status', 'name' => 'status', 'searchable' => false, 'orderable' => false],
                ['data' => 'action', 'name' => 'action', 'orderable' => false, 'searchable' => false],
            ],
        );
    }
}
