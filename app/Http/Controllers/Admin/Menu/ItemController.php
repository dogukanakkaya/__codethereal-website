<?php

namespace App\Http\Controllers\Admin\Menu;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Menu\ItemRequest;
use App\Models\Admin\Menu\Group;
use App\Models\Admin\Menu\Item;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ItemController extends Controller
{
    public function index(int $groupId)
    {
        if (!Auth::user()->can('see_menus')) {
            return back();
        }
        $groupItems = $this->groupItems($groupId);
        $data = [
            'navigations' => [route('menus.index') => __('menus.group'), __('menus.items')],
            'items' => $this->treeItems($groupItems),
            'actions' => $this->actions(),
            'groupId' => $groupId,
            'parents' => $groupItems->pluck('title', 'item_id')->toArray() // Data of selectable menu parent
        ];
        return view('admin.menus.items', $data);
    }

    /**
     * We do not have datatable on menu items, so we just return a view
     *
     * @param int $groupId
     * @return JsonResponse|Response
     */
    public function ajaxList(int $groupId)
    {
        if (!Auth::user()->can('see_menus')) {
            return resJsonUnauthorized();
        }
        $data = [
            'items' => $this->treeItems($this->groupItems($groupId)),
            'actions' => $this->actions()
        ];
        return response()
            ->view('admin.menus.items-ajax-list', $data, 200)
            ->header('Content-Type', 'application/html');
    }

    public function create(ItemRequest $request, int $groupId)
    {
        if (!Auth::user()->can('create_menus')) {
            return resJsonUnauthorized();
        }
        $data = $request->validated();

        $itemData = array_remove($data, 'item');

        // Set group id from route
        $itemData['group_id'] = $groupId;

        DB::beginTransaction();
        try {
            $item = Item::create($itemData);

            // Loop with every language
            foreach ($data as $language => $values) {
                $values['language'] = $language;
                $values['item_id'] = $item->id;
                $values['url'] = empty($values['url']) ? Str::slug($values['title']) : $values['url'];
                DB::table('menu_item_translations')->insert($values);
            }

            DB::commit();
            return resJson(true);
        } catch (\Exception $e) {
            echo $e->getMessage();
            DB::rollBack();
            return resJson(false);
        }
    }

    public function find(int $groupId, int $itemId)
    {
        if (!Auth::user()->can('see_menus')) {
            return resJsonUnauthorized();
        }
        // TODO: We'll check that for better way for multi language operations (without model relations)
        $item = Item::select('parent_id')->find($itemId);

        $translations = DB::table('menu_item_translations')
            ->select('title', 'url', 'icon', 'active', 'language')
            ->where('item_id', $itemId)
            ->get()
            ->keyBy('language')
            ->transform(function ($i) {
                // Remove language keys, i needed it only to make a keyBy on collection
                unset($i->language);
                return $i;
            });

        return response()->json([
            'item' => $item,
            'translations' => $translations
        ], 200);
    }

    public function update(ItemRequest $request, int $groupId, int $itemId)
    {
        if (!Auth::user()->can('update_menus')) {
            return resJsonUnauthorized();
        }
        $data = $request->validated();

        $itemData = array_remove($data, 'item');

        DB::beginTransaction();
        try {
            Item::where('id', $itemId)->update($itemData);

            // Loop with every language
            foreach ($data as $language => $values) {
                $values['url'] = empty($values['url']) ? Str::slug($values['title']) : $values['url'];
                DB::table('menu_item_translations')
                    ->where('item_id', $itemId)
                    ->where('language', $language)
                    ->update($values);
            }

            DB::commit();
            return resJson(true);
        } catch (\Exception $e) {
            DB::rollBack();
            return resJson(false);
        }
    }

    public function destroy(int $groupId, int $itemId)
    {
        if (!Auth::user()->can('delete_menus')) {
            return resJsonUnauthorized();
        }
        return resJson(Item::destroy($itemId));
    }

    public function restore(int $groupId, int $itemId)
    {
        if (!Auth::user()->can('delete_menus')) {
            return resJsonUnauthorized();
        }
        return resJson(Item::withTrashed()->find($itemId)->restore());
    }

    public function saveSequence(Request $request)
    {
        if (!Auth::user()->can('update_menus')) {
            return resJsonUnauthorized();
        }
        $data = $request->all();

        DB::beginTransaction();
        try {
            foreach ($data as $key => $datum) {
                // I write this with query builder for better performance, there could be a lot of data to be ordered.
                DB::update('UPDATE menu_items SET updated_at = ?, parent_id = ?, sequence = ? WHERE id = ?;', [
                    now(),
                    $datum['parent_id'],
                    $key,
                    $datum['item_id']
                ]);
            }
            DB::commit();
            return resJson(true);
        } catch (\Exception $e) {
            DB::rollBack();
            return resJson(false);
        }

    }

    /**
     * Return the group items and it's language with where condition by App locale language
     *
     * @param $groupId
     * @return mixed
     */
    private function groupItems($groupId)
    {
        return Group::find($groupId)
            ->items()
            ->oldest('sequence')
            ->latest()
            ->leftJoin('menu_item_translations', 'menu_item_translations.item_id', '=', 'menu_items.id')
            ->where('language', app()->getLocale())
            ->get();
    }

    /**
     * Return builded items
     *
     * @param $items
     * @return array
     */
    private function treeItems($items): array
    {
        return buildTree($items, [
            'id' => 'item_id',
            'parentId' => 'parent_id'
        ]);
    }

    /**
     * Actions for menu items
     *
     * @return string[][]
     */
    private function actions(): array
    {
        return [
            ['title' => '<i class="material-icons-outlined md-18">edit</i> ' . __('global.update'), 'onclick' => '__find({value})'],
            ['title' => '<i class="material-icons-outlined md-18">delete</i> ' . __('global.delete'), 'onclick' => '__delete({value})']
        ];
    }
}
