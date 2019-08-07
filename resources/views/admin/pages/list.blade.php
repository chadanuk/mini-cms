<div class="mini-cms_list">
    <div class="mini-cms_header-wrapper">
        <h1 class="mini-cms__header">Pages</h1>
        <a href="{{ route('mini-cms.pages.create') }}" class="mini-cms__button mini-cms__button--create">Create page</a>
    </div>
    <div class="mini-cms__table-wrapper">
        <table class="mini-cms__table">
            <thead>
                <tr>
                    <td>Name</td>
                    <td>Actions</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($pages as $page)
                <tr>
                    <td>{{ $page->name }}</td>
                    <td>
                        <a href="{{ route('mini-cms.pages.edit', ['page' => $page->id]) }}" title="Edit page"
                            class="mini-cms__button mini-cms__button--edit">Edit</a>
                        <a href="{{ route('mini-cms.pages.delete', ['page' => $page->id]) }}" title="Delete page"
                            class="mini-cms__button mini-cms__button--delete">Delete</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
