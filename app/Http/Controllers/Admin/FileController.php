<?php

/**
 * File operations controller for CodEthereal
 *
 * Available routes
 * @POST /admin/files => this->upload
 *
 * @author Doğukan Akkaya
 * @version 0.0
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    private array $allowedExtensions = array('jpg', 'jpeg', 'png', 'gif', 'svg', 'webp', 'zip');

    public function upload()
    {
        /**
         * 1: Normal
         * 2: Öne Çıkarılan
         * 3: Geniş
         */

        $file = request()->file('file');
        $name = $file->getClientOriginalName();
        $size = $file->getSize();
        $extension = $file->extension();
        $folder = request('folder');

        // Check allowed extensions
        if (!in_array($file->extension(), $this->allowedExtensions)) {
            return redirect()->back()->withErrors([__('dropzone.file_extension_not_allowed')]);
        }

        $path = $file->store($folder, ['disk' => 'public']);

        $file = File::create([
            'path' => $path,
            'name' => $name,
            'size' => $size,
            'extension' => $extension
        ]);

        $data = [
            'id' => $file->id,
            'path' => asset('storage/' . $path)
        ];

        return resJson($file, $data);
    }

    public function download(int $id)
    {
        $file = File::find($id);
        return response()->download("storage/" . $file->path);
    }

    public function find(int $id)
    {
        $file = File::find($id, ['type']);
        $translations = File::select('title', 'alt', 'active', 'language')
            ->where('files.id', $id)
            ->leftJoin('file_translations', 'file_translations.file_id', 'files.id')
            ->get()
            ->keyBy('language')
            ->transform(function ($i) {
                // Remove language keys, i needed it only to make a keyBy on collection
                unset($i->language);
                return $i;
            });
        return response()->json([
            'file' => $file,
            'translations' => $translations
        ]);
    }

    public function saveSequence()
    {
        $data = request()->all();

        DB::beginTransaction();
        try {
            foreach ($data as $key => $value) {
                // I write this with query builder for better performance, there could be a lot of data to be ordered.
                DB::update('UPDATE files SET updated_at = ?, sequence = ? WHERE id = ?;', [
                    now(),
                    $key,
                    $value
                ]);
            }

            DB::commit();
            return resJson(true);
        } catch (\Exception) {
            DB::rollBack();
            return resJson(false);
        }
    }

    public function update(int $id)
    {
        $reqData = request()->json()->all();

        $fileData = array_remove($reqData, 'file');

        DB::beginTransaction();
        try {
            File::where('id', $id)->update($fileData);

            // Loop every language
            foreach (languages() as $language) {
                // Get active language's data
                $data = $reqData[$language->code];

                DB::table('file_translations')->updateOrInsert(
                    [
                        "file_id" => $id,
                        "language" => $language->code
                    ],
                    [
                        'title' => $data['file_title'] ?? '',
                        'alt' => $data['file_alt'] ?? '',
                        'active' => $data['file_active'] ?? ''
                    ]
                );
            }
            DB::commit();
            return resJson(true);
        } catch (\Exception) {
            DB::rollBack();
            return resJson(false);
        }
    }

    public function destroy(int $id)
    {
        // Find file to delete from disk
        $file = File::find($id);

        // Delete from db
        $dbDelete = File::destroy($id);

        // Delete from disk
        $diskDelete = true; // Do not delete from disk (soft delete) Storage::disk('public')->delete($file->path);

        return resJson($dbDelete && $diskDelete);
    }
}
