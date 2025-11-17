<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enquiry extends Model
{

    //Belongs to Service
    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    //Belongs to Tour
    public function tour()
    {
        return $this->belongsTo(TourPackage::class, 'tour_id');
    }

    //Belongs to Tour Package
    public function tour_package()
    {
        return $this->belongsTo(TourPackage::class, 'tour_package_id');
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
            'title' => __('translation.EnquiryList'),
            'module' => __('translation.EnquiryList'),
            'active_page' => __('translation.EnquiryList'),
            'is_add' =>  false,
            'is_modal' => true,
            'is_modal_large' => false,
            'is_back_button' => false,
            'view_deleted_btn' => auth()->user()->can('enquiries.restore') ? false : false,
            'back_route' => route('enquiries.index'),
            'index_route' => route('enquiries.index'),
            'create_route' => route('enquiries.create'),
            'columns' => [
                '#',
                __('translation.Name'),
                __('translation.Email'),
                __('translation.Mobile'),
                __('translation.Message'),
                __('translation.Action'),
            ],
            'ajax_url' => route('enquiries.get-ajax-data'),
            'buttons' => $buttons,
            'js_columns' => [
                ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'searchable' => false, 'orderable' => false],
                ['data' => 'name', 'name' => 'name'],
                ['data' => 'email', 'name' => 'email'],
                ['data' => 'mobile', 'name' => 'mobile'],
                ['data' => 'message', 'name' => 'message', 'wrap' => true],
                ['data' => 'action', 'name' => 'action', 'orderable' => false, 'searchable' => false],
            ],
        );
    }

    //Get Service Enquiry Data Table
    static function getTableDataServiceEnquiry()
    {
        $buttons = [];
        $buttons = array(
            ['extend' => 'csv', 'exportOptions' => ['columns' => ':not(.notexport)']],
            ['extend' => 'excel', 'exportOptions' => ['columns' => ':not(.notexport)']],
            ['colvis']
        );
        return array(
            'title' => __('translation.ServiceEnquiryList'),
            'module' => __('translation.ManagerServiceEnquiries'),
            'active_page' => __('translation.ServiceEnquiryList'),
            'is_add' =>  false,
            'is_modal' => true,
            'is_modal_large' => false,
            'is_back_button' => false,
            'view_deleted_btn' => auth()->user()->can('enquiries.restore') ? false : false,
            'back_route' => route('enquiries.index', ['type' => 'service']),
            'index_route' => route('enquiries.index', ['type' => 'service']),
            'create_route' => route('enquiries.create'),
            'columns' => [
                '#',
                __('translation.Service'),
                __('translation.Name'),
                __('translation.Email'),
                __('translation.Mobile'),
                __('translation.Message'),
                __('translation.Action'),
            ],
            'ajax_url' => route('enquiries.get-ajax-data', ['type' => 'service']),
            'buttons' => $buttons,
            'js_columns' => [
                ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'searchable' => false, 'orderable' => false],
                ['data' => 'service', 'name' => 'service.title', 'wrap' => true],
                ['data' => 'name', 'name' => 'name'],
                ['data' => 'email', 'name' => 'email'],
                ['data' => 'mobile', 'name' => 'mobile'],
                ['data' => 'message', 'name' => 'message', 'wrap' => true],
                ['data' => 'action', 'name' => 'action', 'orderable' => false, 'searchable' => false],
            ],
        );
    }

    //Get Tour Enquiry Data Table
    static function getTableDataTourEnquiry()
    {
        $buttons = [];
        $buttons = array(
            ['extend' => 'csv', 'exportOptions' => ['columns' => ':not(.notexport)']],
            ['extend' => 'excel', 'exportOptions' => ['columns' => ':not(.notexport)']],
            ['colvis']
        );
        return array(
            'title' => __('translation.TourEnquiryList'),
            'module' => __('translation.ManagerTourEnquiries'),
            'active_page' => __('translation.TourEnquiryList'),
            'is_add' =>  false,
            'is_modal' => true,
            'is_modal_large' => false,
            'is_back_button' => false,
            'view_deleted_btn' => auth()->user()->can('enquiries.restore') ? false : false,
            'back_route' => route('enquiries.index', ['type' => 'tour']),
            'index_route' => route('enquiries.index', ['type' => 'tour']),
            'create_route' => route('enquiries.create'),
            'columns' => [
                '#',
                __('translation.Tour'),
                __('translation.Name'),
                __('translation.Email'),
                __('translation.Mobile'),
                __('translation.Message'),
                __('translation.Action'),
            ],
            'ajax_url' => route('enquiries.get-ajax-data', ['type' => 'tour']),
            'buttons' => $buttons,
            'js_columns' => [
                ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'searchable' => false, 'orderable' => false],
                ['data' => 'tour', 'name' => 'tour.name', 'wrap' => true],
                ['data' => 'name', 'name' => 'name'],
                ['data' => 'email', 'name' => 'email'],
                ['data' => 'mobile', 'name' => 'mobile'],
                ['data' => 'message', 'name' => 'message', 'wrap' => true],
                ['data' => 'action', 'name' => 'action', 'orderable' => false, 'searchable' => false],
            ],
        );
    }

    //Get Tour Package Enquiry Data Table
    static function getTableDataTourPackageEnquiry()
    {
        $buttons = [];
        $buttons = array(
            ['extend' => 'csv', 'exportOptions' => ['columns' => ':not(.notexport)']],
            ['extend' => 'excel', 'exportOptions' => ['columns' => ':not(.notexport)']],
            ['colvis']
        );
        return array(
            'title' => __('translation.TourPackageEnquiryList'),
            'module' => __('translation.ManagerTourPackageEnquiries'),
            'active_page' => __('translation.TourPackageEnquiryList'),
            'is_add' =>  false,
            'is_modal' => true,
            'is_modal_large' => false,
            'is_back_button' => false,
            'view_deleted_btn' => auth()->user()->can('enquiries.restore') ? false : false,
            'back_route' => route('enquiries.index', ['type' => 'tour_package']),
            'index_route' => route('enquiries.index', ['type' => 'tour_package']),
            'create_route' => route('enquiries.create'),
            'columns' => [
                '#',
                __('translation.TourPackage'),
                __('translation.Name'),
                __('translation.Email'),
                __('translation.Mobile'),
                __('translation.Message'),
                __('translation.Action'),
            ],
            'ajax_url' => route('enquiries.get-ajax-data', ['type' => 'tour_package']),
            'buttons' => $buttons,
            'js_columns' => [
                ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'searchable' => false, 'orderable' => false],
                ['data' => 'tour_package', 'name' => 'tour_package.name', 'wrap' => true],
                ['data' => 'name', 'name' => 'name'],
                ['data' => 'email', 'name' => 'email'],
                ['data' => 'mobile', 'name' => 'mobile'],
                ['data' => 'message', 'name' => 'message', 'wrap' => true],
                ['data' => 'action', 'name' => 'action', 'orderable' => false, 'searchable' => false],
            ],
        );
    }
}
