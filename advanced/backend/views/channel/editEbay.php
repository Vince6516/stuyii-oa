<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use yii\helpers\Url;
use yii\bootstrap\Tabs;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $model backend\models\Channel */


$this->title =  '更新平台信息';
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '平台信息'), 'url' => ['index']];
$shipping_templates = [
    "template"=>"<div > {label} </div><div class='col-lg-6'>{input}</div>{hint}{error}",
    'labelOptions' => ['class' => 'col-lg-2 control-label']
                ];


$templatesVarUrl = Url::toRoute('templates-var'); // 多属性连接

//创建模态框
use yii\bootstrap\Modal;
Modal::begin([
    'id' => 'templates-modal',
    'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">关闭</a>',
    'size' => "modal-xl"
]);
//echo
Modal::end();
?>
<?=
 Tabs::widget([

    'items' => [
        [
            'label' => 'Wish',
            'url' => Url::to(['update', 'id' => $templates->infoid]),

            'headerOptions' => ["id" => 'tab1'],
            'options' => ['id' => 'article'],

        ],
        [
            'label' => 'eBay',
            'url' => Url::to(['update-ebay', 'id' => $templates->infoid]),
            'headerOptions' => ["id" => 'tab1'],
            'options' => ['id' => 'topic'],
            'active' => true,
        ],


    ],
]);?>

</br>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css">
<div class="st">
    <div class="row top">
        <div class="col-sm-3">
        <?= Html::button('保存当前数据', ['class' =>' save-only btn btn-default']) ?>

        <?= Html::button('保存并完善', ['class' =>'save-complete btn btn-default']) ?>

        </div>
        <div class="col-sm-2">
            <select class="selectpicker ebay-chosen-up" multiple data-actions-box="true" title="--所有账号--">
        <?php
        foreach ($ebayAccount as $account=>$suffix) {

            echo '<option class="ebay-select" value="'.$suffix.'">'.$suffix.'</option>';
        }
        ?>
            </select>
        </div>
            <div class="col-sm-1">
                <?php echo Html::button('导出所选账号模板',['class' =>'top-export-ebay-given btn btn-default'])    ?>
            </div>

    </div>
</div>
</br>

<?php $form = ActiveForm::begin([
    'id' => 'msg-form',
    'options' => ['class'=>'form-horizontal'],
    'enableAjaxValidation'=>false,
    'fieldConfig' => [
        'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
        'labelOptions' => ['class' => 'col-lg-1 control-label'],
    ]
]);
?>

<div class="blockTitle">
    <span>基本信息</span>
</div>
</br>
<?= $form->field($templates,'sku')->textInput()?>
<?php
echo $form->field($templates,'mainPage')->textInput(['class'=>'main-page','style'=>'display:none']);
echo '<div class="form-group field-oatemplates-mainpage">
    <label class="col-lg-1 control-label"></label>
    <div class="col-lg-3"><input type="text" class="form-control tem-page" value="'.$templates->mainPage.'"></div>

    <div class="col-lg=1"> 
    <a target="_blank" href="'.$templates->mainPage.'">
    <img src="'.$templates->mainPage.'" width="50" height="50">
    </a>
    </div>
</div>'
;?>
<?= $form->field($templates,'extraPage')->textarea(['style'=>'display:none']); ?>
<?php
echo '<div class="images">';
$images = json_decode($templates->extraPage,true)['images'];
foreach($images as $key=>$image){
    echo '<div class="form-group all-images">
    <label class="col-lg-1"></label>
    <div class="col-lg-3"><input  type="text" class="form-control extra-images" value="'.$image.'"></div>
    <div class="col-lg=1">
    <strong class="serial">#'.($key+1).'</strong>
    <button  class="btn add-images">增加</button>
    <button  class="btn btn-error remove-image">删除</button>
    <button class="btn up-btn btn-error">上移动</button>
    <button class="btn down-btn btn-error">下移动</button>
    <a target="_blank" href="'.$image.'">
    <img src="'.$image.'" width="50" height="50">
    </a>
    </div>
</div>';
}
echo '</div>';
?>
</br>
<?= $form->field($templates,'location')->textInput(); ?>
<?= $form->field($templates,'country')->textInput(); ?>
<?= $form->field($templates,'postCode')->textInput(); ?>
<?= $form->field($templates,'prepareDay')->textInput(['value' => '10' ]); ?>

<div class="blockTitle">
    <span> 站点组</span>
</div>
</br>
<?= $form->field($templates,'site')->dropDownList([0=>'美国站',3=>'英国站',15=>'澳大利亚站']); ?>
<div class="blockTitle">
    <span> 多属性</span>
