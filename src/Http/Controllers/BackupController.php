<?php

namespace Encore\Admin\Backup\Http\Controllers;

use Dcat\Admin\Layout\Content;
use Dcat\Admin\Admin;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\Backup\BackupDestination\Backup;
use Spatie\Backup\Commands\ListCommand;
use Spatie\Backup\Tasks\Monitor\BackupDestinationStatus;
use Spatie\Backup\Tasks\Monitor\BackupDestinationStatusFactory;

/**
 * Laravel备份管理界面
 *
 * @date 2024-01-17
 */
class BackupController extends Controller {


    /**
     * 备份列表
     *
     * @param Content $content
     * @return Content
     * @date 2024-01-18
     */
    public function index(Content $content): Content {
        $statuses = BackupDestinationStatusFactory::createForMonitorConfig(config('backup.monitor_backups'));
        $listCommand = new ListCommand();
        $rows = $statuses
                    ->map(fn(BackupDestinationStatus $backupDestinationStatus) => $listCommand->convertToRow($backupDestinationStatus))
                    ->all();
        foreach($statuses as $index => $status) {
            $rows[$index]['files'] = $status->backupDestination()->backups()->map(function (Backup $backup) {
                $size = method_exists($backup, 'sizeInBytes') ? $backup->sizeInBytes() : $backup->size();

                return [
                    'path' => basename($backup->path()),
                    'date' => $backup->date()->format('Y-m-d H:i:s'),
                    'size' => \Spatie\Backup\Helpers\Format::humanReadableSize($size),
                ];
            });
            // $name = $status->backupDestination()->backupName();
            // $files = array_map('basename', $status->backupDestination()->disk()->allFiles($name));
            // $rows[$index]['files'] = array_slice(array_reverse($files), 0, 30);
        }

        return $content
            ->title('Title')
            ->description('Description')
            ->body(Admin::view('zwping.backup::index', [
                'backups'   => $rows,
            ]));
    }


    /**
     * Run `backup:run` command.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function run()
    {
        try {
            ini_set('max_execution_time', 300);

            // https://spatie.be/docs/laravel-backup/v8/installation-and-setup#content-dumping-the-database
            // dump_binary_path: mysqldumo路径
            // 默认位置 macos: /usr/local/bin linux: /usr/bin, 未将mysqldump软链到默认位置, 会报mysqldump: command not found
            // start the backup process
            Artisan::call('backup:run --disable-notifications --only-db');

            $output = Artisan::output();

            return response()->json([
                'status'  => true,
                'message' => $output,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Download a backup zip file.
     *
     * @param Request $request
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\BinaryFileResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function download(Request $request) {
        $validated = $request->validate([
            'disk'  => ['required', ],
            'file'  => ['required', ],
        ]);

        $storage = Storage::disk($validated['disk']);
        if ($storage->fileExists($validated['file'])) {
            return response()->download($storage->path($validated['file']));
        }

        return response('', 404);
    }

    /**
     * Delete a backup file.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request)
    {
        $disk = Storage::disk($request->get('disk'));
        $file = $request->get('file');

        if ($disk->exists($file)) {
            $disk->delete($file);

            return response()->json([
                'status'  => true,
                'message' => trans('admin.delete_succeeded'),
            ]);
        }

        return response()->json([
            'status'  => false,
            'message' => trans('admin.delete_failed'),
        ]);
    }

}
