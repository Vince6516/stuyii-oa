<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use kartik\dialog\Dialog;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\OaGoodsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '逆向产品';
$this->params['breadcrumbs'][] = $this->title;
//创建模态框

use yii\bootstrap\Modal;
Modal::begin([
    'id' => 'backward-modal',
//    'header' => '<h4 class="modal-title">保存</h4>',
    'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">关闭</a>',
    'size' => "modal-lg"
]);
//echo
Modal::end();

//模态框的方式查看和更改数据
$viewUrl = Url::toRoute('forward-view');
$updateUrl = Url::toRoute('backward-update');
$createUrl = Url::toRoute('backward-create');
$js = <<<JS


$('.glyphicon-eye-open').addClass('icon-cell');
$('.wrapper').addClass('body-color');
// 查看框
$('.backward-view').on('click',  function () {
        $('.modal-body').children('div').remove();
        $.get('{$viewUrl}',  { id: $(this).closest('tr').data('key') },
            function (data) {
                $('.modal-body').html(data);
            }
        );
    });
    
//删除按钮
$('.backward-delete').on('click',  function () {
     self = this;
     krajeeDialog.confirm("确定删除此条记录?", function (result) {
        
        if (result) {
            id = $(self).closest('tr').data('key');
            $.post('delete',{id:id,type:'backward-products'},function() {
            });
            }
            });
        });
//更新框
$('.backward-update').on('click',  function () {
        $('.modal-body').children('div').remove();
        $.get('{$updateUrl}',  { id: $(this).closest('tr').data('key') },
            function (data) {
                $('.modal-body').html(data);
            }
        );
    });

//创建框
$('.backward-create').on('click',  function () {
        $('.modal-body').children('div').remove();
        $.get('{$createUrl}',
            function (data) {
                $('.modal-body').html(data);
            }
        );
    }); 

//提交审核
$('.approve').on('click',function(){
     var id = $(this).closest('tr').data('key');
        krajeeDialog.confirm("确定提交审核?", function (result) {
        if (result) {
           $.get('/oa-goods/approve?id='+id,{'type':'backward-products'},
               function(msg){
                  alter(msg); 
               }               
           );
            
        } else {
            alert('Oops!放弃提交');
        }
    });
});

//批量提交审批
$('.approve-lots').on('click',function() {
    var ids = $("#oa-check").yiiGridView("getSelectedRows");    
    if(ids.length == 0) return false;
     $.ajax({
           url:"/oa-goods/approve-lots",
           type:"post",
           data:{'id':ids,'type':'backward-products'},
           dataType:"json",
           success:function(res){               
                console.log("oh yeah lots passed!");
           }
        });
    });

JS;
$this->registerJs($js);
//单元格居中类
class CenterFormatter {
    public function __construct($name) {
        $this->name = $name;
    }
    public  function format() {
        // 超链接显示为超链接
        if ($this->name === 'origin'||$this->name === 'origin1'||$this->name === 'origin1'
            ||$this->name === 'origin2'||$this->name === 'origin3'||$this->name === 'vendor1'||$this->name === 'vendor2'
            ||$this->name === 'vendor3') {
            return  [
                'attribute' => $this->name,
                'value' => function($data) {
                    if(!empty($data[$this->name]))
                    {
                        try {
                            $hostName = parse_url($data[$this->name])['host'];
                        }
                        catch (Exception $e){
                            $hostName = "www.unknown.com";
                        }
                        return "<a class='cell' href='{$data[$this->name]}' target='_blank'>{$hostName}</a>";
                    }
                    else
                    {
                        return '';
                    }

                },
                'format' => 'raw',

            ];
            // 图片显示为图片
        }
        if ($this->name === 'img') {
            return [
                'attribute' => 'img',
                'value' => function($data) {
                    return "<img src='".$data[$this->name]."' width='100' height='100'>";
                },
                'format' => 'raw',

            ];
        }
        if (strpos(strtolower($this->name), 'date') || strpos(strtolower($this->name), 'time')) {
            return [
                'attribute' => $this->name,
                'value' => function($data) {
                    return "<span class='cell'>".substr($data[$this->name],0,19)."</span>";

                },
                'format' => 'raw',

            ];

        }
        return  [
            'attribute' => $this->name,
            'value' => function($data) {
                return "<span class='cell'>".$data[$this->name]."</span>";
//                    return $data['cate'];
            },
            'format' => 'raw',


        ];
    }
};
//封装到格式化函数中
function centerFormat($name) {
    return (new CenterFormatter($name))->format();
};
?>
<style>
    .cell {
        Word-break: break-all;
        display: table-cell;
        vertical-align: middle;
        text-align: center;
        width: 100px;
        height: 100px;
        /*border:1px solid #666;*/
    }

    .icon-cell {
        Word-break: break-all;
        display: table-cell;
        vertical-align: middle;
        text-align: center;
        width: 10px;
        height: 100px;
    }
    .body-color {
        background-color: whitesmoke;
    }