</div>
</br>
<div>
<a  data-toggle="modal" data-target="#templates-modal" class=" var-btn btn btn-default ">设置多属性</a>
    </div>
</br>
<div class="blockTitle">
    <span> 主信息</span>
</div>
</br>
<div>
<?= $form->field($templates,'listedCate')->textInput(); ?>
<?= $form->field($templates,'listedSubcate')->textInput(); ?>
<?= $form->field($templates,'title')->textInput(); ?>
<?= $form->field($templates,'subTitle')->textInput(); ?>
<?= $form->field($templates,'description')->textarea(['rows'=>6]); ?>
<?= $form->field($templates,'quantity')->textInput(); ?>
<?= $form->field($templates,'nowPrice')->textInput(); ?>
<?= $form->field($templates,'UPC')->textInput(['value' => 'Does not apply']); ?>
<?= $form->field($templates,'EAN')->textInput(['value' => 'Does not apply']); ?>
</div>

<div class="blockTitle">
    <span>物品属性</span>
</div>
</br>
<div>
    <?= $form->field($templates,'specifics')->textarea(['style'=>'display:none'])->label(false); ?>
    <?php
    $specifics = json_decode($templates->specifics,true)['specifics'];
    echo
    '<div class="row"><div class="col-lg-6"><table class="specifics-tab table table-hover">
    <thead>
    <tr>
    <th>属性名称</th>
    <th>属性内容</th>
    </tr>
    </thead>
    <tbody>';
    foreach($specifics as $row){
        echo '<tr><th><input name="specificsKey" value="'.array_keys($row)[0].'"></th>
    <td><input size="40" calss="specifics-value" value="'.array_values($row)[0].'">
    <input type="button" value="删除" onclick="$(this.parentNode.parentNode).remove()"></td></tr>';
    }
    echo
    '</tbody>
    </table>
    <button class=" add-specifics btn btn-default">增加属性</button>
    </div></div>';
    ?>
</div>
</br>
<div class="blockTitle">
    <span>物流设置</span>

</div>
</br
<div>
<div class="row" >
    <div class="col-lg-6">
    <span>境内运输方式</span>
<?=
$form->field($templates,'InshippingMethod1',$shipping_templates)->dropDownList($inShippingService,
    [
        'class' => 'col-lg-6',
        'prompt'=>'--境内物流选择--',
    ]
); ?>
<?= $form->field($templates,'InFirstCost1',$shipping_templates)->textInput(['placeholder' => '--USD--']); ?>
<?= $form->field($templates,'InSuccessorCost1',$shipping_templates)->textInput(['placeholder' => '--USD--']); ?>
        <?=
        $form->field($templates,'InshippingMethod2',$shipping_templates)->dropDownList($inShippingService,
            [
                'class' => 'col-lg-6',
                'prompt'=>'--境内物流选择--',
            ]
        ); ?>
<?= $form->field($templates,'InFirstCost2',$shipping_templates)->textInput(['placeholder' => '--USD--']); ?>
<?= $form->field($templates,'InSuccessorCost2',$shipping_templates)->textInput(['placeholder' => '--USD--']); ?>
    </div>
    <div class="col-lg-6">
    <span>境外运输方式</span>
        <?=
        $form->field($templates,'OutshippingMethod1',$shipping_templates)->dropDownList($outShippingService,
            [
                'class' => 'col-lg-6',
                'prompt'=>'--境外物流选择--',
            ]
        ); ?>
    <?= $form->field($templates,'OutFirstCost1',$shipping_templates)->textInput(['placeholder' => '--USD--']); ?>
    <?= $form->field($templates,'OutSuccessorCost1',$shipping_templates)->textInput(['placeholder' => '--USD--']); ?>
        <?=
        $form->field($templates,'OutshippingMethod2',$shipping_templates)->dropDownList($outShippingService,
            [
                'class' => 'col-lg-6',
                'prompt'=>'--境外物流选择--',
            ]
        ); ?>
    <?= $form->field($templates,'OutFirstCost2',$shipping_templates)->textInput(['placeholder' => '--USD--']); ?>
    <?= $form->field($templates,'OutSuccessorCost2',$shipping_templates)->textInput(['placeholder' => '--USD--']); ?>
    </div>

