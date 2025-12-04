<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\WebsiteController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\SocialLoginController;
use App\Http\Controllers\BenefitController;
use App\Http\Controllers\BlogCategoryController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CaseStudyController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CounterController;
use App\Http\Controllers\EnquiryController;
use App\Http\Controllers\FAQController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InternationalHelpAddressController;
use App\Http\Controllers\LogisticPartnerController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

use App\Http\Controllers\MainMenuController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SubMenuController;
use App\Http\Controllers\VisaCategoryController;
use App\Http\Controllers\VisaInformationController;
use App\Http\Controllers\DiplomaticRepresentationController;
use App\Http\Controllers\VisaFormController;
use App\Http\Controllers\VisaDetailController;

use App\Http\Controllers\Installer\WelcomeController;
use App\Http\Controllers\Installer\RequirementsController;
use App\Http\Controllers\Installer\PermissionsController;
use App\Http\Controllers\Installer\EnvironmentController;
use App\Http\Controllers\Installer\DatabaseController;
use App\Http\Controllers\Installer\FinalController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FeatureController;
use App\Http\Controllers\NewsLetterController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TourPackageController;
use App\Http\Controllers\Admin\BoatWidgetEnquiryController as AdminBoatWidgetEnquiryController;

Route::group(['prefix' => 'install', 'as' => 'installer.'], function () {
    Route::get('/', [WelcomeController::class, 'welcome'])->name('welcome');
    Route::get('/requirements', [RequirementsController::class, 'requirements'])->name('requirements');
    Route::get('/permissions', [PermissionsController::class, 'permissions'])->name('permissions');

    // Environment Setup
    Route::get('/environment', [EnvironmentController::class, 'environment'])->name('environment');
    Route::post('/environment/save', [EnvironmentController::class, 'save'])->name('environmentSave');

    // Database Setup
    Route::get('/database', [DatabaseController::class, 'database'])->name('database');

    // Final Step
    Route::get('/verify', [FinalController::class, 'verify'])->name('verify');
    Route::post('/validate', [FinalController::class, 'validate'])->name('validate');
    Route::get('/finish', [FinalController::class, 'finish'])->name('finish');
});

//Social Login
Route::get('auth/{provider}', [SocialLoginController::class, 'redirect']);
Route::get('auth/{provider}/callback', [SocialLoginController::class, 'callback']);


Auth::routes(['verify' => true]);

