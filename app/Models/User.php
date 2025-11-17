<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Spatie\Permission\Models\Role;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'remember_token'
    ];
    protected $appends = ['image_url'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    //Insert full name
    public function setFullNameAttribute()
    {
        $this->attributes['full_name'] = $this->first_name . ' ' . $this->last_name;
    }

    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset($this->image);
        }

        // Return a default image path if no image is set
        return asset('assets/img/default-profile.png');
    }

    //Get the roles of the user
    public function getRoleAttribute()
    {
        $role = $this->roles->first();
        return $role ? $role->name : 'No Role';
    }

    //Belongs To Relationship with Country
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    //Belongs To Relationship with State
    public function state()
    {
        return $this->belongsTo(State::class, 'state_id');
    }

    //Belongs To Relationship with City
    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    //Has Many Customers Relationship
    public function customers()
    {
        return $this->hasMany(User::class, 'created_by');
    }

    static function fields($is_edit = false)
    {
        $form_fields = [
            array('name' => 'first_name', 'type' => 'text', 'class' => '', 'id' => 'first_name', 'label' => __('translation.FirstName'), 'placeholder' => __('translation.FirstName'), 'readonly' => false, 'required' => true, 'col_size' => 'col-md-6'),
            array('name' => 'last_name', 'type' => 'text', 'class' => '', 'id' => 'last_name', 'label' => __('translation.LastName'), 'placeholder' => __('translation.LastName'), 'readonly' => false, 'required' => true, 'col_size' => 'col-md-6'),
            array('name' => 'email', 'type' => 'email', 'class' => '', 'id' => 'email', 'label' => __('translation.Email'), 'placeholder' => __('translation.Email'), 'readonly' => false, 'required' => true, 'col_size' => 'col-md-6', 'oninput' => 'oninput=this.value=this.value.replace(/[^a-zA-Z0-9@._-]/g,\'\').substring(0,100);', 'onclick' => 'onclick="select();"'),

            array('name' => 'company_name', 'type' => 'text', 'class' => '', 'id' => 'company_name', 'label' => __('translation.CompanyName'), 'placeholder' => __('translation.CompanyName'), 'readonly' => false, 'required' => true, 'col_size' => 'col-md-6'),
            array('name' => 'company_website', 'type' => 'text', 'class' => '', 'id' => 'company_website', 'label' => __('translation.CompanyWebsite'), 'placeholder' => __('translation.CompanyWebsite'), 'readonly' => false, 'required' => true, 'col_size' => 'col-md-6', 'oninput' => 'oninput=this.value=this.value.replace(/[^a-zA-Z0-9:\/._-]/g,\'\').substring(0,100);', 'onclick' => 'onclick="select();"'),
            array('name' => 'country_code', 'type' => 'hidden', 'class' => '', 'id' => 'country_code', 'label' => __('translation.CountryCode'), 'placeholder' => __('translation.CountryCode'), 'readonly' => false, 'required' => true, 'col_size' => 'col-md-6'),

            array('name' => 'mobile', 'type' => 'text', 'class' => '', 'id' => 'mobile1', 'label' => __('translation.Mobile'), 'placeholder' => __('translation.Mobile'), 'readonly' => false, 'required' => true, 'col_size' => 'col-md-6', 'oninput' => 'oninput=this.value=this.value.replace(/[^0-9]/g,\'\').substring(0,10);', 'onclick' => 'onclick="select();"'),

            array('name' => 'designation', 'type' => 'text', 'class' => '', 'id' => 'designation', 'label' => __('translation.Designation'), 'placeholder' => __('translation.Designation'), 'readonly' => false, 'required' => true, 'col_size' => 'col-md-6'),
            array('name' => 'address', 'type' => 'text', 'class' => '', 'id' => 'address', 'label' => __('translation.Address'), 'placeholder' => __('translation.Address'), 'readonly' => false, 'required' => true, 'col_size' => 'col-md-6'),

            array('name' => 'country_id', 'type' => 'select', 'class' => '', 'id' => 'country_id', 'label' => __('translation.Country'), 'placeholder' => __('translation.SelectCountry'), 'readonly' => false, 'required' => true, 'col_size' => 'col-md-6', 'options' => Country::pluck('country', 'id'), 'data_append_id' => 'state_id', 'data_url' => route('get-states')),
            array('name' => 'state_id', 'type' => 'select', 'class' => '', 'id' => 'state_id', 'label' => __('translation.State'), 'placeholder' => __('translation.SelectState'), 'readonly' => false, 'required' => true, 'col_size' => 'col-md-6', 'options' => [], 'data_append_id' => 'city_id', 'data_url' => route('get-cities')),
            array('name' => 'city_id', 'type' => 'select', 'class' => '', 'id' => 'city_id', 'label' => __('translation.City'), 'placeholder' => __('translation.SelectCity'), 'readonly' => false, 'required' => true, 'col_size' => 'col-md-6', 'options' => []),
            array('name' => 'role_id', 'type' => 'select', 'class' => '', 'id' => 'role_id', 'label' => __('translation.Role'), 'placeholder' => __('translation.Role'), 'readonly' => false, 'required' => true, 'col_size' => 'col-md-6', 'options' => Role::whereNot('name', 'Admin')->pluck('name', 'id')),
            array(
                'name' => 'image',
                'label' => __('translation.Image'),
                'type' => 'file',
                'id' => 'image',
                'class' => '',
                'required' => false,
                'placeholder' => __('translation.UploadImage'),
                'col_size' => 'col-md-6'
            ),
        ];
        if ($is_edit == false) {
            $form_fields[] =  array('name' => 'password', 'type' => 'password', 'class' => '', 'id' => 'password', 'label' => __('translation.Password'), 'placeholder' => __('translation.Password'), 'readonly' => false, 'required' => true, 'col_size' => 'col-md-6');

            $form_fields[] =  array('name' => 'password_confirmation', 'type' => 'password', 'class' => '', 'id' => 'password_confirmation', 'label' => __('translation.ConfirmPassword'), 'placeholder' => __('translation.ConfirmPassword'), 'readonly' => false, 'required' => true, 'col_size' => 'col-md-6');
        }
        $form_fields[] = array('name' => 'status', 'type' => 'select', 'class' => '', 'id' => 'status', 'label' => __('translation.Status'), 'placeholder' => __('translation.Status'), 'readonly' => false, 'required' => true, 'col_size' => 'col-md-6', 'options' => ['1' => __('translation.Active'), '0' => __('translation.Inactive'),]);
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
            'title' => __('translation.ClientList'),
            'module' => __('translation.ManageClients'),
            'active_page' => __('translation.Clients'),
            'is_add' => true,
            'is_modal' => true,
            'is_modal_large' => true,
            'is_back_button' => false,
            'view_deleted_btn' => true,
            'view_deleted_btn' => auth()->user()->can('users_and_roles.users.restore') ? true : false,
            'back_route' => route('user-managements.users.index'),
            'index_route' => route('user-managements.users.index'),
            'create_route' => route('user-managements.users.create'),
            'columns' => [
                '#',
                __('translation.Image'),
                __('translation.Name'),
                __('translation.Email'),
                __('translation.Mobile'),
                __('translation.Designation'),
                __('translation.Company'),
                __('translation.Address'),
                __('translation.CreatedAt'),
                __('translation.Status'),
                __('translation.Action'),
            ],
            'ajax_url' => route('user-managements.users.get-ajax-data'),
            'buttons' => $buttons,
            'js_columns' => [
                ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'orderable' => false, 'searchable' => false],
                ['data' => 'image', 'name' => 'image'],
                ['data' => 'full_name', 'name' => 'full_name'],
                ['data' => 'email', 'name' => 'email'],
                ['data' => 'mobile', 'name' => 'mobile'],
                ['data' => 'designation', 'name' => 'designation'],
                ['data' => 'company_name', 'name' => 'company_name'],
                ['data' => 'address', 'name' => 'address', 'wrap' => true],
                ['data' => 'created_at', 'name' => 'created_at', 'wrap' => true],
                ['data' => 'status', 'name' => 'status', 'searchable' => false, 'orderable' => false],
                ['data' => 'action', 'name' => 'action', 'orderable' => false, 'searchable' => false, 'wrap' => true],
            ],
        );
    }
}
