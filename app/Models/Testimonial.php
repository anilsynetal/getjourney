<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
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
                'name' => 'name',
                'label' => __('translation.Name'),
                'type' => 'text',
                'id' => 'name',
                'class' => '',
                'required' => true,
                'placeholder' => __('translation.EnterName'),
                'col_size' => 'col-md-6'
            ],
            [
                'name' => 'designation',
                'label' => __('translation.Designation'),
                'type' => 'text',
                'id' => 'designation',
                'class' => '',
                'required' => false,
                'placeholder' => __('translation.EnterDesignation'),
                'col_size' => 'col-md-6'
            ],
            [
                'name' => 'description',
                'label' => __('translation.Description'),
                'type' => 'textarea',
                'id' => 'description',
                'class' => '',
                'required' => true,
                'placeholder' => __('translation.EnterDescription'),
                'col_size' => 'col-md-12'
            ],
            [
                'name' => 'image',
                'label' => __('translation.Image'),
                'type' => 'file',
                'id' => 'image',
                'class' => '',
                'required' => false,
                'placeholder' => __('translation.EnterImage'),
                'col_size' => 'col-md-6'
            ],
            [
                'name' => 'rating',
                'label' => __('translation.Rating'),
                'type' => 'text',
                'id' => 'rating',
                'class' => '',
                'required' => true,
                'placeholder' => __('translation.EnterRating'),
                'col_size' => 'col-md-6',
                'min' => 1,
                'max' => 5,
                'minlength' => 1,
                'maxlength' => 1,
                'oninput' => 'oninput=this.value=this.value.replace(/[^1-5]/g,\'\').substring(0,1);'
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
            'title' => __('translation.Testimonial'),
            'module' => __('translation.ManageTestimonials'),
            'active_page' => __('translation.TestimonialList'),
            'is_add' => auth()->user()->can('testimonials.add') ? true : false,
            'is_modal' => true,
            'is_modal_large' => false,
            'is_back_button' => false,
            'view_deleted_btn' => auth()->user()->can('testimonials.restore') ? false : false,
            'back_route' => route('testimonials.index'),
            'index_route' => route('testimonials.index'),
            'create_route' => route('testimonials.create'),
            'columns' => [
                '#',
                __('translation.Name'),
                __('translation.Designation'),
                __('translation.Description'),
                __('translation.Image'),
                __('translation.Rating'),
                __('translation.CreatedBy'),
                __('translation.Status'),
                __('translation.Action'),
            ],
            'ajax_url' => route('testimonials.get-ajax-data'),
            'buttons' => $buttons,
            'js_columns' => [
                ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'searchable' => false, 'orderable' => false],
                ['data' => 'name', 'name' => 'name'],
                ['data' => 'designation', 'name' => 'designation', 'wrap' => true],
                ['data' => 'description', 'name' => 'description', 'wrap' => true],
                ['data' => 'image', 'name' => 'image', 'searchable' => false, 'orderable' => false],
                ['data' => 'rating', 'name' => 'rating'],
                ['data' => 'created_by', 'name' => 'created_by_user.name'],
                ['data' => 'status', 'name' => 'status', 'searchable' => false, 'orderable' => false],
                ['data' => 'action', 'name' => 'action', 'orderable' => false, 'searchable' => false],
            ],
        );
    }
}
