<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;

class VisaDetail extends Model
{
    protected $guarded = [];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Belongs To Country
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    // Belongs To VisaCategory
    public function visa_category()
    {
        return $this->belongsTo(VisaCategory::class, 'visa_category_id');
    }

    // Has Many VisaDetailDocument
    public function documents()
    {
        return $this->hasMany(VisaDetailDocument::class, 'visa_detail_id');
    }

    // Belongs To User Created By
    public function created_by_user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Belongs To User Updated By
    public function updated_by_user()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Get Table Data
    static function getTableData()
    {
        $buttons = [
            ['extend' => 'csv', 'exportOptions' => ['columns' => ':not(.notexport)']],
            ['extend' => 'excel', 'exportOptions' => ['columns' => ':not(.notexport)']],
            ['colvis']
        ];
        return [
            'title' => __('translation.VisaDetail'),
            'module' => __('translation.ManageVisa'),
            'active_page' => __('translation.VisaDetailList'),
            'is_add' => Gate::allows('manage_visa.visa_details.add') ? true : false,
            'is_edit' => Gate::allows('manage_visa.visa_details.edit') ? true : false,
            'is_delete' => Gate::allows('manage_visa.visa_details.delete') ? true : false,
            'is_status' => Gate::allows('manage_visa.visa_details.status') ? true : false,
            'is_modal' => false,
            'is_modal_large' => false,
            'is_back_button' => false,
            'view_deleted_btn' => false,
            'back_route' => route('manage-visa.visa-details.index'),
            'index_route' => route('manage-visa.visa-details.index'),
            'create_route' => route('manage-visa.visa-details.create'),
            'columns' => [
                '#',
                __('translation.Country'),
                __('translation.City'),
                __('translation.VisaCategory'),
                __('translation.VisaFees'),
                __('translation.LogisticCharges'),
                __('translation.DocumentsCount'),
                __('translation.CreatedBy'),
                __('translation.Status'),
                __('translation.Action'),
            ],
            'ajax_url' => route('manage-visa.visa-details.get-ajax-data'),
            'buttons' => $buttons,
            'js_columns' => [
                ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'searchable' => false, 'orderable' => false],
                ['data' => 'country', 'name' => 'country.country'],
                ['data' => 'city', 'name' => 'city'],
                ['data' => 'visa_category', 'name' => 'visa_category.name'],
                ['data' => 'visa_fees', 'name' => 'visa_fees'],
                ['data' => 'logistic_charges', 'name' => 'logistic_charges'],
                ['data' => 'documents_count', 'name' => 'documents_count', 'searchable' => false, 'orderable' => false],
                ['data' => 'created_by', 'name' => 'created_by_user.name'],
                ['data' => 'status', 'name' => 'status', 'searchable' => false, 'orderable' => false],
                ['data' => 'action', 'name' => 'action', 'orderable' => false, 'searchable' => false],
            ],
        ];
    }
}
