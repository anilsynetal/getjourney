<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class About extends Model
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

            [
                'name' => 'established_year',
                'label' => __('translation.EstablishedYear'),
                'type' => 'text',
                'id' => 'established_year',
                'class' => '',
                'required' => true,
                'placeholder' => __('translation.EnterEstablishedYear'),
                'col_size' => 'col-md-6'
            ],
            [
                'name' => 'highlighted_text1',
                'label' => __('translation.HighlightedText1'),
                'type' => 'text',
                'id' => 'highlighted_text1',
                'class' => '',
                'required' => true,
                'placeholder' => __('translation.EnterHighlightedText1'),
                'col_size' => 'col-md-6'
            ],
            [
                'name' => 'highlighted_text2',
                'label' => __('translation.HighlightedText2'),
                'type' => 'text',
                'id' => 'highlighted_text2',
                'class' => '',
                'required' => true,
                'placeholder' => __('translation.EnterHighlightedText2'),
                'col_size' => 'col-md-6'
            ],
            [
                'name' => 'highlighted_text3',
                'label' => __('translation.HighlightedText3'),
                'type' => 'text',
                'id' => 'highlighted_text3',
                'class' => '',
                'required' => true,
                'placeholder' => __('translation.EnterHighlightedText3'),
                'col_size' => 'col-md-6'
            ],
            [
                'name' => 'image1',
                'label' => __('translation.Image1'),
                'type' => 'file',
                'id' => 'image1',
                'class' => '',
                'required' => ($id ? false : true),
                'placeholder' => __('translation.UploadImage'),
                'col_size' => 'col-md-6'
            ],
            [
                'name' => 'image2',
                'label' => __('translation.Image2'),
                'type' => 'file',
                'id' => 'image2',
                'class' => '',
                'required' => ($id ? false : true),
                'placeholder' => __('translation.UploadImage'),
                'col_size' => 'col-md-6'
            ],
        ];

        return $form_fields;
    }

    //Get Table data
    static function getTableData($count)
    {
        $buttons = [];
        $buttons = array(
            ['extend' => 'csv', 'exportOptions' => ['columns' => ':not(.notexport)']],
            ['extend' => 'excel', 'exportOptions' => ['columns' => ':not(.notexport)']],
            ['colvis']
        );
        return array(
            'title' => __('translation.AboutUs'),
            'module' => __('translation.AccountSettings'),
            'active_page' => __('translation.AboutUs'),
            'is_add' => auth()->user()->can('settings.about-us.add') && $count == 0 ? true : false,
            'is_modal' => true,
            'is_modal_large' => false,
            'is_back_button' => false,
            'view_deleted_btn' => auth()->user()->can('settings.about-us.restore') ? false : false,
            'back_route' => route('settings.about-us.index'),
            'index_route' => route('settings.about-us.index'),
            'create_route' => route('settings.about-us.create'),
            'columns' => [
                '#',
                __('translation.Title'),
                __('translation.Description'),
                __('translation.Image1'),
                __('translation.Image2'),
                __('translation.EstablishedYear'),
                __('translation.HighlightedText1'),
                __('translation.HighlightedText2'),
                __('translation.HighlightedText3'),
                __('translation.CreatedBy'),
                __('translation.Status'),
                __('translation.Action'),
            ],
            'ajax_url' => route('settings.about-us.get-ajax-data'),
            'buttons' => $buttons,
            'js_columns' => [
                ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'searchable' => false, 'orderable' => false],
                ['data' => 'title', 'name' => 'title'],
                ['data' => 'description', 'name' => 'description', 'wrap' => true],
                ['data' => 'image1', 'name' => 'image1', 'searchable' => false, 'orderable' => false],
                ['data' => 'image2', 'name' => 'image2', 'searchable' => false, 'orderable' => false],
                ['data' => 'established_year', 'name' => 'established_year'],
                ['data' => 'highlighted_text1', 'name' => 'highlighted_text1'],
                ['data' => 'highlighted_text2', 'name' => 'highlighted_text2'],
                ['data' => 'highlighted_text3', 'name' => 'highlighted_text3'],
                ['data' => 'created_by', 'name' => 'created_by_user.name'],
                ['data' => 'status', 'name' => 'status', 'searchable' => false, 'orderable' => false],
                ['data' => 'action', 'name' => 'action', 'orderable' => false, 'searchable' => false],
            ],
        );
    }
}
