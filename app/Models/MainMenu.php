<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MainMenu extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($main_menu) {
            if ($main_menu->isForceDeleting()) {
                $main_menu->sub_menus()->forceDelete();
            } else {
                $main_menu->sub_menus()->delete();
            }
        });

        static::restored(function ($main_menu) {
            $main_menu->sub_menus()->restore();
        });
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

    //Belongs To User Deleted By
    public function deleted_by_user()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    //Has Many Sub Menus
    public function sub_menus()
    {
        return $this->hasMany(SubMenu::class, 'main_menu_id');
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
            'title' => __('translation.MainMenu'),
            'module' => __('translation.MainMenu'),
            'active_page' => __('translation.MainMenuList'),
            'is_add' => auth()->user()->can('settings.main-menu.add') ? false : false,
            'is_modal' => false,
            'is_modal_large' => false,
            'is_back_button' => false,
            'view_deleted_btn' => auth()->user()->can('settings.main-menu.restore') ? true : false,
            'back_route' => route('settings.main-menus.index'),
            'index_route' => route('settings.main-menus.index'),
            'create_route' => route('settings.main-menus.create'),
            'columns' => [
                __('translation.Order'),
                __('translation.MenuName'),
                __('translation.MenuIcon'),
                __('translation.CreatedBy'),
                __('translation.Status'),
                __('translation.Action')
            ],
            'ajax_url' => route('settings.main-menus.get-ajax-data'),
            'buttons' => $buttons,
            'js_columns' => [
                ['data' => 'order', 'name' => 'sorting', 'orderable' => false, 'searchable' => false],
                ['data' => 'menu_name', 'name' => 'menu_name'],
                ['data' => 'menu_icon', 'name' => 'menu_icon', 'searchable' => false, 'orderable' => false],
                ['data' => 'created_by', 'name' => 'created_by.name'],
                ['data' => 'status', 'name' => 'status', 'searchable' => false, 'orderable' => false],
                ['data' => 'action', 'name' => 'action', 'orderable' => false, 'searchable' => false],
            ],
        );
    }
}
