<?php

namespace App\Utils;

use App\Events\ErrorLogged;
use App\Facades\UtilityFacades;
use App\Models\ActivityLog;
use App\Models\Language;
use App\Models\Notification;
use App\Models\Setting;
use Illuminate\Support\Facades\Log;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Http;
use App\Services\GoogleDriveService;


class Util
{
    static function uploadFile($request, $path = 'uploads')
    {

        $disk = self::getSettingValue('storage_type');
        $file = $request;

        // Generate a random file name with the correct file extension
        $fileName = rand() . '.' . $file->getClientOriginalExtension();

        // Set storage path based on disk
        if ($disk === 'local') {
            $storagePath = $path != 'uploads' ? 'public/uploads/' . $path : 'public/' . $path;

            if (!File::isDirectory(storage_path('app/' . $storagePath))) {
                File::makeDirectory(storage_path('app/' . $storagePath), 0777, true, true);
            }

            // Store file locally
            $filePath = $file->storeAs($storagePath, $fileName);
        } elseif ($disk === 'google') {
            // Resolve GoogleDriveService dynamically
            $storagePath = $path;
            $googleDriveService = app(GoogleDriveService::class);
            $filePath = $googleDriveService->uploadFile($file, $storagePath);
            return $filePath = 'https://drive.google.com/file/d/' . $filePath;
        } else {
            // Store in S3 or Google Drive
            $filePath = $file->storeAs($path, $fileName, $disk);
            // $filePath = Storage::disk('s3')->putFileAs($path, $file, $fileName);
        }

        if (!$filePath) {
            Log::error("File Upload Failed: File not uploaded to {$disk}");
            return;
        } else {
            return Storage::disk($disk)->url($filePath);
        }

        // Return the file path for database storage



    }

    static function unlinkFile($file_url)
    {
        // Check if the file exists in the storage directory and delete it if it does
        if (file_exists(storage_path('app/public/uploads/' . $file_url))) {
            unlink(storage_path('app/public/uploads/' . $file_url));
        }
        return true;
    }

    //Activity Log
    static function activityLog($module, $action, $log_data)
    {
        $agent = new Agent();
        $store_log = new ActivityLog();
        $store_log->module_name = $module;
        $store_log->module_id = $log_data->id;
        $store_log->action = $action;
        $store_log->description = $log_data;
        $store_log->created_by =   Auth::check() ? Auth::user()->id : $log_data->id;
        $store_log->ip_address = request()->ip();
        $store_log->user_agent = request()->header('User-Agent');
        $store_log->browser = $agent->browser();
        $store_log->url = request()->fullUrl();
        $store_log->save();
        return true;
    }

    //Add Column in Table
    static function addColumnToTable($table, $column, $type, $field_options = null)
    {
        if (!Schema::hasColumn($table, $column)) {
            Schema::table($table, function (Blueprint $table) use ($column, $type, $field_options) {
                if ($type == 'select' || $type == 'radio' || $type == 'checkbox') {
                    $table->string($column)->nullable()->after('status')->comment($field_options);
                } else if ($type == 'textarea') {
                    $table->text($column)->nullable()->after('status');
                } else if ($type == 'date') {
                    $table->date($column)->nullable()->after('status');
                } else {
                    $table->string($column)->nullable()->after('status');
                }
            });
        }
        return true;
    }

    //Drop Column in Table
    static function dropColumnFromTable($table, $column)
    {
        if (Schema::hasColumn($table, $column)) {
            Schema::table($table, function (Blueprint $table) use ($column) {
                $table->dropColumn($column);
            });
        }
        return true;
    }

    static function generateErrorLog($e)
    {
        // Log the error
        Log::channel('error_log')->error($e->getMessage(), ['exception' => $e]);
        // Send email notification
        // Mail::to('anilprajapati.0809@gmail.com')->send(new ErrorNotification($e));

        //Dispatch an event
        event(new ErrorLogged());
        return true;
    }

    //Generate Unique Code
    static function generateUniqueCode($prefix, $prefix2 = null)
    {
        $qry = User::query();

        // Check for soft deleted records as well
        $qry->withTrashed()->where(function ($query) use ($prefix, $prefix2) {
            $query->where('unique_code', 'LIKE', '%' . $prefix . '%');
            if ($prefix2) {
                $query->orWhere('unique_code', 'LIKE', '%' . $prefix2 . '%');
            }
        });

        // Get the latest unique code, including soft deleted records
        $usr = $qry->orderBy('unique_code', 'desc')->first();

        // Generate new unique code
        $unique_code = $prefix . '0001';
        if ($usr) {
            // Extract the numeric part and increment
            $existing_id = str_replace($prefix, "", $usr->unique_code);
            $unique_code = $prefix . sprintf("%04d", (int)$existing_id + 1);
        }

        // Restore the query scope to exclude soft deleted records
        $qry->withoutTrashed();

        return $unique_code;
    }

