<?php

/**
 * Returns active class if we are at given url
 *
 * @param $url
 * @param string $class
 * @return mixed
 */
function isActive($url, $class = 'active'): mixed
{
    if (config('laravellocalization.url_prefix')){
        if (is_array($url)) {
            $url = array_map(fn($url) => app()->getLocale() . "/" . $url, $url);
        } else {
            $url = app()->getLocale() . "/" . $url;
        }
        return request()->is($url) ? $class : '';
    }
    return request()->is($url) ? $class : '';
}

/**
 * Makes easier http json responses
 *
 * @param $status
 * @param array $data
 * @param int $statusCode
 * @return \Illuminate\Http\JsonResponse
 */
function resJson($status, $data = array(), int $statusCode = 200)
{
    $response = ($status) ? [
        'status' => 1,
        'title' => __('messages.success.custom_title'),
        'message' => __('messages.success.custom_message')
    ] : [
        'status' => 0,
        'title' => __('messages.error.custom_title'),
        'message' => __('messages.error.custom_message')
    ];
    return response()->json(array_merge($response, $data), $statusCode);
}

/**
 * Response message for unauthorized user
 *
 * @return \Illuminate\Http\JsonResponse
 */
function resJsonUnauthorized()
{
    return response()->json([
        'status' => 0,
        'title' => __('messages.error.custom_title'),
        'message' => __('messages.error.unauthorized_message')
    ], 403);
}

/**
 * Return languages
 *
 * @return \Illuminate\Support\Collection
 */
function languages()
{
    return \Illuminate\Support\Facades\DB::table('languages')->get();
}

/**
 * E-Mail Subject
 *
 * @param $text
 * @return string
 */
function mailSubject(string $text): string
{
    return config('app.name', 'Codethereal') . " // $text";
}

/**
 * Check if user with given id is online
 *
 * @param $id
 * @return bool
 * @throws \Psr\SimpleCache\InvalidArgumentException
 */
function isOnline(int $id): bool
{
    return cache()->has('user-is-online-' . $id);
}

/**
 * Return the value of given key and unset it from array
 *
 * @param array $arr
 * @param $key
 * @return mixed
 */
function array_remove(array &$arr, $key): mixed
{
    $val = $arr[$key] ?? [];
    unset($arr[$key]);
    return $val;
}

/**
 * Returns needed agent data
 *
 * @param null $userAgent
 * @return array
 */
#[JetBrains\PhpStorm\ArrayShape(['platform' => "bool|null|string", 'browser' => "bool|null|string", 'platform_version' => "mixed", 'browser_version' => "mixed", 'device' => "bool|null|string"])]
function agent($userAgent = null): array
{
    $agent = new \Jenssegers\Agent\Agent(null, $userAgent);
    $platform = $agent->platform();
    $browser = $agent->browser();
    return [
        'platform' => $platform ?? null,
        'browser' => $browser ?? null,
        'platform_version' => $agent->version($platform) ?? null,
        'browser_version' => $agent->version($browser) ?? null,
        'device' => $agent->device($userAgent) ?? null
    ];
}

/**
 * Find location by ip from an api
 *
 * @param $ip
 * @param string[] $fields
 * @return array
 */
function location($ip, $fields = array('country', 'city')): array
{
    $fetchFields = implode(',', $fields);
    $ipApi = json_decode(file_get_contents("http://ip-api.com/json/$ip?fields=$fetchFields"));
    $data = [];
    foreach ($fields as $field) {
        $data[$field] = $ipApi->{$field} ?? null;
    }
    return $data;
}

/**
 * Merge existing and new html attributes together
 *
 * @param $existAttrs
 * @param $newAttrs
 * @return string
 */
function mergeHtmlAttributes($existAttrs, $newAttrs): string
{
    $attributes = '';

    // Merge all of them together, and unset attributes that merged
    foreach ($existAttrs as $key => $value) {
        if (in_array($key, array_keys($newAttrs))) {
            $attributes .= $key . '="' . $value . ' ' . $newAttrs[$key] . '"';
            unset($newAttrs[$key]);
        } else {
            $attributes .= $key . '="' . $value . '"';
        }
    }

    // Add attributes that should not be merged (i already unset it from array if merge needed)
    foreach ($newAttrs as $key => $value) {
        $attributes .= $key . '="' . $value . '"';
    }

    return $attributes;
}

/**
 * buildTree function orders the items with their parentId cols, and returns an array with this order
 * @param $items
 * @param array $dbCols = []
 * @param int $parentId = 0
 * @return array
 */
function buildTree($items, array $dbCols = [], int $parentId = 0): array
{
    $dbCols['id'] = isset($dbCols['id']) ? $dbCols['id'] : 'id';
    $dbCols['parentId'] = isset($dbCols['parentId']) ? $dbCols['parentId'] : 'parentId';

    $branch = array();
    foreach ($items as $item) {
        if ($item->{$dbCols['parentId']} == $parentId) {
            $children = buildTree($items, $dbCols, $item->{$dbCols['id']});
            $item->children = ($children) ? $children : array();
            $branch[] = $item;
        }
    }
    return $branch;
}

/**
 * buildHtmlTree function returns a html output, ordered with buildTree function.
 * @param $items
 * @param array $htmlTags = []
 * @param array $dbCols
 * @param int $parentId
 * @return string
 */