Route::get('/otp-verify', [LoginController::class, 'otpVerify'])->name('otp.verify');
Route::post('/resend-otp', [LoginController::class, 'resendOtp'])->name('resend-otp');
Route::post('/validate-otp', [LoginController::class, 'validateOtp'])->name('validate.otp');

Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'user.auth', 'verified']], function () {
    // Route::get('/', [HomeController::class, 'index']);
    Route::get('/', [HomeController::class, 'index'])->name('root');
    Route::post('home/get-ajax-data-latest-booking', [HomeController::class, 'getAjaxDataLatestBooking'])->name('home.get-ajax-data-latest-booking');
    Route::get('/back-to-admin', [HomeController::class, 'back_to_admin'])->name('back-to-admin');
    Route::get('/search-suggestions', [HomeController::class, 'get_suggestions'])->name('home.search.suggestions');
    Route::post('/search', [HomeController::class, 'search'])->name('home.search');
    Route::get('home/profile', [HomeController::class, 'profile'])->name('profile.edit');
    Route::put('home/update-profile/{id}', [HomeController::class, 'update_profile'])->name('profile.update');
    Route::get('home/change-password', [HomeController::class, 'change_password'])->name('home.change-password');
    Route::post('home/update-password/{id}', [HomeController::class, 'update_password'])->name('home.update-password');
    Route::get('home/activity-logs', [HomeController::class, 'activity_logs'])->name('home.activity-logs');
    Route::post('home/get-activity-log-ajax-data', [HomeController::class, 'get_activity_log_ajax_data'])->name('home.get-activity-log-ajax-data');

    //Update User Details
    Route::post('/update-profile/{id}', [HomeController::class, 'updateProfile'])->name('updateProfile');
    Route::post('/update-password/{id}', [HomeController::class, 'updatePassword'])->name('updatePassword');
    //settings
    Route::prefix('settings')->name('settings.')->group(function () {
        //setting
        Route::get('/', [SettingController::class, 'index'])->name('index');
        Route::post('/app-name/update', [SettingController::class, 'appNameUpdate'])->name('appname.update');
        Route::post('/email-setting/update', [SettingController::class, 'emailSettingUpdate'])->name('email.setting.update');
        Route::get('/test-mail', [SettingController::class, 'testMail'])->name('test.mail');

        Route::post('/test-mail', [SettingController::class, 'testSendMail'])->name('test.send.mail');
        Route::post('/google-recaptcha-update', [SettingController::class, 'google_recaptcha_update'])->name('google-recaptcha.update');
        Route::post('/social-login-update', [SettingController::class, 'social_login_update'])->name('social-login.update');
        Route::get('create-backup', [SettingController::class, 'create_backup'])->name('create-backup');
        Route::post('get-backup-ajax-data', [SettingController::class, 'getBackupAjaxData'])->name('get-backup-ajax-data');
        Route::delete('delete_backup/{file}', [SettingController::class, 'delete_backup'])->name('delete-backup');
        //Main Menus Routes
        Route::post('main-menus/get-ajax-data', [MainMenuController::class, 'getAjaxData'])->name('main-menus.get-ajax-data');
        Route::get('main-menus/{id}/status', [MainMenuController::class, 'status'])->name('main-menus.status');
        Route::get('main-menus/{id}/restore', [MainMenuController::class, 'restore'])->name('main-menus.restore');
        Route::post('main-menus/update-order', [MainMenuController::class, 'update_order'])->name('main-menus.update-order');
        Route::resource('main-menus', MainMenuController::class);

        //Sub Menus Routes
        Route::post('sub-menus/get-ajax-data', [SubMenuController::class, 'getAjaxData'])->name('sub-menus.get-ajax-data');
        Route::get('sub-menus/{id}/status', [SubMenuController::class, 'status'])->name('sub-menus.status');
        Route::get('sub-menus/{id}/restore', [SubMenuController::class, 'restore'])->name('sub-menus.restore');
        Route::post('sub-menus/update-order', [SubMenuController::class, 'update_order'])->name('sub-menus.update-order');
        Route::resource('sub-menus', SubMenuController::class);
        Route::get('get-sub-menu-by-main-menu', [SubMenuController::class, 'getSubMenuByMainMenu'])->name('sub-menus.get-sub-menu-by-main-menu');

        //Contact Us Routes
        Route::post('contacts/get-ajax-data', [ContactController::class, 'getAjaxData'])->name('contacts.get-ajax-data');
        Route::get('contacts/{id}/status', [ContactController::class, 'status'])->name('contacts.status');
        Route::resource('contacts', ContactController::class);

        //About Us Routes
        Route::post('about-us/get-ajax-data', [AboutController::class, 'getAjaxData'])->name('about-us.get-ajax-data');
        Route::get('about-us/{id}/status', [AboutController::class, 'status'])->name('about-us.status');
        Route::resource('about-us', AboutController::class);

        //Team Routes
        Route::post('teams/get-ajax-data', [TeamController::class, 'getAjaxData'])->name('teams.get-ajax-data');
        Route::get('teams/{id}/status', [TeamController::class, 'status'])->name('teams.status');
        Route::get('teams/{id}/restore', [TeamController::class, 'restore'])->name('teams.restore');
        Route::resource('teams', TeamController::class);
    });

    //User Management Routes
    Route::prefix('user-managements')->name('user-managements.')->group(function () {
        //Permissions Routes
        Route::resource('permissions', PermissionController::class);
        //Role Routes
        Route::resource('roles', RoleController::class);
        //Employee Routes
        Route::get('users/{id}/change-password', [UserController::class, 'change_password'])->name('users.change-password');
        Route::post('users/update-password/{id}', [UserController::class, 'update_password'])->name('users.update-password');
        Route::get('users/{id}/status', [UserController::class, 'status'])->name('users.status');
        Route::get('users/{id}/restore', [UserController::class, 'restore'])->name('users.restore');
        Route::get('users/{id}/dashboard', [UserController::class, 'dashboard'])->name('users.dashboard');
        Route::post('users/get-ajax-data', [UserController::class, 'getAjaxData'])->name('users.get-ajax-data');
        Route::resource('users', UserController::class);
        // get user data by id
        Route::get('get-user-data', [UserController::class, 'getUserData'])->name('users.get-user-data');
    });
    //Enquiry Routes
    //Service Enquiries
    Route::get('service-enquiries', [EnquiryController::class, 'service_enquiries'])->name('enquiries.services');
    //Tour Enquiries
    Route::get('tour-enquiries', [EnquiryController::class, 'tour_enquiries'])->name('enquiries.tours');
    //Tour Package Enquiries
    Route::get('tour-package-enquiries', [EnquiryController::class, 'tour_package_enquiries'])->name('enquiries.tour_packages');

    Route::post('enquiries/get-ajax-data', [EnquiryController::class, 'getAjaxData'])->name('enquiries.get-ajax-data');
    Route::get('enquiries/{id}/restore', [EnquiryController::class, 'restore'])->name('enquiries.restore');
    Route::resource('enquiries', EnquiryController::class);

    //Blog Routes
    Route::prefix('manage-blogs')->name('manage-blogs.')->group(function () {
        //Blog Category Routes
        Route::post('blog-categories/get-ajax-data', [BlogCategoryController::class, 'getAjaxData'])->name('blog-categories.get-ajax-data');
        Route::get('blog-categories/{id}/status', [BlogCategoryController::class, 'status'])->name('blog-categories.status');
        Route::get('blog-categories/{id}/restore', [BlogCategoryController::class, 'restore'])->name('blog-categories.restore');
        Route::resource('blog-categories', BlogCategoryController::class);

        //Blog Routes
        Route::post('blogs/get-ajax-data', [BlogController::class, 'getAjaxData'])->name('blogs.get-ajax-data');
        Route::get('blogs/{id}/status', [BlogController::class, 'status'])->name('blogs.status');
        Route::get('blogs/{id}/restore', [BlogController::class, 'restore'])->name('blogs.restore');
        Route::resource('blogs', BlogController::class);
    });

    //Visa Routes
    Route::prefix('manage-visa')->name('manage-visa.')->group(function () {
        //Visa Category Routes
        Route::post('visa-categories/get-ajax-data', [VisaCategoryController::class, 'getAjaxData'])->name('visa-categories.get-ajax-data');
        Route::get('visa-categories/{id}/status', [VisaCategoryController::class, 'status'])->name('visa-categories.status');
        Route::get('visa-categories/{id}/restore', [VisaCategoryController::class, 'restore'])->name('visa-categories.restore');
        Route::resource('visa-categories', VisaCategoryController::class);

        //Visa Information Routes
        Route::post('visa-information/get-ajax-data', [VisaInformationController::class, 'getAjaxData'])->name('visa-information.get-ajax-data');
        Route::get('visa-information/{id}/status', [VisaInformationController::class, 'status'])->name('visa-information.status');
        Route::get('visa-information/{id}/restore', [VisaInformationController::class, 'restore'])->name('visa-information.restore');
        Route::resource('visa-information', VisaInformationController::class);

        //Diplomatic Representation Routes
        Route::post('diplomatic-representations/get-ajax-data', [DiplomaticRepresentationController::class, 'getAjaxData'])->name('diplomatic-representations.get-ajax-data');
        Route::get('diplomatic-representations/{id}/status', [DiplomaticRepresentationController::class, 'status'])->name('diplomatic-representations.status');
        Route::get('diplomatic-representations/{id}/restore', [DiplomaticRepresentationController::class, 'restore'])->name('diplomatic-representations.restore');
        Route::resource('diplomatic-representations', DiplomaticRepresentationController::class);

        //International Help Address Routes
        Route::post('international-help-addresses/get-ajax-data', [InternationalHelpAddressController::class, 'getAjaxData'])->name('international-help-addresses.get-ajax-data');
        Route::get('international-help-addresses/{id}/status', [InternationalHelpAddressController::class, 'status'])->name('international-help-addresses.status');
        Route::resource('international-help-addresses', InternationalHelpAddressController::class);

        //Logistic Partner Routes
        Route::post('logistic-partners/get-ajax-data', [LogisticPartnerController::class, 'getAjaxData'])->name('logistic-partners.get-ajax-data');
        Route::get('logistic-partners/{id}/status', [LogisticPartnerController::class, 'status'])->name('logistic-partners.status');
        Route::resource('logistic-partners', LogisticPartnerController::class);

        //Visa Form Routes
        Route::post('visa-forms/get-ajax-data', [VisaFormController::class, 'getAjaxData'])->name('visa-forms.get-ajax-data');
        Route::get('visa-forms/{id}/status', [VisaFormController::class, 'status'])->name('visa-forms.status');
        Route::resource('visa-forms', VisaFormController::class);

        //Visa Detail Routes
        Route::post('visa-details/get-ajax-data', [VisaDetailController::class, 'getAjaxData'])->name('visa-details.get-ajax-data');
        Route::get('visa-details/{id}/status', [VisaDetailController::class, 'status'])->name('visa-details.status');
        Route::post('visa-details/{id}/add-document', [VisaDetailController::class, 'addDocument'])->name('visa-details.add-document');
        Route::put('visa-details/update-document/{id}', [VisaDetailController::class, 'updateDocument'])->name('visa-details.update-document');
        Route::delete('visa-details/delete-document/{id}', [VisaDetailController::class, 'deleteDocument'])->name('visa-details.delete-document');
        Route::get('visa-details/{id}/get-documents', [VisaDetailController::class, 'getDocuments'])->name('visa-details.get-documents');
        Route::resource('visa-details', VisaDetailController::class);
    });

    //Feature Routes
    Route::post('features/get-ajax-data', [FeatureController::class, 'getAjaxData'])->name('features.get-ajax-data');
    Route::get('features/{id}/status', [FeatureController::class, 'status'])->name('features.status');
    Route::resource('features', FeatureController::class);

    //Case Study Routes
    Route::post('case-studies/get-ajax-data', [CaseStudyController::class, 'getAjaxData'])->name('case-studies.get-ajax-data');
    Route::get('case-studies/{id}/status', [CaseStudyController::class, 'status'])->name('case-studies.status');
    Route::resource('case-studies', CaseStudyController::class);

    //Benefit Routes
    Route::post('benefits/get-ajax-data', [BenefitController::class, 'getAjaxData'])->name('benefits.get-ajax-data');
    Route::get('benefits/{id}/status', [BenefitController::class, 'status'])->name('benefits.status');
    Route::resource('benefits', BenefitController::class);

    //Client Routes
    Route::post('clients/get-ajax-data', [ClientController::class, 'getAjaxData'])->name('clients.get-ajax-data');
    Route::get('clients/{id}/status', [ClientController::class, 'status'])->name('clients.status');
    Route::resource('clients', ClientController::class);

    //Team Routes
    Route::post('teams/get-ajax-data', [TeamController::class, 'getAjaxData'])->name('teams.get-ajax-data');
    Route::get('teams/{id}/status', [TeamController::class, 'status'])->name('teams.status');
    Route::resource('teams', TeamController::class);

    //Get State By Country Route
    Route::get('get-states', [HomeController::class, 'get_states'])->name('get-states');

    //Get Cities By State Route
    Route::get('get-cities', [HomeController::class, 'get_cities'])->name('get-cities');

    //News Letter Routes
    Route::post('news-letters/get-ajax-data', [NewsLetterController::class, 'getAjaxData'])->name('news-letters.get-ajax-data');
    Route::resource('news-letters', NewsLetterController::class);

    //Slider Routes
    Route::post('sliders/get-ajax-data', [SliderController::class, 'getAjaxData'])->name('sliders.get-ajax-data');
    Route::get('sliders/{id}/status', [SliderController::class, 'status'])->name('sliders.status');
    Route::resource('sliders', SliderController::class);

    //Faqs Routes
    Route::post('faqs/get-ajax-data', [FAQController::class, 'getAjaxData'])->name('faqs.get-ajax-data');
    Route::get('faqs/{id}/status', [FAQController::class, 'status'])->name('faqs.status');
    Route::resource('faqs', FAQController::class);

    //Service Routes
    Route::post('services/get-ajax-data', [ServiceController::class, 'getAjaxData'])->name('services.get-ajax-data');
    Route::get('services/{id}/status', [ServiceController::class, 'status'])->name('services.status');
    Route::resource('services', ServiceController::class);

    //Tour Package Routes
    Route::post('tour-packages/get-ajax-data', [TourPackageController::class, 'getAjaxData'])->name('tour-packages.get-ajax-data');
    Route::get('tour-packages/{id}/status', [TourPackageController::class, 'status'])->name('tour-packages.status');
    Route::get('tour-packages/{id}/restore', [TourPackageController::class, 'restore'])->name('tour-packages.restore');
    Route::resource('tour-packages', TourPackageController::class);

    //Counter Routes
    Route::post('counters/get-ajax-data', [CounterController::class, 'getAjaxData'])->name('counters.get-ajax-data');
    Route::get('counters/{id}/status', [CounterController::class, 'status'])->name('counters.status');
    Route::resource('counters', CounterController::class);

    //Testimonials Routes
    Route::post('testimonials/get-ajax-data', [TestimonialController::class, 'getAjaxData'])->name('testimonials.get-ajax-data');
    Route::get('testimonials/{id}/status', [TestimonialController::class, 'status'])->name('testimonials.status');
    Route::resource('testimonials', TestimonialController::class);

    // Boat Widget Enquiries Routes
    Route::post('boat-widget-enquiries/get-ajax-data', [AdminBoatWidgetEnquiryController::class, 'getAjaxData'])->name('boat-widget-enquiries.get-ajax-data');
    Route::post('boat-widget-enquiries/{id}/change-status', [AdminBoatWidgetEnquiryController::class, 'changeStatus'])->name('boat-widget-enquiries.change-status');
    Route::post('boat-widget-enquiries/{id}/assign-user', [AdminBoatWidgetEnquiryController::class, 'assignToUser'])->name('boat-widget-enquiries.assign-user');
    Route::post('boat-widget-enquiries/{id}/add-notes', [AdminBoatWidgetEnquiryController::class, 'addNotes'])->name('boat-widget-enquiries.add-notes');
    Route::post('boat-widget-enquiries/{id}/respond', [AdminBoatWidgetEnquiryController::class, 'respond'])->name('boat-widget-enquiries.respond');
    Route::get('boat-widget-enquiries/statistics', [AdminBoatWidgetEnquiryController::class, 'getStatistics'])->name('boat-widget-enquiries.statistics');
    Route::get('boat-widget-enquiries/export', [AdminBoatWidgetEnquiryController::class, 'export'])->name('boat-widget-enquiries.export');
    Route::resource('boat-widget-enquiries', AdminBoatWidgetEnquiryController::class);

    //Language Translation
    Route::get('index/{locale}', [HomeController::class, 'lang']);
});

