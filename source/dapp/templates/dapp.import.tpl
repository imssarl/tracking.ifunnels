{include file='../../error.tpl'}

<div class="row">
    <div class="col-10">
        <form method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Project</label>

                <select name="project_id" class="form-select">
                    {foreach from=$project_list item=prj}
                    <option value="{$prj.id}">{$prj.name}</option>
                    {/foreach}
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">CSV file</label>
                <input type="file" name="csv" accept=".csv" class="form-control" />
            </div>

            <div class="mb-3">
                <button type="submit" class="btn btn-secondary">Submit</button>
            </div>
        </form>
    </div>
</div>
