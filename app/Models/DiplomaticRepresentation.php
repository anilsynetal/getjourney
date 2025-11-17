<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;

class DiplomaticRepresentation extends Model
{
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

    //Get Table data
    static function getTableData()
    {
        $buttons = [
            ['extend' => 'csv', 'exportOptions' => ['columns' => ':not(.notexport)']],
            ['extend' => 'excel', 'exportOptions' => ['columns' => ':not(.notexport)']],
            ['colvis']
        ];
        return [
            'title' => __('translation.DiplomaticRepresentation'),
            'module' => __('translation.ManageVisa'),
            'active_page' => __('translation.DiplomaticRepresentationList'),
            'is_add' => Gate::allows('manage_visa.diplomatic_representations.add') ? true : false,
            'is_edit' => Gate::allows('manage_visa.diplomatic_representations.edit') ? true : false,
            'is_delete' => Gate::allows('manage_visa.diplomatic_representations.delete') ? true : false,
            'is_status' => Gate::allows('manage_visa.diplomatic_representations.status') ? true : false,
            'is_modal' => false,
            'is_modal_large' => false,
            'is_back_button' => false,
            'view_deleted_btn' => false,
            'back_route' => route('manage-visa.diplomatic-representations.index'),
            'index_route' => route('manage-visa.diplomatic-representations.index'),
            'create_route' => route('manage-visa.diplomatic-representations.create'),
            'columns' => [
                '#',
                __('translation.Country'),
                __('translation.City'),
                __('translation.OfficeName'),
                __('translation.Address'),
                __('translation.ContactNumber1'),
                __('translation.ContactNumber2'),
                __('translation.FaxNumber'),
                __('translation.Email'),
                __('translation.CreatedBy'),
                __('translation.Status'),
                __('translation.Action'),
            ],
            'ajax_url' => route('manage-visa.diplomatic-representations.get-ajax-data'),
            'buttons' => $buttons,
            'js_columns' => [
                ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'searchable' => false, 'orderable' => false],
                ['data' => 'country', 'name' => 'country.country'],
                ['data' => 'city', 'name' => 'city'],
                ['data' => 'office_name', 'name' => 'office_name'],
                ['data' => 'address', 'name' => 'address'],
                ['data' => 'contact_number1', 'name' => 'contact_number1'],
                ['data' => 'contact_number2', 'name' => 'contact_number2'],
                ['data' => 'fax_number', 'name' => 'fax_number'],
                ['data' => 'email', 'name' => 'email'],
                ['data' => 'created_by', 'name' => 'created_by_user.name'],
                ['data' => 'status', 'name' => 'status', 'searchable' => false, 'orderable' => false],
                ['data' => 'action', 'name' => 'action', 'orderable' => false, 'searchable' => false],
            ],
        ];
    }
}
