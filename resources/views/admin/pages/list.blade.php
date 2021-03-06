<div class="mini-cms_list px-2">
    <div class="mini-cms_header-wrapper flex justify-between">
        <h1 class="font-semibold bg-grey-lightest text-base text-grey-darkest mini-cms__header">Pages</h1>
        <a href="{{ route('mini-cms.pages.create') }}"
            class="p-2 border border-rounded border-green mini-cms__button mini-cms__button--create">Create page</a>
    </div>
    <div class="mini-cms__table-wrapper">
        <table class="mini-cms__table">
            <thead>
                <tr>
                    <th class="p-2">Name</th>
                    <th class="p-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pages as $page)
                <tr>
                    <td class="p-2">{{ $page->name }}</td>
                    <td class="p-2">
                        <a href="{{ route('mini-cms.pages.edit', ['page' => $page->id]) }}" title="Edit page"
                            class="p-2 border border-rounded border-grey mini-cms__button mini-cms__button--edit">Edit</a>
                        <a href="{{ route('mini-cms.pages.delete', ['page' => $page->id]) }}" title="Delete page"
                            class="p-2 border border-rounded border-red  mini-cms__button mini-cms__button--delete">Delete</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
