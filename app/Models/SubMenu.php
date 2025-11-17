<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubMenu extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

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

    //Belongs To User Deleted By
    public function deleted_by_user()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    //Belongs To Main Menu
    public function main_menu()
    {
        return $this->belongsTo(MainMenu::class, 'main_menu_id');
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
            'title' => __('translation.SubMenu'),
            'module' => __('translation.SubMenu'),
            'active_page' => __('translation.SubMenuList'),
            'is_add' => auth()->user()->can('settings.sub-menu.add') ? false : false,
            'is_modal' => false,
            'is_modal_large' => false,
            'is_back_button' => false,
            'view_deleted_btn' => auth()->user()->can('settings.sub-menu.restore') ? true : false,
            'back_route' => route('settings.sub-menus.index'),
            'index_route' => route('settings.sub-menus.index'),
            'create_route' => route('settings.sub-menus.create'),
            'columns' => [
                __('translation.Order'),
                __('translation.MainMenu'),
                __('translation.SubMenu'),
                // __('translation.MenuIcon'),
                __('translation.CreatedBy'),
                __('translation.Status'),
                __('translation.Action')
            ],
            'ajax_url' => route('settings.sub-menus.get-ajax-data'),
            'buttons' => $buttons,
            'js_columns' => [
                ['data' => 'order', 'name' => 'order', 'orderable' => false, 'searchable' => false],
                ['data' => 'main_menu', 'name' => 'main_menu.menu_name'],
                ['data' => 'menu_name', 'name' => 'menu_name'],
                // ['data' => 'menu_icon', 'name' => 'menu_icon', 'searchable' => false, 'orderable' => false],
                ['data' => 'created_by', 'name' => 'created_by.name'],
                ['data' => 'status', 'name' => 'status', 'searchable' => false, 'orderable' => false],
                ['data' => 'action', 'name' => 'action', 'orderable' => false, 'searchable' => false],
            ],
        );
    }
}