    //Get Icon List
    static function getIconList()
    {
        $icon_lists = array(
            'bx bx-sticker',
            'bx bx-shield-quarter',
            'bx bx-upside-down',
            'bx bx-laugh',
            'bx bx-meh-blank',
            'bx bx-happy-beaming',
            'bx bx-shocked',
            'bx bx-sleepy',
            'bx bx-confused',
            'bx bx-wink-smile',
            'bx bx-dizzy',
            'bx bx-happy-heart-eyes',
            'bx bx-angry',
            'bx bx-smile',
            'bx bx-tired',
            'bx bx-cool',
            'bx bx-happy-alt',
            'bx bx-wink-tongue',
            'bx bx-meh-alt',
            'bx bx-food-menu',
            'bx bx-food-tag',
            'bx bx-female-sign',
            'bx bx-male-sign',
            'bx bx-female',
            'bx bx-male',
            'bx bx-clinic',
            'bx bx-health',
            'bx bx-shekel',
            'bx bx-yen',
            'bx bx-won',
            'bx bx-pound',
            'bx bx-euro',
            'bx bx-rupee',
            'bx bx-ruble',
            'bx bx-lira',
            'bx bx-bitcoin',
            'bx bx-tone',
            'bx bx-bolt-circle',
            'bx bx-cake',
            'bx bx-spa',
            'bx bx-dish',
            'bx bx-fridge',
            'bx bx-image-add',
            'bx bx-image-alt',
            'bx bx-space-bar',
            'bx bx-alarm-add',
            'bx bx-archive-out',
            'bx bx-archive-in',
            'bx bx-add-to-queue',
            'bx bx-border-radius',
            'bx bx-check-shield',
            'bx bx-label',
            'bx bx-file-find',
            'bx bx-face',
            'bx bx-extension',
            'bx bx-exit',
            'bx bx-conversation',
            'bx bx-sort-z-a',
            'bx bx-sort-a-z',
            'bx bx-printer',
            'bx bx-radio',
            'bx bx-customize',
            'bx bx-brush-alt',
            'bx bx-briefcase-alt-2',
            'bx bx-time-five',
            'bx bx-pie-chart-alt-2',
            'bx bx-gas-pump',
            'bx bx-mobile-vibration',
            'bx bx-mobile-landscape',
            'bx bx-border-all',
            'bx bx-border-bottom',
            'bx bx-border-top',
            'bx bx-border-left',
            'bx bx-border-right',
            'bx bx-dialpad-alt',
            'bx bx-filter-alt',
            'bx bx-brightness',
            'bx bx-brightness-half',
            'bx bx-wifi-off',
            'bx bx-credit-card-alt',
            'bx bx-band-aid',
            'bx bx-hive',
            'bx bx-map-pin',
            'bx bx-line-chart',
            'bx bx-receipt',
            'bx bx-purchase-tag-alt',
            'bx bx-basket',
            'bx bx-palette',
            'bx bx-no-entry',
            'bx bx-message-alt-dots',
            'bx bx-message-alt',
            'bx bx-check-square',
            'bx bx-log-out-circle',
            'bx bx-log-in-circle',
            'bx bx-doughnut-chart',
            'bx bx-building-house',
            'bx bx-accessibility',
            'bx bx-user-voice',
            'bx bx-cuboid',
            'bx bx-cube-alt',
            'bx bx-polygon',
            'bx bx-square-rounded',
            'bx bx-square',
            'bx bx-error-alt',
            'bx bx-shield-alt-2',
            'bx bx-paint-roll',
            'bx bx-droplet',
            'bx bx-street-view',
            'bx bx-plus-medical',
            'bx bx-search-alt-2',
            'bx bx-bowling-ball',
            'bx bx-dna',
            'bx bx-cycling',
            'bx bx-shape-circle',
            'bx bx-down-arrow-alt',
            'bx bx-up-arrow-alt',
            'bx bx-right-arrow-alt',
            'bx bx-left-arrow-alt',
            'bx bx-lock-open-alt',
            'bx bx-lock-alt',
            'bx bx-cylinder',
            'bx bx-pyramid',
            'bx bx-comment-dots',
            'bx bx-comment',
            'bx bx-landscape',
            'bx bx-book-open',
            'bx bx-transfer-alt',
            'bx bx-copy-alt',
            'bx bx-run',
            'bx bx-user-pin',
            'bx bx-grid',
            'bx bx-code-alt',
            'bx bx-mail-send',
            'bx bx-ghost',
            'bx bx-shape-triangle',
            'bx bx-shape-square',
            'bx bx-video-recording',
            'bx bx-notepad',
            'bx bx-bug-alt',
            'bx bx-mouse-alt',
            'bx bx-edit-alt',
            'bx bx-chat',
            'bx bx-book-content',
            'bx bx-message-square-dots',
            'bx bx-message-square',
            'bx bx-slideshow',
            'bx bx-wallet-alt',
            'bx bx-memory-card',
            'bx bx-message-rounded-dots',
            'bx bx-message-dots',
            'bx bx-bar-chart-alt-2',
            'bx bx-store-alt',
            'bx bx-buildings',
            'bx bx-home-circle',
            'bx bx-money',
            'bx bx-walk',
            'bx bx-repeat',
            'bx bx-font-family',
            'bx bx-joystick-button',
            'bx bx-paint',
            'bx bx-unlink',
            'bx bx-brush',
            'bx bx-rotate-left',
            'bx bx-badge-check',
            'bx bx-show-alt',
            'bx bx-caret-down',
            'bx bx-caret-right',
            'bx bx-caret-up',
            'bx bx-caret-left',
            'bx bx-calendar-event',
            'bx bx-magnet',
            'bx bx-rewind-circle',
            'bx bx-card',
            'bx bx-help-circle',
            'bx bx-test-tube',
            'bx bx-note',
            'bx bx-sort-down',
            'bx bx-sort-up',
            'bx bx-id-card',
            'bx bx-badge',
            'bx bx-grid-small',
            'bx bx-grid-vertical',
            'bx bx-grid-horizontal',
            'bx bx-move-vertical',
            'bx bx-move-horizontal',
            'bx bx-stats',
            'bx bx-equalizer',
            'bx bx-disc',
            'bx bx-analyse',
            'bx bx-search-alt',
            'bx bx-dollar-circle',
            'bx bx-football',
            'bx bx-ball',
            'bx bx-circle',
            'bx bx-transfer',
            'bx bx-fingerprint',
            'bx bx-font-color',
            'bx bx-highlight',
            'bx bx-file-blank',
            'bx bx-strikethrough',
            'bx bx-photo-album',
            'bx bx-code-block',
            'bx bx-font-size',
            'bx bx-handicap',
            'bx bx-dialpad',
            'bx bx-wind',
            'bx bx-water',
            'bx bx-swim',
            'bx bx-restaurant',
            'bx bx-box',
            'bx bx-menu-alt-right',
            'bx bx-menu-alt-left',
            'bx bx-video-plus',
            'bx bx-list-ol',
            'bx bx-planet',
            'bx bx-hotel',
            'bx bx-movie',
            'bx bx-taxi',
            'bx bx-train',
            'bx bx-bath',
            'bx bx-bed',
            'bx bx-area',
            'bx bx-bot',
            'bx bx-dumbbell',
            'bx bx-check-double',
            'bx bx-bus',
            'bx bx-check-circle',
            'bx bx-rocket',
            'bx bx-certification',
            'bx bx-slider-alt',
            'bx bx-sad',
            'bx bx-meh',
            'bx bx-happy',
            'bx bx-cart-alt',
            'bx bx-car',
            'bx bx-loader-alt',
            'bx bx-loader-circle',
            'bx bx-wrench',
            'bx bx-alarm-off',
            'bx bx-layout',
            'bx bx-dock-left',
            'bx bx-dock-top',
            'bx bx-dock-right',
            'bx bx-dock-bottom',
            'bx bx-dock-bottom',
            'bx bx-world',
            'bx bx-selection',
            'bx bx-paper-plane',
            'bx bx-slider',
            'bx bx-loader',
            'bx bx-chalkboard',
            'bx bx-trash-alt',
            'bx bx-grid-alt',
            'bx bx-command',
            'bx bx-window-close',
            'bx bx-notification-off',
            'bx bx-plug',
            'bx bx-infinite',
            'bx bx-carousel',
            'bx bx-hourglass',
            'bx bx-briefcase-alt',
            'bx bx-wallet',
            'bx bx-station',
            'bx bx-collection',
            'bx bx-tv',
            'bx bx-closet',
            'bx bx-paperclip',
            'bx bx-expand',
            'bx bx-pen',
            'bx bx-purchase-tag',
            'bx bx-images',
            'bx bx-pie-chart-alt',
            'bx bx-news',
            'bx bx-downvote',
            'bx bx-upvote',
            'bx bx-globe-alt',
            'bx bx-store',
            'bx bx-hdd',
            'bx bx-skip-previous-circle',
            'bx bx-skip-next-circle',
            'bx bx-chip',
            'bx bx-cast',
            'bx bx-body',
            'bx bx-phone-outgoing',
            'bx bx-phone-incoming',
            'bx bx-collapse',
            'bx bx-rename',
            'bx bx-rotate-right',
            'bx bx-horizontal-center',
            'bx bx-ruler',
            'bx bx-import',
            'bx bx-calendar-alt',
            'bx bx-battery',
            'bx bx-server',
            'bx bx-task',
            'bx bx-folder-open',
            'bx bx-film',
            'bx bx-aperture',
            'bx bx-phone-call',
            'bx bx-up-arrow',
            'bx bx-undo',
            'bx bx-timer',
            'bx bx-support',
            'bx bx-subdirectory-right',
            'bx bx-right-arrow',
            'bx bx-revision',
            'bx bx-repost',
            'bx bx-reply',
            'bx bx-reply-all',
            'bx bx-redo',
            'bx bx-radar',
            'bx bx-poll',
            'bx bx-list-check',
            'bx bx-like',
            'bx bx-left-arrow',
            'bx bx-joystick-alt',
            'bx bx-history',
            'bx bx-flag',
            'bx bx-first-aid',
            'bx bx-export',
            'bx bx-down-arrow',
            'bx bx-dislike',
            'bx bx-crown',
            'bx bx-barcode',
            'bx bx-user',
            'bx bx-user-x',
            'bx bx-user-plus',
            'bx bx-user-minus',
            'bx bx-user-circle',
            'bx bx-user-check',
            'bx bx-underline',
            'bx bx-trophy',
            'bx bx-trash',
            'bx bx-text',
            'bx bx-sun',
            'bx bx-star',
            'bx bx-sort',
            'bx bx-shuffle',
            'bx bx-shopping-bag',
            'bx bx-shield',
            'bx bx-shield-alt',
            'bx bx-share',
            'bx bx-share-alt',
            'bx bx-select-multiple',
            'bx bx-screenshot',
            'bx bx-save',
            'bx bx-pulse',
            'bx bx-power-off',
            'bx bx-plus',
            'bx bx-pin',
            'bx bx-pencil',
            'bx bx-pin',
            'bx bx-pencil',
            'bx bx-paste',
            'bx bx-paragraph',
            'bx bx-package',
            'bx bx-notification',
            'bx bx-music',
            'bx bx-move',
            'bx bx-mouse',
            'bx bx-minus',
            'bx bx-microphone-off',
            'bx bx-log-out',
            'bx bx-log-in',
            'bx bx-link-external',
            'bx bx-joystick',
            'bx bx-italic',
            'bx bx-home-alt',
            'bx bx-heading',
            'bx bx-hash',
            'bx bx-group',
            'bx bx-git-repo-forked',
            'bx bx-git-pull-request',
            'bx bx-git-merge',
            'bx bx-git-compare',
            'bx bx-git-commit',
            'bx bx-git-branch',
            'bx bx-font',
            'bx bx-filter',
            'bx bx-file',
            'bx bx-edit',
            'bx bx-diamond',
            'bx bx-detail',
            'bx bx-cut',
            'bx bx-cube',
            'bx bx-crop',
            'bx bx-credit-card',
            'bx bx-columns',
            'bx bx-cog',
            'bx bx-cloud-snow',
            'bx bx-cloud-rain',
            'bx bx-cloud-lightning',
            'bx bx-cloud-light-rain',
            'bx bx-cloud-drizzle',
            'bx bx-check',
            'bx bx-cart',
            'bx bx-calculator',
            'bx bx-bold',
            'bx bx-award',
            'bx bx-anchor',
            'bx bx-album',
            'bx bx-adjust',
            'bx bx-x',
            'bx bx-table',
            'bx bx-duplicate',
            'bx bx-windows',
            'bx bx-window',
            'bx bx-window-open',
            'bx bx-wifi',
            'bx bx-voicemail',
            'bx bx-video-off',
            'bx bx-usb',
            'bx bx-upload',
            'bx bx-alarm',
            'bx bx-tennis-ball',
            'bx bx-target-lock',
            'bx bx-tag',
            'bx bx-tab',
            'bx bx-spreadsheet',
            'bx bx-sitemap',
            'bx bx-sidebar',
            'bx bx-send',
            'bx bx-pie-chart',
            'bx bx-phone',
            'bx bx-navigation',
            'bx bx-mobile',
            'bx bx-mobile-alt',
            'bx bx-message',
            'bx bx-message-rounded',
            'bx bx-map',
            'bx bx-map-alt',
            'bx bx-lock',
            'bx bx-lock-open',
            'bx bx-list-minus',
            'bx bx-list-ul',
            'bx bx-list-plus',
            'bx bx-link',
            'bx bx-link-alt',
            'bx bx-layer',
            'bx bx-laptop',
            'bx bx-home',
            'bx bx-heart',
            'bx bx-headphone',
            'bx bx-devices',
            'bx bx-globe',
            'bx bx-gift',
            'bx bx-envelope',
            'bx bx-download',
            'bx bx-dots-vertical',
            'bx bx-dots-vertical-rounded',
            'bx bx-dots-horizontal',
            'bx bx-dots-horizontal-rounded',
            'bx bx-dollar',
            'bx bx-directions',
            'bx bx-desktop',
            'bx bx-data',
            'bx bx-compass',
            'bx bx-crosshair',
            'bx bx-terminal',
            'bx bx-cloud',
            'bx bx-cloud-upload',
            'bx bx-cloud-download',
            'bx bx-chart',
            'bx bx-calendar',
            'bx bx-calendar-x',
            'bx bx-calendar-minus',
            'bx bx-calendar-check',
            'bx bx-calendar-plus',
            'bx bx-buoy',
            'bx bx-bulb',
            'bx bx-bluetooth',
            'bx bx-bug',
            'bx bx-building',
            'bx bx-broadcast',
            'bx bx-briefcase',
            'bx bx-bookmark-plus',
            'bx bx-bookmark-minus',
            'bx bx-book',
            'bx bx-book-bookmark',
            'bx bx-block',
            'bx bx-basketball',
            'bx bx-bar-chart',
            'bx bx-bar-chart-square',
            'bx bx-bar-chart-alt',
            'bx bx-at',
            'bx bx-archive',
            'bx bx-zoom-out',
            'bx bx-zoom-in',
            'bx bx-x-circle',
            'bx bx-volume',
            'bx bx-volume-mute',
            'bx bx-volume-low',
            'bx bx-volume-full',
            'bx bx-video',
            'bx bx-vertical-center',
            'bx bx-up-arrow-circle',
            'bx bx-trending-up',
            'bx bx-trending-down',
            'bx bx-toggle-right',
            'bx bx-toggle-left',
            'bx bx-time',
            'bx bx-sync',
            'bx bx-stopwatch',
            'bx bx-stop',
            'bx bx-stop-circle',
            'bx bx-skip-previous',
            'bx bx-skip-next',
            'bx bx-show',
            'bx bx-search',
            'bx bx-rss',
            'bx bx-right-top-arrow-circle',
            'bx bx-right-indent',
            'bx bx-right-down-arrow-circle',
            'bx bx-right-arrow-circle',
            'bx bx-reset',
            'bx bx-rewind',
            'bx bx-rectangle',
            'bx bx-radio-circle',
            'bx bx-radio-circle-marked',
            'bx bx-question-mark',
            'bx bx-plus-circle',
            'bx bx-play',
            'bx bx-play-circle',
            'bx bx-pause',
            'bx bx-pause-circle',
            'bx bx-moon',
            'bx bx-minus-circle',
            'bx bx-microphone',
            'bx bx-menu',
            'bx bx-left-top-arrow-circle',
            'bx bx-left-indent',
            'bx bx-left-down-arrow-circle',
            'bx bx-left-arrow-circle',
            'bx bx-last-page',
            'bx bx-key',
            'bx bx-align-justify',
            'bx bx-info-circle',
            'bx bx-image',
            'bx bx-hide',
            'bx bx-fullscreen',
            'bx bx-folder',
            'bx bx-folder-plus',
            'bx bx-folder-minus',
            'bx bx-first-page',
            'bx bx-fast-forward',
            'bx bx-fast-forward-circle',
            'bx bx-exit-fullscreen',
            'bx bx-error',
            'bx bx-error-circle',
            'bx bx-down-arrow-circle',
            'bx bx-copyright',
            'bx bx-copy',
            'bx bx-coffee',
            'bx bx-code',
            'bx bx-code-curly',
            'bx bx-clipboard',
            'bx bx-chevrons-left',
            'bx bx-chevrons-right',
            'bx bx-chevrons-up',
            'bx bx-chevrons-down',
            'bx bx-chevron-right',
            'bx bx-chevron-left',
            'bx bx-chevron-up',
            'bx bx-chevron-down',
            'bx bx-checkbox-square',
            'bx bx-checkbox',
            'bx bx-checkbox-checked',
            'bx bx-captions',
            'bx bx-camera',
            'bx bx-camera-off',
            'bx bx-bullseye',
            'bx bx-bookmarks',
            'bx bx-bookmark',
            'bx bx-bell',
            'bx bx-bell-plus',
            'bx bx-bell-off',
            'bx bx-bell-minus',
            'bx bx-arrow-back',
            'bx bx-align-right',
            'bx bx-align-middle',
            'bx bx-align-left'
        );
        return $icon_lists;
    }

