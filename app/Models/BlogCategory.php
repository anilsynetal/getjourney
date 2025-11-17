<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogCategory extends Model
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

    //Has Many Blogs
    public function blogs()
    {
        return $this->hasMany(Blog::class, 'blog_category_id');
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
                'placeholder' => __('translation.EnterCategoryName'),
                'col_size' => 'col-md-12'
            ],
            [
                'name' => 'icon',
                'label' => __('translation.Icon'),
                'type' => 'text',
                'id' => 'icon',
                'class' => '',
                'required' => false,
                'placeholder' => 'eg. fa fa-cog',
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
            'title' => __('translation.BlogCategory'),
            'module' => __('translation.ManageBlogs'),
            'active_page' => __('translation.BlogCategoryList'),
            'is_add' => auth()->user()->can('manage_blogs.blog_categories.add') ? true : false,
            'is_modal' => true,
            'is_modal_large' => false,
            'is_back_button' => false,
            'view_deleted_btn' => auth()->user()->can('manage_blogs.blog_categories.restore') ? false : false,
            'back_route' => route('manage-blogs.blog-categories.index'),
            'index_route' => route('manage-blogs.blog-categories.index'),
            'create_route' => route('manage-blogs.blog-categories.create'),
            'columns' => [
                '#',
                __('translation.Name'),
                __('translation.Icon'),
                __('translation.CreatedBy'),
                __('translation.Status'),
                __('translation.Action'),
            ],
            'ajax_url' => route('manage-blogs.blog-categories.get-ajax-data'),
            'buttons' => $buttons,
            'js_columns' => [
                ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'searchable' => false, 'orderable' => false],
                ['data' => 'name', 'name' => 'name', 'wrap' => true],
                ['data' => 'icon', 'name' => 'icon', 'searchable' => false, 'orderable' => false],
                ['data' => 'created_by', 'name' => 'created_by_user.name'],
                ['data' => 'status', 'name' => 'status', 'searchable' => false, 'orderable' => false],
                ['data' => 'action', 'name' => 'action', 'orderable' => false, 'searchable' => false],
            ],
        );
    }
}
