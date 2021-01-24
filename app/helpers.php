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
    if (is_array($url)) {
        $url = array_map(fn($url) => app()->getLocale() . "/" . $url, $url);
    } else{
        $url = app()->getLocale() . "/" . $url;
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
 * @return mixed|null
 */
function array_remove(array &$arr, $key)
{
    $val = $arr[$key] ?? null;
    unset($arr[$key]);
    return $val;
}

/**
 * Returns needed agent data
 *
 * @param null $userAgent
 * @return array
 */
function agent($userAgent = null)
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
        if (in_array($key, array_keys($newAttrs))){
            $attributes .= $key.'="' . $value . ' ' . $newAttrs[$key] . '"';
            unset($newAttrs[$key]);
        }else{
            $attributes .= $key.'="' . $value . '"';
        }
    }

    // Add attributes that should not be merged (i already unset it from array if merge needed)
    foreach ($newAttrs as $key => $value) {
        $attributes .= $key.'="' . $value . '"';
    }

    return $attributes;
}

/**
 * buildTree function ordering the contents with their parentId cols, and returns an array with this order
 * @param $contents
 * @param $parentId = 0
 * @param $dbCols = []
 * @return array
 */
function buildTree($contents, array $dbCols= [], int $parentId = 0): array
{
    $dbCols['id'] = isset($dbCols['id']) ? $dbCols['id'] : 'id';
    $dbCols['parentId'] = isset($dbCols['parentId']) ? $dbCols['parentId'] : 'parentId';

    $branch = array();
    foreach ($contents as $content) {
        if ($content->{$dbCols['parentId']} == $parentId) {
            $children = buildTree($contents, $dbCols, $content->{$dbCols['id']});
            $content->children = ($children) ? $children : array();
            $branch[] = $content;
        }
    }
    return $branch;
}

/**
 * buildHtmlTree function returns a html output, ordered with buildTree function.
 * @param $contents
 * @param array $htmlTags = []
 * @param array $dbCols
 * @param int $parentId
 * @return string
 */
function buildHtmlTree($contents, array $htmlTags = [], array $dbCols = [], int $parentId = 0): string
{
    $htmlTags['start'] = isset($htmlTags['start']) ? $htmlTags['start'] : '<ul>';
    $htmlTags['end'] = isset($htmlTags['end']) ? $htmlTags['end'] : '</ul>';

    $htmlTags['childStart'] = isset($htmlTags['childStart']) ? $htmlTags['childStart'] : '<li value="{value}">{title}';
    $htmlTags['childEnd'] = isset($htmlTags['childEnd']) ? $htmlTags['childEnd'] : '</li>';

    $dbCols['id'] = isset($dbCols['id']) ? $dbCols['id'] : 'id';
    $dbCols['title'] = isset($dbCols['title']) ? $dbCols['title'] : 'title';

    $htmlStart = str_replace('{parentId}', $parentId,$htmlTags['start']);

    $html = $htmlStart;
    foreach ($contents as $content) {
        $childStart = str_replace('{value}',$content->{$dbCols['id']},$htmlTags['childStart']);
        $childStart = str_replace('{title}',$content->{$dbCols['title']},$childStart);

        $html .= $childStart;
        $html .= buildHtmlTree($content->children,$htmlTags,$dbCols, $content->{$dbCols['id']});
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
function createUrl(string $url): string
{
    return $url === '#' ? 'javascript:void(0);' : (
        preg_match('@^(https://|http://)@', $url) ? $url : url(app()->getLocale() . "/" . $url)
    );
}

/**
 * Resize an image by given width and height
 *
 * @param string $path
 * @param int|null $width
 * @param int|null $height
 * @param bool $aspectRatio
 * @param false $upsize
 * @return string
 */
function resize(string $path, int|null $width, int|null $height = null, $aspectRatio = true, $upsize = false): string
{
    $explodeSlashes = explode('/', $path);
    $file = end($explodeSlashes);

    $filePath = 'storage/thumbs/DS' . $width . 'x' . $height . '_' . $file;

    if (!file_exists(asset($filePath))){
        \Intervention\Image\Facades\Image::make('storage/' . $path)
            ->resize($width, $height, function($constraint) use($aspectRatio, $upsize) {
                if ($aspectRatio) $constraint->aspectRatio();
                if ($upsize) $constraint->upsize();
            })
            ->save($filePath)
            ->response();
    }

    return asset($filePath);
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

function nameCode(string $name): string
{
    $exploded = explode(' ', $name);
    $first = substr($exploded[0] ?? '', 0, 1);
    $last = '';
    if (count($exploded) !== 1){
        $last = substr(end($exploded), 0, 1);
    }
    return $first.$last;
}