    static function getMenuListWithRoute()
    {
        $admin = User::where('role', 'admin')->first();
        $permissions = array(
            array(
                'language_key' => 'Dashboard',
                'menu_name' => 'Dashboard',
                'menu_icon' => 'bx bx-home-circle',
                'route_name' => 'root',
                'table_name' => null,
                'permissions' => array('name' => 'Dashboard', 'permission' => 'view_dashboard'),
                'status' => 1,
                'created_by' => $admin->id,
                'created_by_ip' => request()->ip(),
                'sub_menus' => array(),
            ),

            array(
                'language_key' => 'ManageSliders',
                'menu_name' => 'Manage Sliders',
                'menu_icon' => 'bx bx-carousel',
                'route_name' => 'sliders.index',
                'table_name' => null,
                'permissions' => array(
                    array(
                        'name' => 'View Slider',
                        'permission' => 'sliders.view'
                    ),
                    array(
                        'name' => 'List',
                        'permission' => 'sliders.list'
                    ),
                    array(
                        'name' => 'Add',
                        'permission' => 'sliders.add'
                    ),
                    array(
                        'name' => 'Edit',
                        'permission' => 'sliders.edit'
                    ),
                    array(
                        'name' => 'Delete',
                        'permission' => 'sliders.delete'
                    ),
                    array(
                        'name' => 'Status',
                        'permission' => 'sliders.status'
                    ),
                ),
                'status' => 1,
                'created_by' => $admin->id,
                'created_by_ip' => request()->ip(),
                'sub_menus' => array(),
            ),

            array(
                'language_key' => 'ManageFeatures',
                'menu_name' => 'Manage Features',
                'menu_icon' => 'bx bx-bulb',
                'route_name' => 'features.index',
                'table_name' => 'features',
                'permissions' =>  array(
                    array(
                        'name' => 'View Features',
                        'permission' => 'features.view'
                    ),
                    array(
                        'name' => 'List',
                        'permission' => 'features.list'
                    ),
                    array(
                        'name' => 'Add',
                        'permission' => 'features.add'
                    ),
                    array(
                        'name' => 'Edit',
                        'permission' => 'features.edit'
                    ),
                    array(
                        'name' => 'Delete',
                        'permission' => 'features.delete'
                    ),
                    array(
                        'name' => 'Status',
                        'permission' => 'features.status'
                    ),
                ),
                'status' => 1,
                'created_by' => $admin->id,
                'created_by_ip' => request()->ip(),
                'sub_menus' => array(),
            ),

            array(
                'language_key' => 'ManageServices',
                'menu_name' => 'Manage Services',
                'menu_icon' => 'bx bx-cog',
                'route_name' => 'services.index',
                'table_name' => 'services',
                'permissions' => array(
                    array(
                        'name' => 'View Services',
                        'permission' => 'services.view'
                    ),
                    array(
                        'name' => 'List',
                        'permission' => 'services.list'
                    ),
                    array(
                        'name' => 'Add',
                        'permission' => 'services.add'
                    ),
                    array(
                        'name' => 'Edit',
                        'permission' => 'services.edit'
                    ),
                    array(
                        'name' => 'Delete',
                        'permission' => 'services.delete'
                    ),
                    array(
                        'name' => 'Status',
                        'permission' => 'services.status'
                    ),
                ),
                'status' => 1,
                'created_by' => $admin->id,
                'created_by_ip' => request()->ip(),
                'sub_menus' => array(),
            ),

            array(
                'language_key' => 'ManageCaseStudies',
                'menu_name' => 'Manage Case Studies',
                'menu_icon' => 'bx bx-briefcase',
                'route_name' => 'case-studies.index',
                'table_name' => 'case_studies',
                'permissions' => array(
                    array(
                        'name' => 'View Case Studies',
                        'permission' => 'case-studies.view'
                    ),
                    array(
                        'name' => 'List',
                        'permission' => 'case-studies.list'
                    ),
                    array(
                        'name' => 'Add',
                        'permission' => 'case-studies.add'
                    ),
                    array(
                        'name' => 'Edit',
                        'permission' => 'case-studies.edit'
                    ),
                    array(
                        'name' => 'Delete',
                        'permission' => 'case-studies.delete'
                    ),
                    array(
                        'name' => 'Status',
                        'permission' => 'case-studies.status'
                    ),
                ),
                'status' => 1,
                'created_by' => $admin->id,
                'created_by_ip' => request()->ip(),
                'sub_menus' => array(),
            ),

            array(
                'language_key' => 'ManageTourPackages',
                'menu_name' => 'Manage Tour Packages',
                'menu_icon' => 'bx bx-package',
                'route_name' => 'tour-packages.index',
                'table_name' => 'tour_packages',
                'permissions' => array(
                    array(
                        'name' => 'View Tour Packages',
                        'permission' => 'tour-packages.view'
                    ),
                    array(
                        'name' => 'List',
                        'permission' => 'tour-packages.list'
                    ),
                    array(
                        'name' => 'Add',
                        'permission' => 'tour-packages.add'
                    ),
                    array(
                        'name' => 'Edit',
                        'permission' => 'tour-packages.edit'
                    ),
                    array(
                        'name' => 'Delete',
                        'permission' => 'tour-packages.delete'
                    ),
                    array(
                        'name' => 'Status',
                        'permission' => 'tour-packages.status'
                    ),
                ),
                'status' => 1,
                'created_by' => $admin->id,
                'created_by_ip' => request()->ip(),
                'sub_menus' => array(),
            ),

            array(
                'language_key' => 'ManageCounters',
                'menu_name' => 'Manage Counters',
                'menu_icon' => 'bx bx-map',
                'route_name' => 'counters.index',
                'table_name' => 'counters',
                'permissions' => array(
                    array(
                        'name' => 'View Counters',
                        'permission' => 'counters.view'
                    ),
                    array(
                        'name' => 'List',
                        'permission' => 'counters.list'
                    ),
                    array(
                        'name' => 'Add',
                        'permission' => 'counters.add'
                    ),
                    array(
                        'name' => 'Edit',
                        'permission' => 'counters.edit'
                    ),
                    array(
                        'name' => 'Delete',
                        'permission' => 'counters.delete'
                    ),
                    array(
                        'name' => 'Status',
                        'permission' => 'counters.status'
                    ),
                ),
                'status' => 1,
                'created_by' => $admin->id,
                'created_by_ip' => request()->ip(),
                'sub_menus' => array(),
            ),

            array(
                'language_key' => 'WhyChooseUs',
                'menu_name' => 'Why Choose Us',
                'menu_icon' => 'bx bx-star',
                'route_name' => 'benefits.index',
                'table_name' => 'benefits',
                'permissions' => array(
                    array(
                        'name' => 'View Why Choose Us',
                        'permission' => 'benefits.view'
                    ),
                    array(
                        'name' => 'List',
                        'permission' => 'benefits.list'
                    ),
                    array(
                        'name' => 'Add',
                        'permission' => 'benefits.add'
                    ),
                    array(
                        'name' => 'Edit',
                        'permission' => 'benefits.edit'
                    ),
                    array(
                        'name' => 'Delete',
                        'permission' => 'benefits.delete'
                    ),
                    array(
                        'name' => 'Status',
                        'permission' => 'benefits.status'
                    ),
                ),
                'status' => 1,
                'created_by' => $admin->id,
                'created_by_ip' => request()->ip(),
                'sub_menus' => array(),
            ),

            array(
                'language_key' => 'ManageBlogs',
                'menu_name' => 'Manage Blogs',
                'menu_icon' => 'bx bx-news',
                'route_name' => 'javascript:void(0)',
                'table_name' => null,
                'permissions' => array(
                    'name' => 'View Blogs',
                    'permission' => 'manage_blogs.view'
                ),
                'status' => 1,
                'created_by' => $admin->id,
                'created_by_ip' => request()->ip(),
                'sub_menus' => array(
                    array(
                        'language_key' => 'BlogCategoryList',
                        'menu_name' => 'Blog Category List',
                        'menu_icon' => null,
                        'route_name' => 'manage-blogs.blog-categories.index',
                        'table_name' => 'blog_categories',
                        'permissions' => array(
                            array(
                                'name' => 'List',
                                'permission' => 'manage_blogs.blog_categories.list'
                            ),
                            array(
                                'name' => 'Add',
                                'permission' => 'manage_blogs.blog_categories.add'
                            ),
                            array(
                                'name' => 'Edit',
                                'permission' => 'manage_blogs.blog_categories.edit'
                            ),
                            array(
                                'name' => 'Delete',
                                'permission' => 'manage_blogs.blog_categories.delete'
                            ),
                            array(
                                'name' => 'Status',
                                'permission' => 'manage_blogs.blog_categories.status'
                            ),
                        ),
                        'status' => 1,
                        'created_by' => $admin->id,
                        'created_by_ip' => request()->ip(),
                    ),
                    array(
                        'language_key' => 'BlogList',
                        'menu_name' => 'Blog List',
                        'menu_icon' => null,
                        'route_name' => 'manage-blogs.blogs.index',
                        'table_name' => 'blogs',
                        'permissions' => array(
                            array(
                                'name' => 'List',
                                'permission' => 'manage_blogs.blogs.list'
                            ),
                            array(
                                'name' => 'Add',
                                'permission' => 'manage_blogs.blogs.add'
                            ),
                            array(
                                'name' => 'Edit',
                                'permission' => 'manage_blogs.blogs.edit'
                            ),
                            array(
                                'name' => 'Delete',
                                'permission' => 'manage_blogs.blogs.delete'
                            ),
                            array(
                                'name' => 'Status',
                                'permission' => 'manage_blogs.blogs.status'
                            ),
                        ),
                        'status' => 1,
                        'created_by' => $admin->id,
                        'created_by_ip' => request()->ip(),
                    ),
                ),
            ),

            array(
                'language_key' => 'ManageVisa',
                'menu_name' => 'Manage Visa',
                'menu_icon' => 'bx bx-id-card',
                'route_name' => 'javascript:void(0)',
                'table_name' => null,
                'permissions' => array(
                    'name' => 'View Visa',
                    'permission' => 'manage_visa.view'
                ),
                'status' => 1,
                'created_by' => $admin->id,
                'created_by_ip' => request()->ip(),
                'sub_menus' => array(
                    array(
                        'language_key' => 'VisaCategoryList',
                        'menu_name' => 'Visa Category List',
                        'menu_icon' => null,
                        'route_name' => 'manage-visa.visa-categories.index',
                        'table_name' => 'visa_categories',
                        'permissions' => array(
                            array(
                                'name' => 'List',
                                'permission' => 'manage_visa.visa_categories.list'
                            ),
                            array(
                                'name' => 'Add',
                                'permission' => 'manage_visa.visa_categories.add'
                            ),
                            array(
                                'name' => 'Edit',
                                'permission' => 'manage_visa.visa_categories.edit'
                            ),
                            array(
                                'name' => 'Delete',
                                'permission' => 'manage_visa.visa_categories.delete'
                            ),
                            array(
                                'name' => 'Status',
                                'permission' => 'manage_visa.visa_categories.status'
                            ),
                        ),
                        'status' => 1,
                        'created_by' => $admin->id,
                        'created_by_ip' => request()->ip(),
                    ),
                    array(
                        'language_key' => 'DiplomaticRepresentationList',
                        'menu_name' => 'Diplomatic Representation List',
                        'menu_icon' => null,
                        'route_name' => 'manage-visa.diplomatic-representations.index',
                        'table_name' => 'diplomatic_representations',
                        'permissions' => array(
                            array(
                                'name' => 'List',
                                'permission' => 'manage_visa.diplomatic_representations.list'
                            ),
                            array(
                                'name' => 'Add',
                                'permission' => 'manage_visa.diplomatic_representations.add'
                            ),
                            array(
                                'name' => 'Edit',
                                'permission' => 'manage_visa.diplomatic_representations.edit'
                            ),
                            array(
                                'name' => 'Delete',
                                'permission' => 'manage_visa.diplomatic_representations.delete'
                            ),
                            array(
                                'name' => 'Status',
                                'permission' => 'manage_visa.diplomatic_representations.status'
                            ),
                        ),
                        'status' => 1,
                        'created_by' => $admin->id,
                        'created_by_ip' => request()->ip(),
                    ),
                    array(
                        'language_key' => 'VisaInformationList',
                        'menu_name' => 'Visa Information List',
                        'menu_icon' => null,
                        'route_name' => 'manage-visa.visa-information.index',
                        'table_name' => 'visa_information',
                        'permissions' => array(
                            array(
                                'name' => 'List',
                                'permission' => 'manage_visa.visa_information.list'
                            ),
                            array(
                                'name' => 'Add',
                                'permission' => 'manage_visa.visa_information.add'
                            ),
                            array(
                                'name' => 'Edit',
                                'permission' => 'manage_visa.visa_information.edit'
                            ),
                            array(
                                'name' => 'Delete',
                                'permission' => 'manage_visa.visa_information.delete'
                            ),
                            array(
                                'name' => 'Status',
                                'permission' => 'manage_visa.visa_information.status'
                            ),
                        ),
                        'status' => 1,
                        'created_by' => $admin->id,
                        'created_by_ip' => request()->ip(),
                    ),
                    array(
                        'language_key' => 'InternationalHelpAddressList',
                        'menu_name' => 'International Help Address',
                        'menu_icon' => null,
                        'route_name' => 'manage-visa.international-help-addresses.index',
                        'table_name' => 'international_help_addresses',
                        'permissions' => array(
                            array(
                                'name' => 'List',
                                'permission' => 'manage_visa.international-help-addresses.list'
                            ),
                            array(
                                'name' => 'Add',
                                'permission' => 'manage_visa.international-help-addresses.add'
                            ),
                            array(
                                'name' => 'Edit',
                                'permission' => 'manage_visa.international-help-addresses.edit'
                            ),
                            array(
                                'name' => 'Delete',
                                'permission' => 'manage_visa.international-help-addresses.delete'
                            ),
                            array(
                                'name' => 'Status',
                                'permission' => 'manage_visa.international-help-addresses.status'
                            ),
                        ),
                        'status' => 1,
                        'created_by' => $admin->id,
                        'created_by_ip' => request()->ip(),
                    ),
                    array(
                        'language_key' => 'LogisticPartnerList',
                        'menu_name' => 'Logistic Partner List',
                        'menu_icon' => null,
                        'route_name' => 'manage-visa.logistic-partners.index',
                        'table_name' => 'logistic_partners',
                        'permissions' => array(
                            array(
                                'name' => 'List',
                                'permission' => 'manage_visa.logistic_partners.list'
                            ),
                            array(
                                'name' => 'Add',
                                'permission' => 'manage_visa.logistic_partners.add'
                            ),
                            array(
                                'name' => 'Edit',
                                'permission' => 'manage_visa.logistic_partners.edit'
                            ),
                            array(
                                'name' => 'Delete',
                                'permission' => 'manage_visa.logistic_partners.delete'
                            ),
                            array(
                                'name' => 'Status',
                                'permission' => 'manage_visa.logistic_partners.status'
                            ),
                        ),
                        'status' => 1,
                        'created_by' => $admin->id,
                        'created_by_ip' => request()->ip(),
                    ),
                    array(
                        'language_key' => 'VisaFormList',
                        'menu_name' => 'Visa Form List',
                        'menu_icon' => null,
                        'route_name' => 'manage-visa.visa-forms.index',
                        'table_name' => 'visa_forms',
                        'permissions' => array(
                            array(
                                'name' => 'List',
                                'permission' => 'manage_visa.visa_forms.list'
                            ),
                            array(
                                'name' => 'Add',
                                'permission' => 'manage_visa.visa_forms.add'
                            ),
                            array(
                                'name' => 'Edit',
                                'permission' => 'manage_visa.visa_forms.edit'
                            ),
                            array(
                                'name' => 'Delete',
                                'permission' => 'manage_visa.visa_forms.delete'
                            ),
                            array(
                                'name' => 'Status',
                                'permission' => 'manage_visa.visa_forms.status'
                            ),
                        ),
                        'status' => 1,
                        'created_by' => $admin->id,
                        'created_by_ip' => request()->ip(),
                    ),
                    array(
                        'language_key' => 'VisaDetailList',
                        'menu_name' => 'Visa Detail List',
                        'menu_icon' => null,
                        'route_name' => 'manage-visa.visa-details.index',
                        'table_name' => 'visa_details',
                        'permissions' => array(
                            array(
                                'name' => 'List',
                                'permission' => 'manage_visa.visa_details.list'
                            ),
                            array(
                                'name' => 'Add',
                                'permission' => 'manage_visa.visa_details.add'
                            ),
                            array(
                                'name' => 'Edit',
                                'permission' => 'manage_visa.visa_details.edit'
                            ),
                            array(
                                'name' => 'Delete',
                                'permission' => 'manage_visa.visa_details.delete'
                            ),
                            array(
                                'name' => 'Status',
                                'permission' => 'manage_visa.visa_details.status'
                            ),
                        ),
                        'status' => 1,
                        'created_by' => $admin->id,
                        'created_by_ip' => request()->ip(),
                    ),
                ),
            ),

            array(
                'language_key' => 'ManageTestimonials',
                'menu_name' => 'Manage Testimonials',
                'menu_icon' => 'bx bx-message-rounded-dots',
                'route_name' => 'testimonials.index',
                'table_name' => 'testimonials',
                'permissions' => array(
                    array(
                        'name' => 'View Testimonials',
                        'permission' => 'testimonials.view'
                    ),
                    array(
                        'name' => 'List',
                        'permission' => 'testimonials.list'
                    ),
                    array(
                        'name' => 'Add',
                        'permission' => 'testimonials.add'
                    ),
                    array(
                        'name' => 'Edit',
                        'permission' => 'testimonials.edit'
                    ),
                    array(
                        'name' => 'Delete',
                        'permission' => 'testimonials.delete'
                    ),
                    array(
                        'name' => 'Status',
                        'permission' => 'testimonials.status'
                    ),
                ),
                'status' => 1,
                'created_by' => $admin->id,
                'created_by_ip' => request()->ip(),
                'sub_menus' => array(),
            ),

            array(
                'language_key' => 'ManageTeams',
                'menu_name' => 'Manage Teams',
                'menu_icon' => 'bx bx-user-check',
                'route_name' => 'teams.index',
                'table_name' => 'teams',
                'permissions' => array(
                    array(
                        'name' => 'View Teams',
                        'permission' => 'teams.view'
                    ),
                    array(
                        'name' => 'List',
                        'permission' => 'teams.list'
                    ),
                    array(
                        'name' => 'Add',
                        'permission' => 'teams.add'
                    ),
                    array(
                        'name' => 'Edit',
                        'permission' => 'teams.edit'
                    ),
                    array(
                        'name' => 'Delete',
                        'permission' => 'teams.delete'
                    ),
                    array(
                        'name' => 'Status',
                        'permission' => 'teams.status'
                    ),
                ),
                'status' => 1,
                'created_by' => $admin->id,
                'created_by_ip' => request()->ip(),
                'sub_menus' => array(),
            ),

            array(
                'language_key' => 'ManageClients',
                'menu_name' => 'Manage Clients',
                'menu_icon' => 'bx bx-user-check',
                'route_name' => 'clients.index',
                'table_name' => 'clients',
                'permissions' => array(
                    array(
                        'name' => 'View Clients',
                        'permission' => 'clients.view'
                    ),
                    array(
                        'name' => 'List',
                        'permission' => 'clients.list'
                    ),
                    array(
                        'name' => 'Add',
                        'permission' => 'clients.add'
                    ),
                    array(
                        'name' => 'Edit',
                        'permission' => 'clients.edit'
                    ),
                    array(
                        'name' => 'Delete',
                        'permission' => 'clients.delete'
                    ),
                    array(
                        'name' => 'Status',
                        'permission' => 'clients.status'
                    ),
                ),
                'status' => 1,
                'created_by' => $admin->id,
                'created_by_ip' => request()->ip(),
                'sub_menus' => array(),
            ),

            array(
                'language_key' => 'ManageFaqs',
                'menu_name' => 'Manage FAQs',
                'menu_icon' => 'bx bx-help-circle',
                'route_name' => 'faqs.index',
                'table_name' => 'faqs',
                'permissions' => array(
                    array(
                        'name' => 'View FAQs',
                        'permission' => 'faqs.view'
                    ),
                    array(
                        'name' => 'List',
                        'permission' => 'faqs.list'
                    ),
                    array(
                        'name' => 'Add',
                        'permission' => 'faqs.add'
                    ),
                    array(
                        'name' => 'Edit',
                        'permission' => 'faqs.edit'
                    ),
                    array(
                        'name' => 'Delete',
                        'permission' => 'faqs.delete'
                    ),
                    array(
                        'name' => 'Status',
                        'permission' => 'faqs.status'
                    ),
                ),
                'status' => 1,
                'created_by' => $admin->id,
                'created_by_ip' => request()->ip(),
                'sub_menus' => array(),
            ),
            array(
                'language_key' => 'Enquiries',
                'menu_name' => 'Enquiries',
                'menu_icon' => 'bx bx-phone-incoming',
                'route_name' => 'enquiries.index',
                'table_name' => 'enquiries',
                'permissions' => array(
                    array(
                        'name' => 'View Enquiries',
                        'permission' => 'enquiries.view'
                    ),
                    array(
                        'name' => 'List',
                        'permission' => 'enquiries.list'
                    ),
                    array(
                        'name' => 'Delete',
                        'permission' => 'enquiries.delete'
                    ),
                ),
                'status' => 1,
                'created_by' => $admin->id,
                'created_by_ip' => request()->ip(),
                'sub_menus' => array(),
            ),

            array(
                'language_key' => 'ServiceEnquiries',
                'menu_name' => 'Service Enquiries',
                'menu_icon' => 'bx bx-phone-incoming',
                'route_name' => 'enquiries.services',
                'table_name' => 'enquiries',
                'permissions' => array(
                    array(
                        'name' => 'View Service Enquiries',
                        'permission' => 'service-enquiries.view'
                    ),
                    array(
                        'name' => 'List',
                        'permission' => 'service-enquiries.list'
                    ),
                    array(
                        'name' => 'Delete',
                        'permission' => 'service-enquiries.delete'
                    ),
                ),
                'status' => 1,
                'created_by' => $admin->id,
                'created_by_ip' => request()->ip(),
                'sub_menus' => array(),
            ),

            array(
                'language_key' => 'TourEnquiries',
                'menu_name' => 'Tour Enquiries',
                'menu_icon' => 'bx bx-phone-incoming',
                'route_name' => 'enquiries.tours',
                'table_name' => 'enquiries',
                'permissions' => array(
                    array(
                        'name' => 'View Tour Enquiries',
                        'permission' => 'tour-enquiries.view'
                    ),
                    array(
                        'name' => 'List',
                        'permission' => 'tour-enquiries.list'
                    ),
                    array(
                        'name' => 'Delete',
                        'permission' => 'tour-enquiries.delete'
                    ),
                ),
                'status' => 1,
                'created_by' => $admin->id,
                'created_by_ip' => request()->ip(),
                'sub_menus' => array(),
            ),

            array(
                'language_key' => 'AccountSettings',
                'menu_name' => 'Account Settings',
                'menu_icon' => 'bx bx-wrench',
                'route_name' => 'javascript:void(0)',
                'table_name' => null,
                'permissions' => array('name' => 'View Account Settings', 'permission' => 'settings.manage-account-settings'),
                'status' => 1,
                'created_by' => $admin->id,
                'created_by_ip' => request()->ip(),
                'sub_menus' => array(
                    array(
                        'language_key' => 'AboutUs',
                        'menu_name' => 'About Us',
                        'menu_icon' => null,
                        'route_name' => 'settings.about-us.index',
                        'table_name' => 'about_us',
                        'permissions' => array(
                            array(
                                'name' => 'About Us',
                                'permission' => 'settings.about-us.view'
                            ),
                            array(
                                'name' => 'Add',
                                'permission' => 'settings.about-us.add'
                            ),
                            array(
                                'name' => 'Edit',
                                'permission' => 'settings.about-us.edit'
                            ),
                            array(
                                'name' => 'Status',
                                'permission' => 'settings.about-us.status'
                            ),
                        ),
                        'status' => 1,
                        'created_by' => $admin->id,
                        'created_by_ip' => request()->ip(),
                    ),
                    array(
                        'language_key' => 'ContactUs',
                        'menu_name' => 'Contact Us',
                        'menu_icon' => null,
                        'route_name' => 'settings.contacts.index',
                        'table_name' => 'contacts',
                        'permissions' => array(
                            array(
                                'name' => 'Contact Us',
                                'permission' => 'settings.contacts.view'
                            ),
                            array(
                                'name' => 'Add',
                                'permission' => 'settings.contacts.add'
                            ),
                            array(
                                'name' => 'Edit',
                                'permission' => 'settings.contacts.edit'
                            ),
                            array(
                                'name' => 'Status',
                                'permission' => 'settings.contacts.status'
                            ),
                        ),
                        'status' => 1,
                        'created_by' => $admin->id,
                        'created_by_ip' => request()->ip(),
                    ),

                    array(
                        'language_key' => 'Settings',
                        'menu_name' => 'Settings',
                        'menu_icon' => null,
                        'route_name' => 'settings.index',
                        'table_name' => 'settings',
                        'permissions' => array(
                            array(
                                'name' => 'Settings',
                                'permission' => 'settings.app-setting'
                            ),
                            array(
                                'name' => 'Email Settings',
                                'permission' => 'settings.email-setting'
                            ),
                        ),
                        'status' => 1,
                        'created_by' => $admin->id,
                        'created_by_ip' => request()->ip()
                    ),
                ),
            ),

            array(
                'language_key' => 'NewsLetter',
                'menu_name' => 'News Letter',
                'menu_icon' => 'bx bx-envelope',
                'route_name' => 'news-letters.index',
                'table_name' => 'news_letters',
                'permissions' => array(
                    array(
                        'name' => 'View News Letter',
                        'permission' => 'news_letters.view'
                    ),
                    array(
                        'name' => 'List',
                        'permission' => 'news_letters.list'
                    ),
                    array(
                        'name' => 'Delete',
                        'permission' => 'news_letters.delete'
                    ),
                ),
                'status' => 1,
                'created_by' => $admin->id,
                'created_by_ip' => request()->ip(),
                'sub_menus' => array(),
            ),
        );
        return $permissions;
    }

