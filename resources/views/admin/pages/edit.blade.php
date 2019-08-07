<form action="{{ route('mini-cms.pages.update', ['id' => $page->id]) }}" method="post" class="mini-cms__form">
    <h1>Edit page</h1>
    <fieldset>
        <legend>Page details</legend>
        <div class="mini-cms__form-row">
            <label>
                Page name
                <input type="text" name="name" class="mini-cms__form-input" value="{{ $page->name }}">
            </label>
        </div>

        @foreach ($page->pageBlocks() as $pageBlock)
        <div class="mini-cms__form-row">
            <label>
                {{ $pageBlock->label }}
                {!! $pageBlock->renderInput() !!}
            </label>
        </div>
        @endforeach

        <div class="mini-cms__form-row">
            <input type="submit" class="button" value="Update page">
        </div>
    </fieldset>
</form>
