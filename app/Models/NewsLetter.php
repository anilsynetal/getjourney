<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsLetter extends Model
{
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
            'title' => __('translation.NewsLetterList'),
            'module' => __('translation.ManageNewsLetters'),
            'active_page' => __('translation.NewsLetterList'),
            'is_add' =>  false,
            'is_modal' => true,
            'is_modal_large' => false,
            'is_back_button' => false,
            'is_status_filter' => false,
            'view_deleted_btn' => auth()->user()->can('news_letters.restore') ? false : false,
            'back_route' => route('news-letters.index'),
            'index_route' => route('news-letters.index'),
            'create_route' => route('news-letters.create'),
            'columns' => [
                '#',
                __('translation.Email'),
                __('translation.Action'),
            ],
            'ajax_url' => route('news-letters.get-ajax-data'),
            'buttons' => $buttons,
            'js_columns' => [
                ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'searchable' => false, 'orderable' => false],
                ['data' => 'email', 'name' => 'email'],
                ['data' => 'action', 'name' => 'action', 'orderable' => false, 'searchable' => false],
            ],
        );
    }
}
