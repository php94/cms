{include common/header@php94/admin}
<h1 class="py-3">{$model.title}</h1>
<form action="{echo $router->build('/php94/cms/content/index')}" onchange="this.submit()">
    <input type="hidden" name="model_id" value="{$model.id}">
    {foreach $request->get('order', []) as $fieldname=>$fieldval}
    {if in_array($fieldval, ['ASC', 'DESC'])}
    <input type="radio" style="display: none;" name="order[{$fieldname}]" value="{$fieldval}" checked>
    {/if}
    {/foreach}
    <div style="display: flex;flex-direction: row;flex-wrap: wrap;gap: 10px;">
        {foreach $fields as $field}
        {if $tmp = $field['type']::getFilterForm($field)}
        <div class="d-flex flex-column gap-1">
            <div>{$field['title']}:</div>
            <div>
                {echo $tmp}
            </div>
        </div>
        {/if}
        {/foreach}
        <div class="d-flex flex-column gap-1">
            <div>搜索:</div>
            <input type="search" name="q" value="{$request->get('q')}" class="form-control" width="auto" placeholder="请输入搜索词：">
        </div>
    </div>
</form>
<div class="d-flex flex-column gap-1 mt-2">
    <div>排序:</div>
    <div class="d-flex flex-wrap align-items-center gap-2">
        {foreach $request->get('order', []) as $fieldname=>$fieldval}
        {if in_array($fieldval, ['ASC', 'DESC'])}
        {if $fieldname == 'id'}
        <div>
            <input type="radio" style="display: none;" name="order[{$fieldname}]" value="{$request->get('order.'.$fieldname)}" checked>
            <?php
            $p = $_GET;
            unset($p['order'][$fieldname]);
            ?>
            <a href="{echo $router->build('/php94/cms/content/index', $p)}" class="btn btn-warning">ID{$fieldval=='DESC'?'⇃':'↿'}</a>
        </div>
        {/if}
        {foreach $fields as $field}
        {if $field['name'] == $fieldname}
        <div>
            <input type="radio" style="display: none;" name="order[{$fieldname}]" value="{$request->get('order.'.$fieldname)}" checked>
            <?php
            $p = $_GET;
            unset($p['order'][$fieldname]);
            ?>
            <a href="{echo $router->build('/php94/cms/content/index', $p)}" class="btn btn-warning">{$field.title}{$fieldval=='DESC'?'⇃':'↿'}</a>
        </div>
        {/if}
        {/foreach}
        {/if}
        {/foreach}
        <div>
            <select class="form-select" width="auto" onchange="location.href=this.value;">
                <option value="">请选择</option>
                {if !in_array($request->get('order.id'), ['ASC', 'DESC'])}
                <?php
                $p = $_GET;
                unset($p['order']['id']);
                ?>
                <?php $p['order']['id'] = 'DESC'; ?>
                <option value="{echo $router->build('/php94/cms/content/index', $p)}">ID⇃</option>
                <?php $p['order']['id'] = 'ASC'; ?>
                <option value="{echo $router->build('/php94/cms/content/index', $p)}">ID↿</option>
                {/if}
                {foreach $fields as $field}
                {if !in_array($request->get('order.'.$field['name']), ['ASC', 'DESC'])}
                <?php
                $p = $_GET;
                unset($p['order'][$field['name']]);
                ?>
                <?php $p['order'][$field['name']] = 'DESC'; ?>
                <option value="{echo $router->build('/php94/cms/content/index', $p)}">{$field['title']}⇃</option>
                <?php $p['order'][$field['name']] = 'ASC'; ?>
                <option value="{echo $router->build('/php94/cms/content/index', $p)}">{$field['title']}↿</option>
                {/if}
                {/foreach}
            </select>
        </div>
    </div>
</div>

<div style="margin-top: 15px;">
    <a href="{echo $router->build('/php94/cms/content/create', ['model_id'=>$model['id']])}" class="btn btn-primary">添加内容</a>
</div>

<style>
    #tablemain tr.nowrap th,
    #tablemain tr.nowrap td {
        white-space: nowrap;
    }
</style>

