{include common/header@php94/admin}
<h1 class="py-3">字段管理</h1>

<fieldset>
    <legend>添加字段：</legend>
    {foreach $fieldtypes as $fieldtype}
    <a href="{:$router->build('/php94/cms/field/create', ['model_id'=>$model['id'], 'type'=>$fieldtype])}">{:$fieldtype::getTitle()}</a>
    {/foreach}
</fieldset>

<div class="table-responsive mt-1">
    <table class="table table-bordered d-table-cell">
        <thead>
            <tr>
                <th>标题</th>
                <th>字段</th>
                <th>类型</th>
                <th>字段属性</th>
                <th>后台编辑</th>
                <th>列表显示</th>
                <th>管理</th>
                <th>排序</th>
            </tr>
        </thead>
        <tbody>
            <?php $groups = []; ?>
            {foreach $fields as $vo}
            <?php $vo['group'] = $vo['group'] ?: '未分组'; ?>
            {if !in_array($vo['group'], $groups)}
            <?php $groups[] = $vo['group']; ?>
            <tr>
                <td colspan="8">
                    <span style="font-weight: bold;">{$vo['group']}</span>
                </td>
            </tr>
            {foreach $fields as $sub}
            <?php $sub['group'] = $sub['group'] ?: '未分组'; ?>
            {if $sub['group'] == $vo['group']}
            <tr>
                <td>
                    <span>{$sub.title}</span>
                </td>
                <td>
                    {$sub.name}
                </td>
                <td>
                    {if class_exists($sub['type'])}
                    {$sub['type']::getTitle()}
                    {else}
                    类型<code>{$sub['type']}</code>不存在
                    {/if}
                </td>
                <td>
                    <span>{$sub.fieldtype}</span>
                </td>
                <td>
                    {if $sub['editable']}
                    <span>✔</span>
                    {/if}
                </td>
                <td>
                    {if $sub['show']}
                    <span>✔</span>
                    {/if}
                </td>
                <td>
                    <a href="{:$router->build('/php94/cms/field/update', ['id'=>$sub['id']])}">编辑</a>
                    {if !$sub['system']}
                    <a href="{:$router->build('/php94/cms/field/delete', ['id'=>$sub['id']])}" onclick="return confirm('确定删除吗？删除后不可恢复！');">删除</a>
                    {/if}
                </td>
                <td>
                    <a href="{echo $router->build('/php94/cms/field/priority', ['id'=>$sub['id'],'type'=>'up'])}">上移</a>
                    <a href="{echo $router->build('/php94/cms/field/priority', ['id'=>$sub['id'],'type'=>'down'])}">下移</a>
                </td>
            </tr>
            {/if}
            {/foreach}
            {/if}
            {/foreach}
        </tbody>
    </table>
</div>
{include common/footer@php94/admin}