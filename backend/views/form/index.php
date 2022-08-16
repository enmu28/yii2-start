<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\example\TblVendor;
use app\models\example\TblMeasurementSystem;
use backend\assets\BackendAsset;

?>
    <?php
    $form = ActiveForm::begin([
//        'enableAjaxValidation'=>true,
        'id' => 'form_active',
//        'enableClientValidation'=>false
            ]);
    ?>
    <div class="row">
        <div class="col-4 form-inline">
            <?php
            echo $form->field($container, 'id_vendor')
                ->dropDownList(ArrayHelper::map(TblVendor::find()->all(),'id','name'),['prompt'=>'Select Vendor'])
                ->label('Vendor');
            ?>
        </div>
        <div class="col-4 form-inline">
            <?php
            echo $form->field($container, 'id_measurement_system')
                ->dropDownList(ArrayHelper::map(TblMeasurementSystem::find()->all(),'id','name'),['prompt'=>'Select System'])
                ->label('Measurement System');
            ?>
        </div>
        <div class="col-4 form-inline">
            <?= $form->field($container, "created_at")
                ->input('date')->label('Date') ?>
        </div>
    </div>

    <div class="row" style="margin-top: 10px">
        <div class="col-4 form-inline">
            <?= $form->field($container, "id")
                ->input('text', ['placeholder'=> 'ID container'])->label('Container# :') ?>
        </div>
    </div>
    <div class="row" style="margin-top: 10px">
        <div class="col-4 form-inline">
            <?= $form->field($container, "price")
                ->input('text', ['placeholder'=> 'Price'])->label('Receiving# :') ?>
        </div>

    </div>

    <br><br>
    <?php  if(Yii::$app->session->hasFlash('messenge_error')){
        echo Yii::$app->session->getFlash('messenge_error');
    }
    ?>
    <table class="table table-bordered" id="table">
        <thead>
        <tr>
            <th style="color: red">STYLE NO</th>
            <th style="color: red">UOM</th>
            <th style="color: red">PREFIX</th>
            <th style="color: red">SUFIX</th>
            <th style="color: red">HEIGHT</th>
            <th style="color: red">WIDTH</th>
            <th style="color: red">LENGTH</th>
            <th style="color: red">WEIGHT</th>
            <th style="color: red">UPC</th>
            <th style="color: red">SIZE 1</th>
            <th style="color: red">COLOR 1</th>
            <th>SIZE 2</th>
            <th>COLOR 2</th>
            <th>SIZE 3</th>
            <th>COLOR 3</th>
            <th style="color: red">CARTON</th>
        </tr>
        </thead>
        <tbody>
        <tr class="tr_name">
            <td>
                <div class="form-group">
                    <input type="text" class="form-control" id="style-no-1" name="TblStyleNo[style_no][1]" onblur="check_val('style-no-1')">
                    <div id="error-style-no-1"></div>
                </div>
            </td>
            <td>
                <div class="form-group">
                    <input type="text" class="form-control" id="uom-1" name="TblStyleNo[uom][1]" onblur="check_val('uom-1')">
                    <div id="error-uom-1"></div>
                </div>
            </td>
            <td>
                <div class="form-group">
                    <input type="text" class="form-control" id="prefix-1" name="TblStyleNo[prefix][1]" onblur="check_val('prefix-1')">
                    <div id="error-prefix-1"></div>
                </div>
            </td>
            <td>
                <div class="form-group">
                    <input type="text" class="form-control" id="sufix-1" name="TblStyleNo[sufix][1]" onblur="check_val('sufix-1')">
                    <div id="error-sufix-1"></div>
                </div>
            </td>
            <td>
                <div class="form-group">
                    <input type="text" class="form-control" id="height-1" name="TblStyleNo[height][1]" onblur="check_val('height-1')">
                    <div id="error-height-1"></div>
                </div>
            </td>
            <td>
                <div class="form-group">
                    <input type="text" class="form-control" id="width-1" name="TblStyleNo[width][1]" onblur="check_val('width-1')">
                    <div id="error-width-1"></div>
                </div>
            </td>
            <td>
                <div class="form-group">
                    <input type="text" class="form-control" id="length-1" name="TblStyleNo[length][1]" onblur="check_val('length-1')">
                    <div id="error-length-1"></div>
                </div>
            </td>
            <td>
                <div class="form-group">
                    <input type="text" class="form-control" id="weight-1" name="TblStyleNo[weight][1]" onblur="check_val('weight-1')">
                    <div id="error-weight-1"></div>
                </div>
            </td>
            <td>
                <div class="form-group">
                    <input type="text" class="form-control" id="upc-1" name="TblStyleNo[upc][1]" onblur="check_val('upc-1')">
                    <div id="error-upc-1"></div>
                </div>
            </td>
            <td>
                <div class="form-group">
                    <input type="text" class="form-control" id="size_1-1" name="TblStyleNo[size_1][1]" onblur="check_val('size_1-1')">
                    <div id="error-size_1-1"></div>
                </div>
            <td>
                <div class="form-group">
                    <input type="text" class="form-control" id="color_1-1" name="TblStyleNo[color_1][1]" onblur="check_val('color_1-1')">
                    <div id="error-color_1-1"></div>
                </div>
            <td><input type="text" class="form-control" name="TblStyleNo[size_2][1]"></td>
            <td><input type="text" class="form-control" name="TblStyleNo[color_2][1]"></td>
            <td><input type="text" class="form-control" name="TblStyleNo[size_3][1]"></td>
            <td><input type="text" class="form-control" name="TblStyleNo[color_3][1]"></td>
            <td>
                <div class="form-group">
                    <input type="text" class="form-control" id="carton-1" name="TblStyleNo[carton][1]" onblur="check_val('carton-1')">
                    <div id="error-carton-1"></div>
                </div>
            </td>
        </tr>
        </tbody>
    </table>

    <div class="form-inline">
        <span id="add_row">Add row</span>
        <input class="form-control" type="text" id="row" size="2" value="1"> &emsp; #row<br>
    </div><br>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.slim.min.js"></script>
    <script>
        $(document).ready(function(){
            $("#add_row").click(function(){
                if($("#row").val() != 1){
                    let i=1;
                    let row_ad = 0;
                    let row_del = 0
                    let length = $('.tr_name').length + 1;
                    if($("#row").val() > $('.tr_name').length){
                        row_ad = $("#row").val() - $('.tr_name').length;
                    }else if($("#row").val() < $('.tr_name').length){
                        row_del = $('.tr_name').length - $("#row").val();
                        for(i; i<=row_del; i++){
                            $('#table tr:last-child').remove();
                        }
                    }
                    for(i; i<=row_ad; i++){
                        $('#table tr:last').after('<tr class="tr_name">' +
                            '<td><div class="form-group">'+
                            '<input type="text" class="form-control" id="style-no-'+length+'" name="TblStyleNo[style_no]['+length+']" onblur="check_val(\'style-no-'+length+'\')">'+
                            '<div id="error-style-no-'+length+'"></div>'+
                            '</div></td>' +

                            '<td><div class="form-group">'+
                            '<input type="text" class="form-control" id="uom-'+length+'" name="TblStyleNo[uom]['+length+']" onblur="check_val(\'uom-'+length+'\')">'+
                            '<div id="error-uom-'+length+'"></div>'+
                            '</div></td>' +

                            '<td><div class="form-group">'+
                            '<input type="text" class="form-control" id="prefix-'+length+'" name="TblStyleNo[prefix]['+length+']" onblur="check_val(\'prefix-'+length+'\')">'+
                            '<div id="error-prefix-'+length+'"></div>'+
                            '</div></td>' +

                            '<td><div class="form-group">'+
                            '<input type="text" class="form-control" id="sufix-'+length+'" name="TblStyleNo[sufix]['+length+']" onblur="check_val(\'sufix-'+length+'\')">'+
                            '<div id="error-sufix-'+length+'"></div>'+
                            '</div></td>' +

                            '<td><div class="form-group">'+
                            '<input type="text" class="form-control" id="height-'+length+'" name="TblStyleNo[height]['+length+']" onblur="check_val(\'height-'+length+'\')">'+
                            '<div id="error-height-'+length+'"></div>'+
                            '</div></td>' +

                            '<td><div class="form-group">'+
                            '<input type="text" class="form-control" id="width-'+length+'" name="TblStyleNo[width]['+length+']" onblur="check_val(\'width-'+length+'\')"">'+
                            '<div id="error-width-'+length+'"></div>'+
                            '</div></td>' +

                            '<td><div class="form-group">'+
                            '<input type="text" class="form-control" id="length-'+length+'" name="TblStyleNo[length]['+length+']" onblur="check_val(\'length-'+length+'\')">'+
                            '<div id="error-length-'+length+'"></div>'+
                            '</div></td>' +

                            '<td><div class="form-group">'+
                            '<input type="text" class="form-control" id="weight-'+length+'" name="TblStyleNo[weight]['+length+']" onblur="check_val(\'weight-'+length+'\')">'+
                            '<div id="error-weight-'+length+'"></div>'+
                            '</div></td>' +

                            '<td><div class="form-group">'+
                            '<input type="text" class="form-control" id="upc-'+length+'" name="TblStyleNo[upc]['+length+']" onblur="check_val(\'upc-'+length+'\')">'+
                            '<div id="error-upc-'+length+'"></div>'+
                            '</div></td>' +

                            '<td><div class="form-group">'+
                            '<input type="text" class="form-control" id="size_1-'+length+'" name="TblStyleNo[size_1]['+length+']" onblur="check_val(\'size_1-'+length+'\')">'+
                            '<div id="error-size_1-'+length+'"></div>'+
                            '</div></td>' +

                            '<td><div class="form-group">'+
                            '<input type="text" class="form-control" id="color_1-'+length+'" name="TblStyleNo[color_1]['+length+']" onblur="check_val(\'color_1-'+length+'\')">'+
                            '<div id="error-color_1-'+length+'"></div>'+
                            '</div></td>' +

                            '<td><input type="text" class="form-control" name="TblStyleNo[size_2]['+length+']"></td>' +

                            '<td><input type="text" class="form-control" name="TblStyleNo[color_2]['+length+']"></td>' +

                            '<td><input type="text" class="form-control" name="TblStyleNo[size_3]['+length+']"></td>' +

                            '<td><input type="text" class="form-control" name="TblStyleNo[color_3]['+length+']"></td>' +

                            '<td><div class="form-group">'+
                            '<input type="text" class="form-control" id="carton-'+length+'" name="TblStyleNo[carton]['+length+']" onblur="check_val(\'carton-'+length+'\')">'+
                            '<div id="error-carton-'+length+'"></div>'+
                            '</div></td>' +
                            '</tr>');
                        length = length + 1;
                    }
                }

            });
        });
        function check_val(id){
            var re=/^\d+$/;
            let k = 0;
            if($("#"+id).val() == ''){
                document.getElementById("error-"+id).innerText="Not null"
                return false;
            }else if(re.test($("#"+id).val().trim())==false || $("#"+id).val() <= 0){
                document.getElementById("error-"+id).innerText="Error datatype!";
                return false;
            }else{
                document.getElementById("error-"+id).innerText="";
                return true;
            }
        }
        function check_validate() {
            let k = 0;
            for(let i=1; i<=$('.tr_name').length + 1; i++){
                if(check_val('style-no-'+i) != true && check_val('uom-'+i) != true && check_val('prefix-'+i) != true && check_val('sufix-'+i) != true && check_val('height-'+i)!=true && check_val('width-'+i) != true && check_val('length-'+i) != true && check_val('weight-'+i) != true && check_val('upc-'+i) != true && check_val('size_1-'+i) != true && check_val('color_1-'+i) != true  && check_val('carton-'+i) != true){
                    k = 1;
                }
            }
            if(k == 1){
                return false;
            }else{
                return true;
            }
        }
    </script>
<button type="submit" class="btn btn-primary submit" onclick="check_validate()">Save</button>
<?php

ActiveForm::end();
?>

    <br><?= Html::a('Main menu', ['form/show'], ['class' => 'profile-link']) ?>