function buildHtmlTree($items, array $htmlTags = [], array $dbCols = [], int $parentId = 0): string
{
    $htmlTags['start'] = isset($htmlTags['start']) ? $htmlTags['start'] : '<ul>';
    $htmlTags['end'] = isset($htmlTags['end']) ? $htmlTags['end'] : '</ul>';

    $htmlTags['childStart'] = isset($htmlTags['childStart']) ? $htmlTags['childStart'] : '<li value="{value}">{title}';
    $htmlTags['childEnd'] = isset($htmlTags['childEnd']) ? $htmlTags['childEnd'] : '</li>';

    $dbCols['id'] = isset($dbCols['id']) ? $dbCols['id'] : 'id';
    $dbCols['title'] = isset($dbCols['title']) ? $dbCols['title'] : 'title';

    $htmlStart = str_replace('{parentId}', $parentId, $htmlTags['start']);

    $html = $htmlStart;
    foreach ($items as $item) {
        $childStart = str_replace('{value}', $item->{$dbCols['id']}, $htmlTags['childStart']);
        $childStart = str_replace('{title}', $item->{$dbCols['title']}, $childStart);

        $html .= $childStart;
        $html .= buildHtmlTree($item->children, $htmlTags, $dbCols, $item->{$dbCols['id']});
        $html .= $htmlTags['childEnd'];
    }
    $html .= $htmlTags['end'];
    return $html;
}

/**
 * Check url and format properly (check if redirects to another website etc.)
 *
 * @param string $url
 * @return string
 */
function createUrl(string $url = ''): string
{
    if ($url === '#'){
        return 'javascript:void(0);';
    }else if(preg_match('@^(https://|http://)@', $url)){
        return $url;
    }else if(config('laravellocalization.url_prefix')){
        return url(app()->getLocale() . "/" . $url);
    }
    return url($url);
}

/**
 * Resize an image by given width and height and encode to webp if browser is not safari
 *
 * @param string $path
 * @param int|null $width
 * @param int|null $height
 * @param bool $aspectRatio
 * @param false $upsize
 * @return string
 */
function resize(string $path, int|null $width = null, int|null $height = null, $aspectRatio = true, $upsize = false): string
{
    if(empty($path)) return $path;

    // Do not resize svg files and if both width and height is null means image won't be resized. Just return it
    $fileInfo = pathinfo(asset('storage/' . $path));
    $fileExt = $fileInfo['extension'] ?? '';
    if ($fileExt === 'svg' || ($width === null && $height === null)) {
        return asset('storage/' . $path);
    }

    $explodeSlashes = explode('/', $path);
    $file = end($explodeSlashes);

    // Make webp if browser is not safari (safari does not support webp format)
    if (config('site.browser') !== 'Safari'){
        $file = str_replace($fileExt, 'webp', $file);
    }

    // Thumb file path
    $fileThumb = 'storage/thumbs/DS' . $width . 'x' . $height . '_' . $file;

    // Absolute path to save file
    $savePath = public_path($fileThumb);
    if (!file_exists($savePath)) {
        \Intervention\Image\Facades\Image::make(public_path('storage/' . $path))
            ->encode('webp')
            ->resize($width, $height, function ($constraint) use ($aspectRatio, $upsize) {
                if ($aspectRatio) $constraint->aspectRatio();
                if ($upsize) $constraint->upsize();
            })
            ->save($savePath)
            ->response();
    }

    return asset($fileThumb);
}

/**
 * Find the image path from id and resize the image by given width and height and encode to webp if browser is not safari
 *
 * @param int $id
 * @param int|null $width
 * @param int|null $height
 * @param bool $aspectRatio
 * @param false $upsize
 * @return string
 */
function resizeById(int $id, int|null $width = null, int|null $height = null, $aspectRatio = true, $upsize = false): string
{
    $file = \App\Models\File::select('path')->find($id);
    return resize($file->path ?? '', $width, $height, $aspectRatio, $upsize);
}

/**
 * Returns the proper badge for status
 *
 * @param bool $status
 * @return string
 */
function statusBadge(bool $status): string
{
    return $status ? '<span class="badge bg-success"><i class="material-icons-outlined md-18">check</i></span>' : '<span class="badge bg-danger"><i class="material-icons-outlined md-18">close</i></span></span>';
}

/**
 * Returns the first char of first and last name of the user. (Doğukan Akkaya -> DA)
 *
 * @param string $name
 * @return string
 */
function nameCode(string $name): string
{
    $exploded = explode(' ', $name);
    $first = substr($exploded[0] ?? '', 0, 1);
    $last = '';
    if (count($exploded) !== 1) {
        $last = substr(end($exploded), 0, 1);
    }

    $first = mb_convert_encoding($first, 'UTF-8', 'auto');
    $last = mb_convert_encoding($last, 'UTF-8', 'auto');
    return $first . $last;
}

/**
 * Returns the meta html tags by given parameters (title, description etc.)
 *
 * @param $data
 * @return string
 */
function meta($data): string
{
    $title = $data['title'] ?? config('app.name');
    $description = $data['description'] ?? '';
    $keywords = $data['keywords'] ?? 'code,software';
    $language = config('site.locale_names.' . app()->getLocale());

    return "
         <title>$title</title>
         <meta charset='UTF-8'>
         <meta name='viewport' content='width=device-width, initial-scale=1.0'>
         <meta http-equiv='Cache-control' content='public'>
         <meta name='title' content='$title'>
         <meta name='description' content='$description'>
         <meta name='keywords' content='$keywords'>
         <meta name='robots' content='index, follow'>
         <meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
         <meta name='language' content='$language'>
         <meta name='revisit-after' content='1 days'>
         <meta name='author' content='" . config('app.name') . "'>
    ";
}
