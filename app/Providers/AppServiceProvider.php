<?php

namespace App\Providers;

use App\Models\BlogCategory;
use App\Models\Contact;
use App\Models\Country;
use App\Models\MainMenu;
use App\Models\Service;
use App\Models\Setting;
use Google\Cloud\Storage\StorageClient;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Filesystem;
use League\Flysystem\GoogleCloudStorage\GoogleCloudStorageAdapter;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);
        // Register GCS driver manually
        Storage::extend('gcs', function ($app, $config) {
            $storageClient = new StorageClient([
                'projectId' => $config['project_id'],
                'keyFilePath' => $config['key_file'],
            ]);

            $bucket = $storageClient->bucket($config['bucket']);
            $adapter = new GoogleCloudStorageAdapter($bucket);

            return new FilesystemAdapter(new Filesystem($adapter), $adapter, $config);
        });

        view()->composer('*', function ($view) {
            $settings = (object)[];
            $menus = (object)[];
            $contact = Contact::first();
            $contact->app_name = Setting::where('key', 'app_name')->first()->value ?? config('app.name');
            $contact->app_logo = Setting::where('key', 'app_logo')->first()->value;
            $blog_categories = BlogCategory::where('status', 1)->orderBy('name')->get();
            $tour_countries = []; // Tour packages don't have country filtering
            $footer_services = Service::where('status', 1)->orderBy('title', 'asc')->take(6)->get(['title', 'slug']);

            try {
                // Check if the database connection is available
                DB::connection()->getPdo();

                if (Auth::check()) {

                    $settings = Setting::get()->toArray();
                    $menus = MainMenu::with(['sub_menus' => function ($q) {
                        $q->whereNotIn('language_key', ['RoleMaster', 'ClientRegistration'])->orderBy('order', 'ASC');
                    }])->whereNotIn('language_key', ['ManageFAQs', 'ManageFeatures', 'ManageCaseStudies', 'ManageClients'])->where('status', 1)->orderBy('order', 'ASC')->get()->toArray();
                }
            } catch (\Exception $e) {
                // Log error but continue execution to avoid app crash
                Log::error('Database connection failed in AppServiceProvider: ' . $e->getMessage());
            }

            $view->with([
                'settings' => $settings,
                'menus' => $menus,
                'contact' => $contact,
                'blog_categories' => $blog_categories,
                'tour_countries' => $tour_countries,
                'footer_services' => $footer_services,
            ]);

            app()->instance('settings', $settings);
            app()->instance('menus', $menus);
            app()->instance('contact', $contact);
            app()->instance('blog_categories', $blog_categories);
            app()->instance('tour_countries', $tour_countries);
        });
    }
}
