<?php

namespace App\Http\Controllers\Admin\Concerns;

use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * Shared CRUD behaviour for simple, flat taxonomy-style resources
 * (product/service/project categories, blog categories & tags). Concrete
 * controllers only declare the model class, permission prefix, view
 * directory, and whether a description field applies.
 */
/**
 * Consuming controllers must declare: $modelClass, $permissionPrefix,
 * $title, $routeName, and $hasDescription. These are intentionally not
 * declared on the trait itself — PHP treats a property declared on both a
 * trait and the composing class as a conflict unless every detail
 * (including default value) matches exactly, which would defeat the
 * purpose of letting each controller customize these values.
 *
 * @property string $modelClass
 * @property string $permissionPrefix
 * @property string $title
 * @property string $routeName
 * @property bool $hasDescription
 */
trait ManagesSimpleTaxonomy
{
    public function index(): View
    {
        $this->authorize("{$this->permissionPrefix}.view");

        $hasSortOrder = Schema::hasColumn((new $this->modelClass)->getTable(), 'sort_order');

        $items = $this->modelClass::query()
            ->when(request('q'), fn ($q) => $q->where('name', 'like', '%'.request('q').'%'))
            ->when($hasSortOrder, fn ($q) => $q->orderBy('sort_order'))
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return view('admin.taxonomy.index', [
            'items' => $items,
            'title' => $this->title,
            'routeName' => $this->routeName,
            'hasDescription' => $this->hasDescription,
        ]);
    }

    public function create(): View
    {
        $this->authorize("{$this->permissionPrefix}.create");

        return view('admin.taxonomy.form', [
            'item' => new $this->modelClass,
            'title' => $this->title,
            'routeName' => $this->routeName,
            'hasDescription' => $this->hasDescription,
            'hasSortOrder' => Schema::hasColumn((new $this->modelClass)->getTable(), 'sort_order'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize("{$this->permissionPrefix}.create");

        $data = $this->validated($request);
        $item = $this->modelClass::create($data);

        ActivityLogger::log('created', $item, "Created {$item->name}");

        return redirect()->route("admin.{$this->routeName}.index")->with('success', 'Created successfully.');
    }

    public function edit(int $id): View
    {
        $item = $this->modelClass::findOrFail($id);
        $this->authorize("{$this->permissionPrefix}.update");

        return view('admin.taxonomy.form', [
            'item' => $item,
            'title' => $this->title,
            'routeName' => $this->routeName,
            'hasDescription' => $this->hasDescription,
            'hasSortOrder' => Schema::hasColumn((new $this->modelClass)->getTable(), 'sort_order'),
        ]);
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $item = $this->modelClass::findOrFail($id);
        $this->authorize("{$this->permissionPrefix}.update");

        $item->update($this->validated($request, $item));

        ActivityLogger::log('updated', $item, "Updated {$item->name}");

        return redirect()->route("admin.{$this->routeName}.index")->with('success', 'Updated successfully.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $item = $this->modelClass::findOrFail($id);
        $this->authorize("{$this->permissionPrefix}.delete");

        $name = $item->name;
        $item->delete();

        ActivityLogger::log('deleted', null, "Deleted taxonomy term {$name}");

        return redirect()->route("admin.{$this->routeName}.index")->with('success', 'Deleted successfully.');
    }

    protected function validated(Request $request, $ignoring = null): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'alpha_dash', 'max:255', Rule::unique((new $this->modelClass)->getTable(), 'slug')->ignore($ignoring?->id)],
        ];

        $table = (new $this->modelClass)->getTable();

        if ($this->hasDescription && Schema::hasColumn($table, 'description')) {
            $rules['description'] = ['nullable', 'string', 'max:1000'];
        }

        if (Schema::hasColumn($table, 'sort_order')) {
            $rules['sort_order'] = ['nullable', 'integer', 'min:0'];
        }

        $data = $request->validate($rules);

        if (empty($data['slug'])) {
            unset($data['slug']);
        }

        return $data;
    }
}
