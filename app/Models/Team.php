<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Team extends Model
{
    use SoftDeletes;

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
                'name' => 'email',
                'label' => __('translation.Email'),
                'type' => 'email',
                'id' => 'email',
                'class' => '',
                'required' => true,
                'placeholder' => __('translation.EnterEmail'),
                'col_size' => 'col-md-6'
            ],
            [
                'name' => 'mobile',
                'label' => __('translation.Mobile'),
                'type' => 'text',
                'id' => 'mobile1',
                'class' => '',
                'required' => true,
                'placeholder' => __('translation.EnterMobile'),
                'col_size' => 'col-md-6',
                'onkeyup' => 'this.value = this.value.replace(/[^0-9]/g, \'\');',
                'maxlength' => 10
            ],
            [
                'name' => 'country_code',
                'label' => __('translation.CountryCode'),
                'type' => 'hidden',
                'id' => 'country_code',
                'class' => '',
                'required' => false,
                'placeholder' => __('translation.EnterCountryCode'),
                'col_size' => 'col-md-6'
            ],
            [
                'name' => 'designation',
                'label' => __('translation.Designation'),
                'type' => 'text',
                'id' => 'designation',
                'class' => '',
                'required' => true,
                'placeholder' => __('translation.EnterDesignation'),
                'col_size' => 'col-md-6'
            ],
            [
                'name' => 'linkedin',
                'label' => __('translation.LinkedIn'),
                'type' => 'text',
                'id' => 'linkedin',
                'class' => '',
                'required' => false,
                'placeholder' => __('translation.EnterLinkedIn'),
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
            'title' => __('translation.Team'),
            'module' => __('translation.Settings'),
            'active_page' => __('translation.TeamList'),
            'is_add' => auth()->user()->can('teams.add') ? true : false,
            'is_modal' => true,
            'is_modal_large' => false,
            'is_back_button' => false,
            'view_deleted_btn' => auth()->user()->can('teams.restore') ? false : false,
            'back_route' => route('teams.index'),
            'index_route' => route('teams.index'),
            'create_route' => route('teams.create'),
            'columns' => [
                '#',
                __('translation.Name'),
                __('translation.Email'),
                __('translation.Mobile'),
                __('translation.Designation'),
                __('translation.LinkedIn'),
                __('translation.Image'),
                __('translation.CreatedBy'),
                __('translation.Status'),
                __('translation.Action'),
            ],
            'ajax_url' => route('teams.get-ajax-data'),
            'buttons' => $buttons,
            'js_columns' => [
                ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'searchable' => false, 'orderable' => false],
                ['data' => 'name', 'name' => 'name'],
                ['data' => 'email', 'name' => 'email'],
                ['data' => 'mobile', 'name' => 'mobile'],
                ['data' => 'designation', 'name' => 'designation'],
                ['data' => 'linkedin', 'name' => 'linkedin'],
                ['data' => 'image', 'name' => 'image', 'orderable' => false, 'searchable' => false],
                ['data' => 'created_by', 'name' => 'created_by_user.name'],
                ['data' => 'status', 'name' => 'status', 'searchable' => false, 'orderable' => false],
                ['data' => 'action', 'name' => 'action', 'orderable' => false, 'searchable' => false],
            ],
        );
    }
}
