<form action="{{ route('mini-cms.pages.store') }}" method="post" class="mini-cms__form">
    @csrf
    <h1>Create a page</h1>
    <fieldset>
        <legend>Page details</legend>
        <div class="mini-cms__form-row">
            <label>
                Page name
                <input type="text" class="mini-cms__form-input" name="name">
            </label>
        </div>
        <div class="mini-cms__form-row">
            <input type="submit" class="button" value="Create!">
        </div>
    </fieldset>
</form>
