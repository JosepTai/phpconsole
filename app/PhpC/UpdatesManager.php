<?php
/**
 * Created by PhpStorm.
 * User: JOSIAH
 * Date: 4/29/2018
 * Time: 3:12 AM
 */

namespace App\PhpC;

use App\Setting;
use Chumper\Zipper\Zipper;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class UpdatesManager {

    public $filesArray;
    protected $moved = false;
    protected $updateFiles;
    protected $checker;
    protected $message;
    protected $setting;
    protected $buildFile;
    protected $uptoDate = false;
    protected $error;
    protected $filesToBackup = [];
    public $checkUpdates = false;
    protected $versions;
    protected $storage;
    protected $zipper;
    protected $url;
    protected $path;
    protected $checkConnection = true;

    public function __construct(bool $check_updates = false) {
        $this->checkUpdates = $check_updates;
        $this->setting = new Setting();
        $this->storage = new Storage();
        $this->zipper = new Zipper();
        $this->path = storage_path('app/updates');

    }

    /**
     * @throws \Exception
     */
    public function run() {
       $this->startUpdates()
            ->getUpdateFiles()
            ->extractFiles()
           ->getVersionsFromBuildFile()
           ->finalizeUpdates();
    }

    protected function startUpdates() {
        if ($this->isConnectedToInternet()) {
            $this->storage::disk('local')->deleteDirectory('/updates');
            $this->storage::disk('local')->makeDirectory('updates');
        }

        return $this;
    }

    protected function getUpdateFiles() {

        $this->downloadUpdates();
        $this->updateFiles = storage_path('app/updates/files.zip');

        return $this;

    }

    public function setUrl($url) {
        $this->url = $url;
        return $this;
    }

    public function checkConnection(bool $bool = true) {
        $this->checkConnection = $bool;

        return $this;
    }

    protected function downloadUpdates() {
        try {
            if ($this->url == '') {
                $this->url = "https://github.com/coderatio/phpc_updates/raw/master/files.zip";
            }
            copy($this->url, storage_path('app/updates/files.zip'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }

        return $this;
    }

    /**
     * @throws \Exception
     */
    protected function extractFiles() {
        $zip = new ZipArchive();
        try {
            if ($this->isConnectedToInternet()) {
                if (!$this->uptoDate) {
                    $selectFile = $zip->open($this->updateFiles);
                    $extract = $zip->extractTo(storage_path('app/updates'));
                    $zip->close();
                }
            } else {
                $this->error = 'No Internet';
            }
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
        }
        return $this;
    }

    public function getVersionsFromBuildFile() {
        if ($this->storage::disk('updates')->exists('files/build.json')) {
            $this->buildFile = file_get_contents(storage_path('app/updates/files/build.json'));
            $update = json_decode($this->buildFile);
            $this->checker = $update;
        }

        return $this;
    }

    public function getVersions() {
        $this->getVersionsFromBuildFile();
        return [
            'available_version' => $this->checker->new_version,
            'user_version' => settings(true)->version
        ];
    }

    /**
     * @return $this
     * @throws \Exception
     */
    protected function finalizeUpdates() {
        if ($this->storage::disk('updates')->exists('files/files.json')) {
            try {
                $this->getVersionsFromBuildFile();
                $this->filesArray = file_get_contents(storage_path('app/updates/files/files.json'));
                $this->filesArray = json_decode($this->filesArray);
                $this->updateFiles = $this->filesArray;

                /** Check if only trying to get updates */
                if ($this->checkUpdates == true || $this->checkUpdates == 'check_updates') {
                    $this->message = "Your current version is " .settings(true)->version ." available version is {$this->checker->new_version}";
                    $this->versions = $this->checker;
                    $this->clearCache();
                    return $this;
                }

                /** Check if app is up to date */
                if ($this->checker->new_version == settings(true)->version) {
                    $this->message = "You have the latest version.";
                    $this->clearCache();
                    return $this;
                }

                /** Prepare backup files and move new files to respective directories */
                $this->prepareBackupFiles();

                if (!$this->moved) {
                    $this->error = "Updates failed.";
                    $backupFile = "$this->path/backupFiles.zip";
                    if ($this->restoreBackupFilesFrom($backupFile)) {
                        $this->storage::disk('updates')->delete('backupFiles.zip');
                        $this->error = 'Updates failed but you can try again later.';
                        $this->message = 'Updates failed but we have restored your files.';
                        $this->clearCache();
                    }
                    throw new \Exception($this->error);
                }

                /** Update settings */
                $this->setting = app(Setting::class)->first();
                $setting = json_decode($this->setting->settings);
                $setting->version = $this->checker->new_version;
                $oldVersion = $setting->version;

                $this->setting->settings = json_encode($setting);
                if ($this->setting->save()) {
                    $this->storage::disk('updates')->delete('backupFiles.zip');
                    $this->clearCache();
                    $this->message .= "and cache cleaned.";
                }
                $this->checker = [
                    'old_version' => $oldVersion,
                    'new_version' => $this->checker->new_version
                ];

            } catch (\Exception $e) {
                $this->error = $e->getMessage();
            }
        } else {
            $this->message = "Updates files couldn't be generated.";
            $this->error = 'Updates not started.';
        }

        return $this;
    }

    /**
     * @return $this
     * @throws \Exception
     */
    protected function prepareBackupFiles() {
        foreach ($this->filesArray as $fileArray) {
            $localPath = $fileArray->local_path;
            $remotePath = $fileArray->remote_path;
            foreach ($fileArray->files as $singleFile) {
                /** Backup old files */
                if ($this->storage::disk('base')->exists("{$localPath}{$singleFile}")) {
                    $this->filesToBackup[] = $localPath.$singleFile;
                }
            }
        }

        /** Prepare backup */
        $filesToBackup = $this->filesToBackup;
        $backupFile = "$this->path/backupFiles.zip";
        if (count($filesToBackup) > 0) {
            /** Create backup zipped file */
            $this->zipper->zip("$this->path/backupFiles.zip")->add($filesToBackup);
            $this->zipper->close();

            /** Let's move files now */
           $this->moveNewFilesToDirectories();

        }
        $this->zipper->close();

        return $this;
    }

    protected function moveNewFilesToDirectories() {
        $count = 0;
        foreach ($this->filesArray as $fileArray) {
            $localPath = $fileArray->local_path;
            $remotePath = $fileArray->remote_path;
            foreach ($fileArray->files as $singleFile) {
                /** Delete old files */
                if ($this->storage::disk('base')->exists("{$localPath}{$singleFile}")
                    && $this->storage::disk('updates')->exists("{$remotePath}{$singleFile}") ) {
                    $this->storage::disk('base')->delete("{$localPath}{$singleFile}");
                }

                /** Move new files to respective directories */
                if ($this->storage::disk('updates')->exists("{$remotePath}{$singleFile}")) {
                    $move = $this->storage::disk('base')->move("storage/app/updates/{$remotePath}{$singleFile}",
                        "{$localPath}{$singleFile}");

                    if ($move) {
                        $this->moved = true;
                        $this->message = "App updated ";
                        $count++;
                    } else {
                        $count++;
                        $this->error = "Failed to move some files.";
                    }
                }
            }
        }
    }

    /**
     * @param $backupFile
     * @return bool
     * @throws \Exception
     */
    protected function restoreBackupFilesFrom($backupFile) {
        $backupSuccessfull = false;
        if ($this->storage::disk('updates')->exists("backupFiles.zip")) {
            $this->storage::disk('updates')->makeDirectory('backups');

            if ($this->storage::disk('updates')->exists('backups')) {
                $this->zipper->make($backupFile)->extractTo("$this->path/backups");
                $this->zipper->close();
                $jsonFiles = file_get_contents("$this->path/files/files.json");
                $jsonFiles = json_decode($jsonFiles);

                if ($jsonFiles) {
                    foreach ($jsonFiles as $file) {
                        foreach ($file->files as $fileArray) {
                            /** @var  $fileArray
                             * Remove comma appended to each file
                             */
                            $fileArray = rtrim($fileArray, ',');
                            /** Check if file exist in backup folder */
                            if ($this->storage::disk('updates')->exists('backups/' . $fileArray)) {
                                /** Delete existing new files in local directory */
                                if ($this->storage::disk('base')->exists("{$file->local_path}{$fileArray}")) {
                                    if ($this->storage::disk('base')->delete("{$file->local_path}{$fileArray}")) {
                                        $move = $this->storage::disk('base')->move("storage/app/updates/backups/{$fileArray}","{$file->local_path}{$fileArray}");
                                        if ($move) {
                                            $backupSuccessfull = true;
                                        } else {
                                            $this->message = 'Restoring backup failed. You may need to reinstall this app.';
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        return $backupSuccessfull;
    }

    public function isConnectedToInternet() {
        if ($this->checkConnection) {
            $connected = @fsockopen("www.github.com", 80);
            /** website, port  (try 80 or 443) */
            if ($connected){
                $is_conn = true;
                fclose($connected);
            }else{
                $is_conn = false;
                $this->error = "No Internet";
                $this->message = "Please connect to an active internet and try again!";
            }
            return $is_conn;
        }

        return true;
    }

    public function getMessage() {
        return $this->message;
    }

    public function getError() {
        return $this->error;
    }

    protected function clearCache() {
        if ($this->storage::disk('updates')->exists('files.zip')) {
            $this->storage::disk('updates')->delete('files.zip');
        }

        @$this->storage::disk('updates')->deleteDirectory('files/contents');

        if ($this->isConnectedToInternet()) {
            if ($this->checker->new_version == settings(true)->version) {
                $this->uptoDate = true;
            }
            $this->setting = json_decode($this->setting->first()->settings);
        } else {
            $this->updateFiles = [];
            $this->checker = [];
            $this->setting = null;
        }

        $this->buildFile = null;

        return $this;
    }
}