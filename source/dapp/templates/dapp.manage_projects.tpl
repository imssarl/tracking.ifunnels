<div class="row">
    <div class="col-10">
        <table class="table">
            <thead>
                <th width="20px">UID</th>
                <th>Project Name</th>
                <th>Signature</th>
                <th class="text-center">Total Records</th>
                <th>Added</th>
                <th>Edited</th>
                <th>Options</th>
            </thead>

            <tbody>
                {foreach from=$arrList item=item}
                <tr>
                    <td>
                        <i class="bi bi-clipboard me-1 pointer_div" data-clipboard data-clipboard-text="{md5($item.id)}"
                            title="Copy Project UID"></i>
                    </td>
                    <td>{$item.name}</td>
                    <td>
                        <i class="bi bi-clipboard me-1 pointer_div" data-clipboard
                            data-clipboard-text="{$item.signature}" title="Copy Signature"></i>{$item.signature}
                    </td>
                    <td class="text-center">{$item.total}</td>
                    <td>{$item.added|date_local:$config->date_time->dt_full_format}</td>
                    <td>{$item.edited|date_local:$config->date_time->dt_full_format}</td>
                    <td>
                        <a href="{url name='dapp' action='create_project'}?pid={$item.id}" title="Edit this project"
                            class="me-1"><i class="bi bi-pencil-fill text-warning"></i></a>
                        <a href="{url name='dapp' action='manage_projects'}?delete_id={$item.id}" class="delete-project"
                            title="Delete this project"><i class="bi bi-trash-fill text-danger"></i></a>
                    </td>
                </tr>
                {foreachelse}
                <tr>
                    <td colspan="6" class="text-center">Empty</td>
                </tr>
                {/foreach}
            </tbody>
        </table>
    </div>
</div>

<script src="/skin/webpack/dapp/dist/js/main.bundle.js"></script>