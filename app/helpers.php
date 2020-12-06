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
        'title' => __('global.success'),
        'message' => __('global.success_message')
    ] : [
        'status' => 0,
        'title' => __('global.error'),
        'message' => __('global.error_message')
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
        'title' => __('global.error'),
        'message' => __('global.unauthorized_message')
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
        $html .= buildHtmlTree($content->children,$htmlTags,$dbCols, $content->item_id);
        $html .= $htmlTags['childEnd'];
    }
    $html .= $htmlTags['end'];
    return $html;
}