    //Get Custom Field Table Name
    static function getCustomFieldTableNames()
    {
        return ['activity_logs', 'roles', 'permissions', 'users', 'main_menus', 'sub_menus', 'custom_fields', 'settings', 'custom_links'];
    }

    //Except Tables
    static function exceptTables()
    {
        // return ['languages', 'custom_links', 'custom_fields'];
        return [];
    }

    //Update Data in .env file
    static function updateEnvFile($env_data = [])
    {

        if (count($env_data) > 0) {
            $envContent = file_get_contents(base_path('.env'));
            foreach ($env_data as $env_key => $env_value) {
                if (in_array($env_key, ['MAIL_FROM_ADDRESS', 'MAIL_FROM_NAME'])) {
                    $envContent = str_replace($env_key . '="' . env($env_key) . '"', $env_key . '=' . $env_value, $envContent);
                } else {
                    $envContent = str_replace($env_key . '=' . env($env_key), $env_key . '=' . $env_value, $envContent);
                }
                file_put_contents(base_path('.env'), $envContent);
            }
        }
    }

    static function maskEmail($email)
    {
        $emailParts = explode("@", $email);
        $name = $emailParts[0];
        $domain = $emailParts[1];

        // Masking: Show first and last character, replace middle with '*'
        if (strlen($name) > 2) {
            $maskedName = substr($name, 0, 1) . str_repeat('*', strlen($name) - 2) . substr($name, -1);
        } else {
            $maskedName = str_repeat('*', strlen($name)); // If too short, mask all
        }

        return $maskedName . "@" . $domain;
    }