// Route::get('/', function () {
//     return redirect('admin');
// })->name('website.index');
Route::get('/', [WebsiteController::class, 'index'])->name('website.index');
Route::get('/about', [WebsiteController::class, 'about'])->name('website.about');
Route::get('/services', [WebsiteController::class, 'services'])->name('website.services');
Route::get('/service-details/{slug}', [WebsiteController::class, 'service_details'])->name('website.service-details');
Route::get('/tours', [WebsiteController::class, 'tours'])->name('website.tours');
Route::get('/tour-details/{slug}', [WebsiteController::class, 'tour_details'])->name('website.tour-details');
Route::get('/tour-package-details/{slug}', [WebsiteController::class, 'tour_package_details'])->name('website.tour-package-details');
Route::get('/contact', [WebsiteController::class, 'contact'])->name('website.contact');
Route::get('/quote', [WebsiteController::class, 'contact'])->name('website.quote');
Route::get('/privacy-policy', [WebsiteController::class, 'privacy_policy'])->name('website.privacy-policy');
Route::get('/blogs', [WebsiteController::class, 'blogs'])->name('website.blogs');
Route::get('/blog-details/{slug}', [WebsiteController::class, 'blog_details'])->name('website.blog-details');
Route::post('/enquiry/store', [WebsiteController::class, 'enquiry_store'])->name('website.enquiry.store');
Route::post('newsletter/subscribe', [WebsiteController::class, 'subscribe_newsletter'])->name('website.newsletter.subscribe');

//Visa information routes
Route::get('/visa-information', [WebsiteController::class, 'visa_information'])->name('website.visa-information');
Route::post('/visa-information/filter', [WebsiteController::class, 'visa_information_filter'])->name('website.visa-information.filter');
Route::post('/visa-information/share', [WebsiteController::class, 'share_visa_info'])->name('website.share-visa-info');
Route::post('/visa-information/download-pdf', [WebsiteController::class, 'download_visa_pdf'])->name('website.download-visa-pdf');
