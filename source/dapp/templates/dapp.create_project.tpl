<div class="row mb-4">
    <div class="col-6">
        <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="{$arrData.id}">

            <div class="mb-3">
                <label class="form-label">Project Name</label>
                <input type="text" class="form-control" name="name" value="{$arrData.name}">
            </div>

            <div class="mb-3">
                <label class="form-label">Signature</label>
                <input type="text" readonly class="form-control" name="signature" value="{$arrData.signature}">
            </div>

            <button type="submit" class="btn btn-secondary">Submit</button>
        </form>
    </div>
</div>

{if !empty($arrData.id)}
<div class="row">
    <div class="accordion">
        <div class="accordion-item">
            <h5 class="accordion-header px-4 py-1 pt-3">Address List</h5>

            <div class="accordion-collapse collapse show">
                <div class="accordion-body d-flex flex-wrap">
                    {foreach from=$arrAddress item=address}
                    <span class="badge bg-warning text-dark m-1">{$address.address}</span>
                    {/foreach}
                </div>
            </div>
        </div>
    </div>
</div>
{/if}