    static function maskMobile($mobile)
    {
        return str_repeat('*', 6) . substr($mobile, -4);
    }

    //Get Setting Value
    static function getSettingValue($key)
    {
        return Setting::where('key', $key)->first()->value ?? '';
    }

    //Store Notification
    static function storeNotification($user_id, $title, $description, $type)
    {
        $notification = new Notification();
        $notification->user_id = $user_id;
        $notification->title = $title;
        $notification->description = $description;
        $notification->type = $type;
        $notification->created_by = Auth::user()->id;
        $notification->save();
    }

    //Update Language File
    static function updateLanguageFile($language_data)
    {

        $openai_status = UtilityFacades::getsettings('openai_status');
        $openai_api_key = UtilityFacades::getsettings('openai_api_key');
        if ($openai_status == 'on' || !empty($openai_api_key)) {
            // Read English translations
            $english_translations = include resource_path('lang/en/translation.php');

            $language_key = $language_data['language_key'];
            $updated_value = $language_data['menu_name'];

            //Check if key exists then update the value otherwise add new key
            if (array_key_exists($language_key, $english_translations)) {
                $english_translations[$language_key] = $updated_value;
            } else {
                $english_translations[$language_key] = $updated_value;
            }

            // Update English language file
            $english_content = "<?php\n\nreturn " . var_export($english_translations, true) . ";\n";
            file_put_contents(resource_path('lang/en/translation.php'), $english_content);

            // Initialize variables
            $apiKey = env('OPENAI_API_KEY') ?? $openai_api_key;
            ini_set('max_execution_time', 300);
            ini_set('memory_limit', '512M');

            // Translate English to selected language
            $active_languages = Language::where('status', 1)->get();
            $jsonData = json_encode(array($language_key => $updated_value), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            foreach ($active_languages as $language) {
                $language_code = $language->iso_code;
                $language_file = resource_path('lang/' . $language_code . '/translation.php');

                $message = "Translate the following JSON from English to $language_code.
                        Return only a valid JSON object, with no additional text:
                        ```json\n$$jsonData\n```";
                // Send request to OpenAI
                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'Authorization' => "Bearer $apiKey",
                ])->post('https://api.openai.com/v1/chat/completions', [
                    'model' => 'gpt-4',
                    'messages' => [
                        ['role' => 'system', 'content' => 'You are a translation assistant. Only return valid JSON, no explanations.'],
                        ['role' => 'user', 'content' => $message]
                    ],
                    'temperature' => 0.3,
                    'max_tokens' => 2048,
                ]);

                // Check if response is successful
                if ($response->successful()) {

                    // Extract translated JSON response
                    $translatedData = $response->json()['choices'][0]['message']['content'] ?? null;
                    if ($translatedData) {

                        // Clean up possible Markdown JSON formatting
                        $translatedData = trim($translatedData, "```json\n");

                        // Ensure valid JSON
                        $chunk_translation = json_decode($translatedData, true);

                        //Now update the language file with the translated data and save it
                        $language_translations = include $language_file;
                        $language_translations[$language_key] = $chunk_translation[$language_key];
                        $language_content = "<?php\n\nreturn " . var_export($language_translations, true) . ";\n";
                        file_put_contents($language_file, $language_content);
                    }
                }
            }
            return true;
        } else {
            $english_translations = include resource_path('lang/en/translation.php');

            $language_key = $language_data['language_key'];
            $updated_value = $language_data['menu_name'];

            // Check if key exists then update the value otherwise add new key
            if (array_key_exists($language_key, $english_translations)) {
                $english_translations[$language_key] = $updated_value;
            } else {
                $english_translations[$language_key] = $updated_value;
            }

            // Update English language file
            $english_content = "<?php\n\nreturn " . var_export($english_translations, true) . ";\n";
            file_put_contents(resource_path('lang/en/translation.php'), $english_content);

            return false;
        }
    }
}
