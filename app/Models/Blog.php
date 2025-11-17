<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
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

    //Belongs To Blog Cateory
    public function blog_category()
    {
        return $this->belongsTo(BlogCategory::class, 'blog_category_id');
    }

    //Form Fields
    static function fields($id = null)
    {
        $form_fields = [
            [
                'name' => 'blog_category_id',
                'label' => __('translation.Category'),
                'type' => 'select',
                'id' => 'blog_category_id',
                'class' => '',
                'required' => true,
                'placeholder' => __('translation.SelectBlogCategory'),
                'col_size' => 'col-md-12',
                'options' => BlogCategory::pluck('name', 'id')->toArray()
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
                'name' => 'description',
                'label' => __('translation.Description'),
                'type' => 'textarea',
                'id' => 'description',
                'class' => '',
                'required' => true,
                'placeholder' => __('translation.EnterDescription'),
                'col_size' => 'col-md-12',
                'rows' => 8
            ],
            [
                'name' => 'image',
                'label' => __('translation.Image'),
                'type' => 'file',
                'id' => 'image',
                'class' => '',
                'required' => ($id ? false : true),
                'placeholder' => __('translation.SelectImage'),
                'col_size' => 'col-md-6'
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
            'title' => __('translation.Blogs'),
            'module' => __('translation.ManageBlogs'),
            'active_page' => __('translation.BlogList'),
            'is_add' => auth()->user()->can('manage_blogs.blogs.add') ? true : false,
            'is_modal' => true,
            'is_modal_large' => true,
            'is_back_button' => false,
            'view_deleted_btn' => auth()->user()->can('manage_blogs.blogs.restore') ? false : false,
            'back_route' => route('manage-blogs.blogs.index'),
            'index_route' => route('manage-blogs.blogs.index'),
            'create_route' => route('manage-blogs.blogs.create'),
            'columns' => [
                '#',
                __('translation.Category'),
                __('translation.Title'),
                __('translation.Description'),
                __('translation.Image'),
                __('translation.CreatedBy'),
                __('translation.Status'),
                __('translation.Action'),
            ],
            'ajax_url' => route('manage-blogs.blogs.get-ajax-data'),
            'buttons' => $buttons,
            'js_columns' => [
                ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'searchable' => false, 'orderable' => false],
                ['data' => 'blog_category_id', 'name' => 'blog_category.name', 'wrap' => true],
                ['data' => 'title', 'name' => 'title', 'wrap' => true],
                ['data' => 'description', 'name' => 'description', 'wrap' => true],
                ['data' => 'image', 'name' => 'image', 'searchable' => false, 'orderable' => false],
                ['data' => 'created_by', 'name' => 'created_by_user.name'],
                ['data' => 'status', 'name' => 'status', 'searchable' => false, 'orderable' => false],
                ['data' => 'action', 'name' => 'action', 'orderable' => false, 'searchable' => false],
            ],
        );
    }
}
