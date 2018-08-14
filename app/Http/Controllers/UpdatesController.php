<?php

namespace App\Http\Controllers;

use App\PhpC\Notifier;
use App\PhpC\UpdatesManager;
use App\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UpdatesController extends Controller {

    protected $request;
    protected $app;
    protected $manager;
    protected $versions = [];
    protected $notifier;
    protected $message = null;
    protected $error = null;
    protected $storage;
    protected $updateVersionUrl = "https://raw.githubusercontent.com/coderatio/phpc_updates/master/check.json";

    public function __construct(Setting $setting, UpdatesManager $manager, Notifier $notifier, Storage $storage) {
        $this->app = json_decode($setting->first()->settings);
        $this->manager = $manager;
        $this->notifier = $notifier;
        $this->storage = $storage;
    }

    public function checkUpdates() {
        return view('check-updates');
    }

    public function downloadUpdates() {
        session()->put('updates.active', [
            'status' => true,
            'completed' => false,
        ]);

        return view('download-updates');
    }

    public function autoCheckUpdates() {
        $this->versions = [
            'installed' => $this->app->version,
            'available' => ''
        ];
        try {
            $file = 'app/updates/check.json';
            if ($this->storage::disk('updates')->exists('check.json')) {
                $this->storage::disk('updates')->delete('check.json');
            }

            if (isConnected()) {
                copy($this->updateVersionUrl, storage_path($file));
                $contents = file_get_contents(storage_path($file));
                $app = json_decode($contents);

                $this->versions['available'] = $app->current_version;

                if ($this->app->version < $app->current_version) {
                    $this->message = "New version is available";
                } else {
                    $this->message = 'You have the latest version';
                }

                $this->storage::disk('updates')->delete('check.json');
            } else {
                $this->error = "No Internet";
            }

        } catch (\Exception $e) {
            $this->error = $e->getMessage();
        }

        return response()->json([
            'message' => $this->message,
            'error' => $this->error,
            'versions' => $this->versions,
            'updated' => $this->versions['installed'] == $this->versions['available']
        ]);
    }

    public function doUpdates() {
        set_time_limit(0);

        try {
            if (isConnected()) {
                $this->manager->run();
                $this->versions = $this->manager->getVersions();
                $this->message = "PHP Console Updated to {$this->versions['available_version']} successfully";

                session()->forget('updates.active');
                session()->put('updates.active', [
                    'status' => false,
                    'completed' => true,
                ]);

                $this->notifier->title('Application Update')
                    ->message($this->message)
                    ->send();
            } else {
                $this->error = 'No Internet';
                $this->versions = [
                    'available_version' => '',
                    'user_version' => $this->app->version,
                ];
            }

        } catch (\Exception $e) {
            $this->error = $e->getMessage();
        }

        return response()->json([
           'message' => $this->message,
           'error' => $this->error,
           'versions' => $this->versions
        ]);
    }

    public function clearUpdateSession() {
        session()->forget('updates.active');
    }
}
