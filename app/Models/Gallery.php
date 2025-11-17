<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
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
    static function fields($id = null, $type)
    {
        $form_fields = [
            [
                'name' => 'title',
                'label' => __('translation.Title'),
                'type' => 'text',
                'id' => 'title',
                'class' => '',
                'required' => false,
                'placeholder' => __('translation.EnterTitle'),
                'col_size' => 'col-md-12'
            ],
            [
                'name' => 'description',
                'label' => __('translation.Description'),
                'type' => 'textarea',
                'id' => 'description',
                'class' => '',
                'required' => false,
                'placeholder' => __('translation.EnterDescription'),
                'col_size' => 'col-md-12'
            ]
        ];

        if ($type == 'photo') {
            $file =     [
                'name' => 'file',
                'label' => __('translation.Image'),
                'type' => 'file',
                'id' => 'file',
                'class' => '',
                'required' => ($id ? false : true),
                'placeholder' => __('translation.SelectImage'),
                'col_size' => 'col-md-6'
            ];
        }
        if ($type == 'video') {
            $file =     [
                'name' => 'file',
                'label' => __('translation.VideoIframe'),
                'type' => 'textarea',
                'id' => 'file',
                'class' => '',
                'required' => ($id ? false : true),
                'placeholder' => __('translation.EnterVideoIframe'),
                'col_size' => 'col-md-12',
                'rows' => 5
            ];
        }
        $form_fields = array_merge($form_fields, [$file]);
        return $form_fields;
    }

    //Get Table data photo
    static function getTableDataPhoto()
    {
        $buttons = [];
        $buttons = array(
            ['extend' => 'csv', 'exportOptions' => ['columns' => ':not(.notexport)']],
            ['extend' => 'excel', 'exportOptions' => ['columns' => ':not(.notexport)']],
            ['colvis']
        );
        return array(
            'title' => __('translation.Photo'),
            'module' =>  __('translation.ManageGallery'),
            'active_page' =>  __('translation.PhotoList'),
            'is_add' => auth()->user()->can('photos.add') ? true : false,
            'is_modal' => true,
            'is_modal_large' => false,
            'is_back_button' => false,
            'view_deleted_btn' => auth()->user()->can('photos.restore') ? false : false,
            'back_route' => route('photos.index'),
            'index_route' => route('photos.index'),
            'create_route' => route('photos.create'),
            'columns' => [
                '#',
                __('translation.Title'),
                __('translation.Description'),
                __('translation.Image'),
                __('translation.CreatedBy'),
                __('translation.Status'),
                __('translation.Action'),
            ],
            'ajax_url' => route('photos.get-ajax-data'),
            'buttons' => $buttons,
            'js_columns' => [
                ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'searchable' => false, 'orderable' => false],
                ['data' => 'title', 'name' => 'title', 'wrap' => true],
                ['data' => 'description', 'name' => 'description', 'wrap' => true],
                ['data' => 'file', 'name' => 'file', 'searchable' => false, 'orderable' => false],
                ['data' => 'created_by', 'name' => 'created_by_user.name'],
                ['data' => 'status', 'name' => 'status', 'searchable' => false, 'orderable' => false],
                ['data' => 'action', 'name' => 'action', 'orderable' => false, 'searchable' => false],
            ],
        );
    }

    //Get Table Data videos
    static function getTableDataVideo()
    {
        $buttons = [];
        $buttons = array(
            ['extend' => 'csv', 'exportOptions' => ['columns' => ':not(.notexport)']],
            ['extend' => 'excel', 'exportOptions' => ['columns' => ':not(.notexport)']],
            ['colvis']
        );
        return array(
            'title' => __('translation.Video'),
            'module' =>  __('translation.ManageGallery'),
            'active_page' =>  __('translation.VideoList'),
            'is_add' => auth()->user()->can('videos.add') ? true : false,
            'is_modal' => true,
            'is_modal_large' => false,
            'is_back_button' => false,
            'view_deleted_btn' => auth()->user()->can('videos.restore') ? false : false,
            'back_route' => route('videos.index'),
            'index_route' => route('videos.index'),
            'create_route' => route('videos.create'),
            'columns' => [
                '#',
                __('translation.Title'),
                __('translation.Description'),
                __('translation.Video'),
                __('translation.CreatedBy'),
                __('translation.Status'),
                __('translation.Action'),
            ],
            'ajax_url' => route('videos.get-ajax-data'),
            'buttons' => $buttons,
            'js_columns' => [
                ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'searchable' => false, 'orderable' => false],
                ['data' => 'title', 'name' => 'title', 'wrap' => true],
                ['data' => 'description', 'name' => 'description', 'wrap' => true],
                ['data' => 'file', 'name' => 'file', 'searchable' => false, 'orderable' => false],
                ['data' => 'created_by', 'name' => 'created_by_user.name'],
                ['data' => 'status', 'name' => 'status', 'searchable' => false, 'orderable' => false],
                ['data' => 'action', 'name' => 'action', 'orderable' => false, 'searchable' => false],
            ],
        );
    }
}