</style>
<div class="oa-goods-index">
   <!-- 页面标题-->
    <p>
        <?= Html::a('新增产品',"javascript:void(0);",  ['title'=>'create','data-toggle' => 'modal','data-target' => '#backward-modal','class' => 'backward-create btn btn-primary']) ?>
        <?= Html::a('批量导入', "javascript:void(0);", ['title' => 'upload', 'class' => 'upload btn btn-info']) ?>
        <?= Html::a('批量删除',"javascript:void(0);",  ['title'=>'deleteLots','class' => 'delete-lots btn btn-danger']) ?>
        <?= Html::a('下载模板', ['template'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('批量审批',"javascript:void(0);",  ['title'=>'approveLots','class' => 'approve-lots btn btn-warning']) ?>
        <input type="file" id="import" name="import" style="display: none" >
    </p>
    <?= GridView::widget([
        'bootstrap' => true,
        'responsive'=>true,
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'id' => 'oa-check',
        'columns' => [
            [
                'class' => 'yii\grid\CheckboxColumn',
            ],
            ['class' => 'kartik\grid\SerialColumn'],

            [ 'class' => 'kartik\grid\ActionColumn',
                'template' =>'{view} {update} {delete} {approve}',
                'buttons' => [

                    'delete' => function ($url, $model, $key) {
                        $options = [
                            'title' => '删除',
                            'aria-label' => '删除',
                            'data-id' => $key,
                            'class' => 'backward-delete',
                        ];
                        return Html::a('<span  class="glyphicon glyphicon-trash"></span>', '#', $options);
                    },
                    'view' => function ($url, $model, $key) {
                        $options = [
                            'title' => '查看',
                            'aria-label' => '查看',
                            'data-toggle' => 'modal',
                            'data-target' => '#backward-modal',
                            'data-id' => $key,
                            'class' => 'backward-view',
                        ];
                        return Html::a('<span  class="glyphicon glyphicon-eye-open"></span>', '#', $options);
                    },
                    'update' => function ($url, $model, $key) {
                        $options = [
                            'title' => '更新',
                            'aria-label' => '更新',
                            'data-toggle' => 'modal',
                            'data-target' => '#backward-modal',
                            'data-id' => $key,
                            'class' => 'backward-update',
                        ];
                        return Html::a('<span  class="glyphicon glyphicon-pencil"></span>', '#', $options);
                    },
                    'approve' => function ($url, $model, $key) {
                        $options = [
                            'title' => '提交审核',
                            'aria-label' => '提交审核',
                            'data-id' => $key,
                            'class' => 'approve',
                        ];
                        return Html::a('<span  class="glyphicon  glyphicon-check"></span>', '#', $options);
                    },
                ],
            ],

            centerFormat('img'),
            centerFormat('cate'),
            centerFormat('subCate'),
            centerFormat('vendor1'),
//            centerFormat('vendor2'),
//            centerFormat('vendor3'),
            centerFormat('origin1'),
//            centerFormat('origin2'),
//            centerFormat('origin3'),
            centerFormat('devNum'),
            centerFormat('developer'),
            centerFormat('introducer'),
//            centerFormat('devStatus'),
            centerFormat('checkStatus'),
            centerFormat('approvalNote'),
            centerFormat('createDate'),
            centerFormat('updateDate'),
            centerFormat('salePrice'),
            centerFormat('hopeWeight'),
            centerFormat('hopeRate'),
            centerFormat('hopeSale'),
            centerFormat('hopeMonthProfit'),


        ],
    ]); ?>
</div>

