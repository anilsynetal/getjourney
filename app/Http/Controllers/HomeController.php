<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Benefit;
use App\Models\Blog;
use App\Models\CaseStudy;
use App\Models\City;
use App\Models\Client;
use App\Models\Enquiry;
use App\Models\FAQ;
use App\Models\Feature;
use App\Models\NewsLetter;
use App\Models\Service;
use App\Models\Slider;
use App\Models\State;
use App\Models\SubMenu;
use App\Models\Team;
use App\Models\Testimonial;
use App\Models\TourPackage;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use App\Utils\Util;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function index()
    {
        $role = Auth::user()->roles()->pluck('name')->first();
        $data['total_services'] = Service::count();
        $data['total_testimonials'] = Testimonial::count();
        $data['total_faqs'] = FAQ::count();
        $data['total_blogs'] = Blog::count();
        $data['total_case_studies'] = CaseStudy::count();
        $data['total_benefits'] = Benefit::count();
        $data['total_subscribers'] = NewsLetter::count();
        $data['total_sliders'] = Slider::count();
        $data['total_features'] = Feature::count();
        $data['total_team_members'] = Team::where('status', 1)->count();
        $data['total_teams'] = Team::count();
        $data['total_tours'] = TourPackage::count();
        $data['total_service_enquiries'] = Enquiry::whereNotNull('service_id')->count();
        $data['total_tour_enquiries'] = Enquiry::whereNotNull('tour_id')->count();
        $data['enquiries'] = Enquiry::take(5)->get();

        return view('home.index', compact('data'));
    }

    //Activity Logs
    public function activity_logs()
    {
        $common_data = ActivityLog::getTableData();
        $ajax_url = $common_data['ajax_url'];
        $common_data['module'] = __('translation.ActivityLogs');
        return view('home.activity-logs', compact('common_data', 'ajax_url'));
    }

    //Get Activity Logs Ajax Data
    public function get_activity_log_ajax_data()
    {
        $data = ActivityLog::with('created_by_user')->orderBy('id', 'desc')->get();
        return Datatables::of($data)
            ->addIndexColumn()
            ->editColumn('created_at', function ($row) {
                return date('d/m/Y h:i A', strtotime($row->created_at));
            })
            ->editColumn('created_by', function ($row) {
                return $row->created_by_user?->name;
            })
            ->rawColumns(['DT_RowIndex', 'created_at', 'created_by'])
            ->make(true);
    }

    //Change Language
    public function lang($locale)
    {
        if ($locale) {
            App::setLocale($locale);
            Session::put('lang', $locale);
            Session::save();
            return redirect()->back()->with('locale', $locale);
        } else {
            return redirect()->back();
        }
    }

    //Profile
    public function profile()
    {
        $result = User::with('roles')->find(Auth::user()->id);
        $common_data['module'] = __('translation.Dashboard');
        $common_data['active_page'] = __('translation.UserProfile');
        return view('home.profile', compact('result', 'common_data'));
    }

    //Change Password
    public function change_password()
    {
        $route_action = route('home.update-password', Auth::user()->id);
        return view('home.change-password', compact('route_action'))->render();
    }

    //Update Profile
    public function update_profile(Request $request, $id)
    {
        if (Auth::user()->role == 'admin') {
            $validator = Validator::make(
                $request->all(),
                [
                    'first_name' => 'required|max:50',
                    'last_name' => 'required|max:50',
                    'mobile' => 'required',
                    'image' => 'mimes:jpeg,jpg,png,gif|max:2048',
                ]
            );
        } else {
            $validator = Validator::make(
                $request->all(),
                [
                    'first_name' => 'required|max:50',
                    'last_name' => 'required|max:50',
                    'mobile' => 'required',
                    'image' => 'mimes:jpeg,jpg,png,gif|max:2048',
                ]
            );
        }
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }
        //remove special characters from mobile number
        $mobile =  str_replace('-', '', str_replace(' ', '', str_replace('(', '', str_replace(')', '', $request->mobile))));
        try {
            DB::beginTransaction();
            //Update User Table
            $user_update = User::find($id);
            $user_update->first_name = $request->first_name;
            $user_update->last_name = $request->last_name;
            $user_update->country_code = $request->country_code;
            $user_update->mobile = substr($mobile, -10); //Get Last 10 digit of mobile number
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $image_name = Util::uploadFile($image, 'users');
                $user_update->image = $image_name;
            }
            $user_update->save();
            Util::activityLog('User', 'Profile Updated', $user_update);
            DB::commit();
            $response = ['status' => 'success', 'message' => __('translation.UpdatedSuccessfully'), 'redirect' => true, 'url' => route('profile.edit')];
            $status_code = 200;
        } catch (\Exception $th) {
            //Log error
            Util::generateErrorLog($th);
            $response = ['status' => 'error', 'message' => $th->getMessage()];
            $status_code = 500;
        }
        return response()->json($response, $status_code);
    }

    public function update_password(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'current_password' => ['required', 'string'],
                'password' => [
                    'required',
                    'string',
                    'confirmed',
                    'min:8',
                    'regex:/[a-z]/',
                    'regex:/[A-Z]/',
                    'regex:/[0-9]/',
                    'regex:/[@$!%*#?&]/',
                ],
            ],
            [
                'password.regex' => 'Password should be at least 8 characters, contain upper case, lower case, numbers and special characters (!@£$%^&)'
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        if (!(Hash::check($request->get('current_password'), Auth::user()->password))) {
            return response()->json([
                'status' => 'error',
                'errors' => ['current_password' => 'Current password does not match!']
            ], 422);
        }
        try {
            $update  = User::find($id);
            $update->password = Hash::make($request->password);
            $update->is_password_updated = 1;
            $update->save();
            auth()->logout();
            Util::activityLog('User', 'Profile Password Updated', $update);
            $response = ['status' => 'success', 'message' => __('translation.password_UpdatedSuccessfully'), 'redirect' => true, 'url' => route('login')];
            $status_code = 200;
        } catch (\Throwable $th) {
            //Log error
            Util::generateErrorLog($th);
            $response = ['status' => 'error', 'message' => $th->getMessage()];
            $status_code = 500;
        }
        return response()->json($response, $status_code);
    }

    public function get_suggestions(Request $request)
    {
        $query = $request->input('query');

        // Fetch matching results from your database
        // $results = MainMenu::where('menu_name', 'like', '%' . $query . '%')->get();
        $results = SubMenu::where('menu_name', 'like', '%' . $query . '%')
            ->get(); // Replace 'name' with the column you want to search
        return response()->json($results);
    }

    public function search(Request $request)
    {
        //Check if route name is available in web.php
        if (!array_key_exists($request->route_name, app('router')->getRoutes()->getRoutesByName())) {
            return view('pages-404');
        }
        return redirect()->route($request->route_name);
    }

    //Back to admin
    public function back_to_admin()
    {
        $admin = User::find(session()->get('admin_id'));
        if ($admin) {
            //Logout current user
            Auth::logout();
            Auth::login($admin);
            session()->forget('admin_id');
            return redirect('/home');
        } else {
            return redirect()->back()->with('error', 'Admin not found');
        }
    }

    //Get State based on Country
    public function get_states()
    {
        $states = State::select('state as name', 'id')->where('country_id', request('id'))
            ->get();
        return response()->json($states);
    }

    //Get Cities based on State
    public function get_cities()
    {
        $cities = City::select('city as name', 'id')->where('state_id', request('id'))
            ->get();
        return response()->json($cities);
    }
}
