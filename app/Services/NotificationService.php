<?php

namespace App\Services;

use App\Mail\NotificationEmail;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendEmailService;
use App\Models\NotificationSetting;
use App\Models\User;
use App\Services\WhatsAppService;
use App\Services\SMSService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class NotificationService
{
    /**
     * Send notifications based on role permissions.
     *
     * @param int $entity_id (Product ID, Order ID, etc.)
     * @param string $event_type ('add_product', 'edit_product', 'delete_product')
     */
    public static function sendNotification($entity_id, $event_type,$details=array())
    { 
          
        // Get notification settings for this event type
        $notification = NotificationSetting::where('slug', $event_type)->first();
        $email_notification = $notification->email_notification;
        $sms_notification = $notification->sms_notification;
        $whatsapp_notification = $notification->whatsapp_notification;

      
        if (!$notification) {
            return; // No notification setting found, skip
        }

        $permission = [];
        $email_users = [];
        $sms_users = [];
        $whatsapp_users = [];

        // check user for email 
        if($email_notification){
            $permission_email = Permission::where('name', ['notifications.email'])->first();

            if (!$permission_email) {
                return; 
            }
            $roles = DB::table('role_has_permissions')
            ->where('permission_id', $permission_email->id)
            ->pluck('role_id')
            ->unique();
            // Get users who have these roles
            if($roles){
                $userIds = DB::table('model_has_roles')
                        ->whereIn('role_id', $roles)
                        ->pluck('model_id');
                $email_users = User::whereIn('id', $userIds)->get();
            }
            
        }
        // check user for sms 
        if($sms_notification){
            $permission_sms = Permission::where('name', ['notifications.sms'])->first();

            if (!$permission_sms) {
                return; 
            }
            $roles = DB::table('role_has_permissions')
                ->where('permission_id', $permission_sms->id)
                ->pluck('role_id')
                ->unique();
                // Get users who have these roles
                if($roles){
                    $userIds = DB::table('model_has_roles')
                        ->whereIn('role_id', $roles)
                        ->pluck('model_id');
                    $sms_users = User::whereIn('id', $userIds)->get();
                }

       }
        // check user for whatsapp 
       if($whatsapp_notification){
        // Get permission for this event
            $permission_whatsapp = Permission::where('name', ['notifications.whatsapp'])->first();

            if (!$permission_whatsapp) {
                return; // No permission found, skip notification
            }
            $roles = DB::table('role_has_permissions')
                ->where('permission_id', $permission_whatsapp->id)
                ->pluck('role_id')
                ->unique();
                // Get users who have these roles
                if($roles){
                    $userIds = DB::table('model_has_roles')
                        ->whereIn('role_id', $roles)
                        ->pluck('model_id');
                    $whatsapp_users = User::whereIn('id', $userIds)->get();
                }

        }

        if($email_notification){

            $email_addresses = $email_users->pluck('email')->toArray();
            self::sendEmail($email_addresses, $details['email']);
        }

        // if($sms_notification){
        //     $smsService = new SMSService();
        //     foreach ($sms_users as $user) {
        //         if($user->phone){
        //             $smsService->sendNotificationSms($user->phone, $details['sms']);
        //         }
        //     }
        // }

        if($whatsapp_notification){

            $whatsappService = new WhatsAppService();
                foreach ($whatsapp_users as $user) { 
                    if($user->mobile){
                        $responce = $whatsappService->sendWhatsAppNotificationMessage($user->mobile, $details['whatsapp']['message']);
                    }
                    
                }
        
        }
        
    }

    /**
     * Send Email Notification.
     */
    private static function sendEmail($emails, $details = [] , $attachmentPath = null)
    {
        try {
            // Send email
            Mail::to($emails)->send(new NotificationEmail($details , $attachmentPath));
        } catch (\Exception $e) {
            // Log any errors
            Log::error('Error sending email: ' . $e->getMessage());
        }
    }
}
