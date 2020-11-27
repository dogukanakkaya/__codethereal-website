<?php

/**
 * Returns active class if we are at given url
 *
 * @param $url
 * @param string $class
 * @return string
 */
function isActive($url, $class = 'active'): string
{
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