<div class="table-responsive mt-3">
    <table class="table table-bordered d-table-cell" id="tablemain">
        <thead>
            <tr class="nowrap">
                <th style="width:22px;">#</th>
                <th>ID</th>
                <?php $fieldtypenum = 0; ?>
                {foreach $fields as $field}
                {if $field['show']}
                <?php $fieldtypenum += 1; ?>
                <th>{$field.title}</th>
                {/if}
                {/foreach}
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            {foreach $contents as $content}
            <tr class="nowrap">
                <td>
                    <input type="checkbox" value="{$content.id}">
                </td>
                <td><span>{$content.id}</span></td>
                {foreach $fields as $field}
                {if $field['show']}
                <td>
                    {if $field['tpl']}
                    {echo $template->renderString($field['tpl'], ['field'=>$field, 'content'=>$content])}
                    {else}
                    {echo $field['type']::getShow($field, $content)}
                    {/if}
                </td>
                {/if}
                {/foreach}
                <td>
                    <a href="{echo $router->build('/php94/cms/content/update', ['model_id'=>$model['id'], 'id'=>$content['id']])}">编辑</a>
                    <a href="{echo $router->build('/php94/cms/content/copy', ['model_id'=>$model['id'], 'id'=>$content['id']])}">复制</a>
                    <a href="javascript:void(0)" onclick="event.target.parentNode.parentNode.nextElementSibling.style.display=event.target.parentNode.parentNode.nextElementSibling.style.display=='table-row'?'none':'table-row'">详情</a>
                </td>
            </tr>
            <tr style="display: none;">
                <td colspan="{$fieldtypenum + 3}">
                    <dl>
                        <dt>ID</dt>
                        <dd>{$content.id}</dd>
                        {foreach $fields as $field}
                        <dt>{$field.title}</dt>
                        <dd>
                            {if $field['tpl']}
                            {echo $template->renderString($field['tpl'], ['field'=>$field, 'content'=>$content])}
                            {else}
                            {echo $field['type']::getShow($field, $content)}
                            {/if}
                        </dd>
                        {/foreach}
                    </dl>
                </td>
            </tr>
            {/foreach}
        </tbody>
    </table>
</div>
<div class="d-flex gap-2 mt-3">
    <div>
        <input type="button" id="fanxuan" value="全选/反选" class="btn btn-primary">
        <script>
            (function() {
                var fanxuanbtn = document.getElementById("fanxuan");
                fanxuanbtn.addEventListener('click', () => {
                    var checklist = document.querySelectorAll("#tablemain td input");
                    checklist.forEach(element => {
                        element.click();
                    });
                })
            })()
        </script>
    </div>

    <div>
        <form action="{echo $router->build('/php94/cms/content/delete')}" method="POST">
            <input type="hidden" name="model_id" value="{$model.id}">
            <input type="hidden" name="ids" value="">
            <input type="submit" value="删除" class="btn btn-danger" onclick="return confirm('确定删除吗？删除后不可恢复！')">
        </form>
    </div>
</div>
<script>
    (() => {
        var checklist = document.querySelectorAll("#tablemain td input");
        checklist.forEach(element => {
            element.addEventListener('click', () => {
                var checklist = document.querySelectorAll("#tablemain td input");
                var ids = [];
                checklist.forEach(ele => {
                    if (ele.checked) {
                        ids.push(ele.value);
                    }
                })
                document.getElementsByName("ids").forEach(ele => {
                    ele.value = ids.join(',');
                });
            })
        });
    })()
</script>

<div class="d-flex align-items-center flex-wrap gap-1 py-3">
    <a class="btn btn-primary {$page>1?'':'disabled'}" href="{echo $router->build('/php94/cms/content/index', array_merge($_GET, ['page'=>1]))}">首页</a>
    <a class="btn btn-primary {$page>1?'':'disabled'}" href="{echo $router->build('/php94/cms/content/index', array_merge($_GET, ['page'=>max($page-1, 1)]))}">上一页</a>
    <div class="d-flex align-items-center gap-1">
        <input class="form-control" type="number" name="page" min="1" max="{$pages}" value="{$page}" onchange="location.href=this.dataset.url.replace('__PAGE__', this.value)" data-url="{echo $router->build('/php94/cms/content/index', array_merge($_GET, ['page'=>'__PAGE__']))}">
        <span>/{$pages}</span>
    </div>
    <a class="btn btn-primary {$page<$pages?'':'disabled'}" href="{echo $router->build('/php94/cms/content/index', array_merge($_GET, ['page'=>min($page+1, $pages)]))}">下一页</a>
    <a class="btn btn-primary {$page<$pages?'':'disabled'}" href="{echo $router->build('/php94/cms/content/index', array_merge($_GET, ['page'=>$pages]))}">末页</a>
    <div>共{$total}条</div>
</div>
{include common/footer@php94/admin}