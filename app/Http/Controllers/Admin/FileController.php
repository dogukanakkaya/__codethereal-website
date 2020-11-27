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
use App\Models\Admin\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    private array $allowedExtensions = array('jpg', 'jpeg', 'png', 'gif', 'svg', 'zip');

    public function upload(Request $request)
    {
        $file = $request->file('file');
        $name = $file->getClientOriginalName();
        $size = $file->getSize();
        $extension = $file->extension();
        $folder = request('folder');

        // Check allowed extensions
        if (!in_array($file->extension(), $this->allowedExtensions)) {
            return redirect()->back()->withErrors([__('global.file_extension_unallowed')]);
        }

        $path = $file->store($folder, ['disk' => 'public']);

        $insert = File::create([
            'path' => $path,
            'name' => $name,
            'size' => $size,
            'extension' => $extension
        ]);

        $data = [
            'id' => DB::getPdo()->lastInsertId(),
            'path' => asset('storage/' . $path)
        ];

        return resJson($insert, $data);
    }

    public function download(int $id)
    {
        $file = File::find($id);
        return response()->download("storage/" . $file->path);
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
