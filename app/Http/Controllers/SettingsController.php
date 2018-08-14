<?php

namespace App\Http\Controllers;

use App\PhpC\UpdatesManager;
use App\Setting;
use App\Snippet;
use Illuminate\Http\Request;

class SettingsController extends Controller {

    protected $request;
    protected $setting;
    protected $snippet;

    public function __construct() {
        $this->request = app(Request::class);
        $this->setting = app(Setting::class);
        $this->snippet = app(Snippet::class);
    }

    public function changeTheme() {

        if (settings(true)->theme == $this->request->theme) {
            exit("same");
        }

        $this->updateSetting('theme', $this->request->theme);

        return ucwords($this->request->theme);
    }

    public function changeThemeType() {

        if (settings(true)->theme_type == $this->request->type) {
            exit("same");
        }

        $this->updateSetting('theme_type', $this->request->type);

        return ($this->request->type);
    }

    public function keepLastCodes() {
        $this->updateSetting('keep_last_codes', $this->request->keep_codes);
        $this->snippet = $this->snippet->orderBy('updated_at', 'desc')->first();

        return response()->json([
            'status' => $this->request->keep_codes,
            'contents' => htmlspecialchars_decode($this->snippet->contents),
            'snippet' => $this->snippet
        ]);
    }

    public function updateSetting($index, $value) {
        $this->setting = $this->setting->all();
        $settings = json_decode($this->setting[0]->settings);

        $settings->$index = $value;
        $this->setting[0]->settings = json_encode($settings);

        return $this->setting[0]->save();
    }

    public function checkIfUpdatesIsActive() {
        //session()->forget('updates.active');
        return response()->json(session('updates.active'));
    }

    public function updateSystemFontFamily() {
        $this->updateSetting('app_font_family', $this->request->font);

        return response(settings(true)->app_font_family);
    }

    public function updateSystemFontSize() {
        $this->updateSetting('app_font_size', intval($this->request->fontSize));

        return response(settings(true)->app_font_size);
    }

    public function getDynamic() {
        return response($this->setting->first()->settings);
    }

    /**
     * @param UpdatesManager $updatesManager
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function runAutoUpdateChecker(UpdatesManager $updatesManager) {

        $nextUpdate = settings(true)->next_update_check;
        $newNextUpdate = now()->parse($nextUpdate)->addDay(2);
        $updateDateHasPassed = str_contains(strval(now()->diffForHumans($nextUpdate)), 'after');
        $updateDay = now()->diffForHumans($nextUpdate, true);

        if ($updateDateHasPassed && $updateDay == '2 days') {
            $this->updateSetting('update_postponed', false);
        }

        if (!settings(true)->update_postponed) {
            $updatesManager = $this->startUpdatesCheck($updatesManager);
            $versions = $updatesManager->getVersions();

            if (is_null($updatesManager->getError())) {
                return response()->json([
                    'hasError' => false,
                    'versions' => ['available' => $versions['available_version'], 'installed' => $versions['user_version']],
                    'uptoDate' => $versions['available_version'] < $versions['user_version'] || $versions['available_version'] ==
                        $versions['user_version'],
                    'recheckDayReached' => time() == $nextUpdate,
                    'isPostponed' => settings(true)->update_postponed,
                    'nextUpdateCheck' => $newNextUpdate->toDateTimeString(),
                    'nextUpdateCheckWords' => now()->diffForHumans($nextUpdate, true),
                    'message' => $updatesManager->getMessage()
                ]);
            }
            return response()->json([
                'hasError' => true,
                'timeNotReached' => false,
                'errorMessage' => $updatesManager->getError()
            ]);
        } elseif (settings(true)->update_postponed && time() == strtotime($nextUpdate)) {
            $this->updateSetting('update_postponed', false);
        }

        return response()->json([
            'hasError' => true,
            'timeNotReached' => true,
            'errorMessage' => "Not yet time to check for updates"
        ]);
    }

    /**
     * @param UpdatesManager $updatesManager
     * @return UpdatesManager
     * @throws \Exception
     */
    public function startUpdatesCheck(UpdatesManager $updatesManager) {
        $url = "http://treasuregh.test/storage/files.zip";
        $updatesManager->checkUpdates = true;
        $updatesManager->setUrl($url)
            ->checkConnection(false)
            ->run();

        return $updatesManager;
    }

    public function postponeUpdateCheck() {
        $nextUpdate = settings(true)->next_update_check;
        $newNextUpdate = now()->parse($nextUpdate)->addDay(2);
        $this->updateSetting('next_update_check', $newNextUpdate->toDateTimeString());
        $this->updateSetting('update_postponed', true);

        return response()->json([
            'nextUpdateCheck' => $newNextUpdate,
            'nextUpdateCheckWords' => now()->diffForHumans($newNextUpdate, true),
            'message' => "We will check for updates in the next " . now()->diffForHumans($newNextUpdate, true) . " when you are connected.",
        ]);
    }

    public function updateTimezone() {
        $this->updateSetting('timezone', $this->request->timezone);

        return settings();
    }


}