</div>
<br>
    <hr>
    <div class="st">
        <div class="row bottom">
            <div class="col-sm-3">
                <?= Html::button('保存当前数据', ['class' =>' save-only btn btn-default']) ?>

                <?= Html::button('保存并完善', ['class' =>'save-complete btn btn-default']) ?>

            </div>
            <div class="col-sm-2">
                <select class="selectpicker ebay-chosen-down" multiple data-actions-box="true" title="--所有账号--">
                    <?php
                    foreach ($ebayAccount as $account=>$suffix) {

                        echo '<option class="ebay-select" value="'.$suffix.'">'.$suffix.'</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="col-sm-1">
                <?php echo Html::button('导出所选账号模板',['class' =>'bottom-export-ebay-given btn btn-default'])    ?>
            </div>

        </div>
    </div>
</div>


<?php ActiveForm::end() ?>

<style>
    .blockTitle {
        font-size: 16px;
        background-color: #f7f7f7;
        border-top: 0.5px solid #eee;
        border-bottom: 0.5px solid #eee;
        padding: 2px 12px;
        margin-left: -5px;
    }
    .blockTitle span{
        margin-top: 20px;
        font-weight: bold;
    }
</style>

<?php
$exportUlr = URL::toRoute(['export-ebay','id'=>$templates->nid]);
$shippingUlr = URL::toRoute(['shipping']);
$saveUrl = Url::to(['ebay-save', 'id' => $templates->nid]);
$completeUrl = Url::to(['ebay-complete', 'id' => $templates->nid]);

$js  = <<< JS


//绑定监听事件根据站点的选择来决定物流

$('#oatemplates-site').change(function() {
  loadShipping(); 
});

//站点加载出来的时候也要重新加载下物流
loadShipping();
function loadShipping() {
  var select = $('#oatemplates-site option:selected').val();
  //获取站点的值
  var promt_in = '<option value="">--境内物流--</option>';
  var promt_out = '<option value="">--境外物流--</option>';
  //三个物流逐句选择
  $.get("{$shippingUlr}",{type:'InFir',site_id:select},function(ret) {
        ret = promt_in + ret;
        $('#oatemplates-inshippingmethod1').html(ret);
  });
  $.get("{$shippingUlr}",{type:'InSec',site_id:select},function(ret) {
        ret = promt_in + ret;
        $('#oatemplates-inshippingmethod2').html(ret);
  });
  $.get("{$shippingUlr}",{type:'OutFir',site_id:select},function(ret) {
        ret = promt_out + ret;
        $('#oatemplates-outshippingmethod1').html(ret);
  });
}
//主图赋值
$('.main-page').val($('.tem-page').val());

//监听主图变化事件
$(".tem-page").on('change',function() {
    //图片切换
    new_image = $(this).val();
    $(this).parents('div .form-group').find('a').attr('href',new_image);
    $(this).parents('div .form-group').find('img').attr('src',new_image);
    //更新主图值
    $('.main-page').val(new_image);
})

//导出模板
$(".export-ebay").on('click',function() {
    window.location.href='{$exportUlr}';
});


//如果图片地址不改变就直接用原始数据
    function allImags() {
        var images = new Array();
        $('.extra-images').each(function() {
        images.push($(this).val());
        });
        $('#oatemplates-extrapage').val(JSON.stringify({'images':images}));
    }
    allImags();


// 生成图片序列
function serialize() {
    i=0;
    $(".serial").each(function() {
        i++;
        $(this).text("#" + i);
    });
}


//增加图片
function addImages() {
       
  total = 0;//判断当前图片数量
  $(".serial").each(function() {
        total++;
    });

    if(total<12){
        row = '<div class="form-group all-images">' +
    '<label class="col-lg-1"></label>' +
    '<strong class="serial">#</strong>'+
    '<div class="col-lg-3"><input type="text" class="form-control extra-images" ></div>'+
    '<div class="col-lg=1">'+
    '<button class="btn add-images">增加</button> '+
    '<button class="btn btn-error remove-image">删除</button> '+
    '<button class="btn up-btn btn-error">上移动</button> '+
    '<button class="btn down-btn btn-error">下移动</button> '+
    '<a target="_blank" href="">'+
    '<img src="" width="50" height="50">'+
    '</a>'+
    '</div>'+
    '</div>';
        $('.images').append(row);
        //重新计算序列
        serialize();
    }
}


//增加属性的按钮
$('.add-specifics').on('click',function() {
    var key = '<tr><th><input  type="text" name="specificsKey"></th>';
    var value = '<td><input type="text" size="40" name="specficsValue"> ';
    var delBtn = '<input type="button" value="删除" onclick="$(this.parentNode.parentNode).remove()"></td></tr>';
    $('.specifics-tab').append(key + value + delBtn);
    return false;
});


// 初始化属性JSON
    function allSpecifics() {
        textarea = $('#oatemplates-specifics');
        var specifics = [];
        $('.specifics-tab').find('input[size="40"]').each(function() {
            var key = $(this).parents('tr').find('input[name="specificsKey"]').val();
            var value = $(this).val();
            var map ={};
            map[key] = value;
            specifics.push(map);
        });
        textarea.text(JSON.stringify({specifics:specifics}));
    }
allSpecifics();


// 属性变化事件,及时刷新JSON事件。
$('.specifics-tab').on('change','input[size="40"]',function() {
    allSpecifics();
});


$('.specifics-tab').on('change','input[name="specificsKey"]',function() {
    allSpecifics();
});


//绑定上移事件
$('body').on('click','.up-btn',function() {
    var point = $(this).closest('div .form-group').find('strong').text().replace('#','');
    if(point > 1){
        var temp = $(this).closest('div .all-images').clone(true);
        $(this).closest('div .all-images').prev().before(temp);
        $(this).closest('div .all-images').remove();
        serialize();
        //重新生成JSON
        var images = new Array();
        $('.extra-images').each(function() {
        images.push($(this).val());
        });
        $('#oatemplates-extrapage').val(JSON.stringify({'images':images}));
    }
    return false;
});


//绑定下移事件
$('body').on('click','.down-btn',function() {
    var point = $(this).closest('div .form-group').find('strong').text().replace('#','');
    if(point < 12){
        var temp = $(this).closest('div .all-images').clone(true);
        $(this).closest('div .all-images').next().after(temp);
        $(this).closest('div .all-images').remove();
        serialize();
        //重新生成JSON
        var images = new Array();
        $('.extra-images').each(function() {
        images.push($(this).val());
        });
        $('#oatemplates-extrapage').val(JSON.stringify({'images':images}));
    }
    return false;
});


//绑定增加按钮事件
$('body').on('click','.add-images',function() {
    addImages();
    return false;
    });
    
    
//删除附加图
$('body').on('click','.remove-image',function() {
    $(this).closest('div .form-group').remove();
    allImags();//重新生成JSON
    serialize();//重新生成序列
        
        
});


//实时刷新图片
$('body').on('change','.extra-images',function() {
    new_image = $(this).val();
    $(this).parents('div .form-group').find('a').attr('href',new_image);
    $(this).parents('div .form-group').find('img').attr('src',new_image);
});

//绑定事件, 实时封装JSON数据
$('body').on('click','.all-images',function() {
    var text = '';
    var images = new Array();
    $('.extra-images').each(function() {
        images.push($(this).val());
    });
    $('#oatemplates-extrapage').val(JSON.stringify({'images':images}));
});


// 多属性设置模态框
$(".var-btn").click(function() {
    $('.modal-body').children('div').remove(); //清空数据
    $.get('{$templatesVarUrl}',{id:{$templates->nid}},
        function(data) {
            $('.modal-body').html(data);
        }
    );
});


//保存按钮
$('.save-only').on('click',function() {
    $.ajax({
        url:'{$saveUrl}',
        type:'post',
        data:$('#msg-form').serialize(),
        success:function(ret) {
            alert(ret);
        }
    });
});

//保存并完善按钮
$('.save-complete').on('click',function() {
    $(this).attr('disabled','disabled');
    var button = $(this);
    $.ajax({
        url:'{$completeUrl}&infoId={$infoId}',
        type:'post',
        data:$('#msg-form').serialize(),
        success:function(ret) {
            alert(ret);
            button.attr('disabled',false);

        }
    });
});


//顶部导出所选账号模板
$('.top-export-ebay-given').on('click',function() {
    names = $('.top').find('.selectpicker').val();
    if(!names){
        names = '';
    }
    window.location.href='{$exportUlr}'+ '&accounts='+names;
});

//底部导出所选账号模板
$('.bottom-export-ebay-given').on('click',function() {
    names = $('.bottom').find('.selectpicker').val();
    if(!names){
        names = '';
    }
    window.location.href='{$exportUlr}'+ '&accounts='+names;
});
JS;
$this->registerJs($js);
?>

<!--<script src="http://58.246.226.254:8090/Public/js/bootstrap-select.min.js"></script>-->

<style>
    @media (min-width: 768px) {
        .modal-xl {
            width: 70%;
            /*max-width:1200px;*/
        }
    }
</style>

<link rel="stylesheet" href="../css/bootstrap-select.min.css">
<script src="../plugins/jquery/1.12.3/jquery.js"></script>
<script src="../plugins/bootstrap-select/bootstrap-select.min.js"></script>

