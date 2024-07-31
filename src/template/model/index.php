{include common/header@php94/admin}
<h1 class="py-3">模型管理</h1>
<div>
    <a class="btn btn-primary" href="{:$router->build('/php94/cms/model/create')}">新增模型</a>
</div>
<div class="table-responsive mt-2">
    <table class="table table-bordered d-table-cell">
        <thead>
            <tr>
                <th>标题</th>
                <th>名称</th>
                <th>管理</th>
            </tr>
        </thead>
        <tbody>
            {foreach $models as $vo}
            <tr>
                <td>
                    {$vo.title}
                </td>
                <td>
                    {$vo.name}
                </td>
                <td>
                    <a href="{:$router->build('/php94/cms/model/update', ['id'=>$vo['id']])}">编辑</a>
                    <a href="{:$router->build('/php94/cms/model/delete', ['id'=>$vo['id']])}" onclick="return confirm('确定删除吗？删除后不可恢复！');">删除</a>
                    <a href="{:$router->build('/php94/cms/field/index', ['model_id'=>$vo['id']])}">字段管理</a>
                    <a href="{:$router->build('/php94/cms/content/index', ['model_id'=>$vo['id']])}">内容管理</a>
                </td>
            </tr>
            {/foreach}
        </tbody>
    </table>
</div>
{include common/footer@php94/admin}