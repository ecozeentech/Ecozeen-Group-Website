<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\NavigationItemRequest;
use App\Http\Requests\Admin\NavigationMenuRequest;
use App\Models\NavigationItem;
use App\Models\NavigationMenu;
use App\Models\Page;
use App\Services\ActivityLogger;
use App\Services\NavigationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NavigationMenuController extends Controller
{
    public function index(): View
    {
        $this->authorize('navigation.manage');

        $menus = NavigationMenu::withCount('allItems')->orderBy('name')->get();

        return view('admin.navigation.index', compact('menus'));
    }

    public function create(): View
    {
        $this->authorize('navigation.manage');

        return view('admin.navigation.form', ['menu' => new NavigationMenu]);
    }

    public function store(NavigationMenuRequest $request): RedirectResponse
    {
        $menu = NavigationMenu::create($request->validated());
        NavigationService::flush();
        ActivityLogger::log('created', $menu, "Created navigation menu \"{$menu->name}\"");

        return redirect()->route('admin.navigation.edit', $menu)->with('success', 'Menu created. Add items below.');
    }

    public function edit(NavigationMenu $navigation): View
    {
        $this->authorize('navigation.manage');

        return view('admin.navigation.edit', [
            'menu' => $navigation->load(['allItems.page', 'allItems.children']),
            'pages' => Page::orderBy('title')->get(),
            'topLevelItems' => $navigation->allItems->whereNull('parent_id'),
        ]);
    }

    public function update(NavigationMenuRequest $request, NavigationMenu $navigation): RedirectResponse
    {
        $navigation->update($request->validated());
        NavigationService::flush();
        ActivityLogger::log('updated', $navigation, "Updated navigation menu \"{$navigation->name}\"");

        return redirect()->route('admin.navigation.edit', $navigation)->with('success', 'Menu updated.');
    }

    public function destroy(NavigationMenu $navigation): RedirectResponse
    {
        $navigation->delete();
        NavigationService::flush();
        ActivityLogger::log('deleted', null, "Deleted navigation menu \"{$navigation->name}\"");

        return redirect()->route('admin.navigation.index')->with('success', 'Menu deleted.');
    }

    public function storeItem(NavigationItemRequest $request, NavigationMenu $navigation): RedirectResponse
    {
        $data = $request->validated();
        $data['navigation_menu_id'] = $navigation->id;
        $data['open_in_new_tab'] = $request->boolean('open_in_new_tab');
        $data['is_visible'] = $request->boolean('is_visible', true);
        $data['sort_order'] = $data['sort_order'] ?? ((int) $navigation->allItems()->max('sort_order') + 1);

        NavigationItem::create($data);
        NavigationService::flush();
        ActivityLogger::log('created', $navigation, "Added navigation item to \"{$navigation->name}\"");

        return redirect()->route('admin.navigation.edit', $navigation)->with('success', 'Item added.');
    }

    public function updateItem(NavigationItemRequest $request, NavigationMenu $navigation, NavigationItem $item): RedirectResponse
    {
        abort_unless($item->navigation_menu_id === $navigation->id, 404);

        $data = $request->validated();
        $data['open_in_new_tab'] = $request->boolean('open_in_new_tab');
        $data['is_visible'] = $request->boolean('is_visible');

        $item->update($data);
        NavigationService::flush();
        ActivityLogger::log('updated', $navigation, "Updated a navigation item on \"{$navigation->name}\"");

        return redirect()->route('admin.navigation.edit', $navigation)->with('success', 'Item updated.');
    }

    public function destroyItem(NavigationMenu $navigation, NavigationItem $item): RedirectResponse
    {
        $this->authorize('navigation.manage');
        abort_unless($item->navigation_menu_id === $navigation->id, 404);

        $item->delete();
        NavigationService::flush();
        ActivityLogger::log('deleted', $navigation, "Removed a navigation item from \"{$navigation->name}\"");

        return redirect()->route('admin.navigation.edit', $navigation)->with('success', 'Item removed.');
    }

    public function reorderItems(Request $request, NavigationMenu $navigation): RedirectResponse
    {
        $this->authorize('navigation.manage');

        $data = $request->validate([
            'order' => ['required', 'array'],
            'order.*' => ['integer', 'exists:navigation_items,id'],
        ]);

        foreach ($data['order'] as $position => $itemId) {
            $navigation->allItems()->where('id', $itemId)->update(['sort_order' => $position]);
        }

        NavigationService::flush();

        return redirect()->route('admin.navigation.edit', $navigation)->with('success', 'Order saved.');
    }
}
