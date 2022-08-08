<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\example\TblVendor;
use app\models\example\TblMeasurementSystem;
?>
    <?php
    $form = ActiveForm::begin();
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
                ->input('datetime-local')->label('Date') ?>
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
    <table class="table table-bordered" id="table">
        <thead>
        <tr class="tr_name">
            <th style="color: red">STYLE NO</th>
            <th style="color: red">UOM</th>
            <th style="color: red">PREFIX</th>
            <th style="color: red">SUFIX</th>
            <th style="color: red">HEIGHT</th>
            <th style="color: red">WIDTH</th>
            <th style="color: red">LENGTH</th>
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
            <td><?= $form->field($style_no, "style_no[1]")
                    ->input('text', ['placeholder' => "style_no"])->label(false) ?></td>

            <td><?= $form->field($style_no, "uom[1]")
                    ->input('text', ['placeholder' => "uom"])->label(false) ?></td>

            <td><?= $form->field($style_no, "prefix[1]")
                    ->input('text', ['placeholder' => "prefix"])->label(false) ?></td>

            <td><?= $form->field($style_no, "sufix[1]")
                    ->input('text', ['placeholder' => "sufix"])->label(false) ?></td>

            <td><?= $form->field($style_no, "height[1]")
                    ->input('text', ['placeholder' => "height"])->label(false) ?></td>

            <td><?= $form->field($style_no, "width[1]")
                    ->input('text', ['placeholder' => "width"])->label(false) ?></td>

            <td><?= $form->field($style_no, "length[1]")
                    ->input('text', ['placeholder' => "length"])->label(false) ?></td>

            <td><?= $form->field($style_no, "upc[1]")
                    ->input('text', ['placeholder' => "upc"])->label(false) ?></td>

            <td><?= $form->field($style_no, "size_1[1]")
                    ->input('text', ['placeholder' => "size"])->label(false) ?></td>

            <td><?= $form->field($style_no, "color_1[1]")
                    ->input('text', ['placeholder' => "color_1"])->label(false) ?></td>

            <td><?= $form->field($style_no, "size_2[1]")
                    ->input('text', ['placeholder' => "size_2"])->label(false) ?></td>

            <td><?= $form->field($style_no, "color_2[1]")
                    ->input('text', ['placeholder' => "color_2"])->label(false) ?></td>

            <td><?= $form->field($style_no, "size_3[1]")
                    ->input('text', ['placeholder' => "size_3"])->label(false) ?></td>

            <td><?= $form->field($style_no, "color_3[1]")
                    ->input('text', ['placeholder' => "color_3"])->label(false) ?></td>

            <td><?= $form->field($style_no, "carton[1]")
                    ->input('text', ['placeholder' => "carton"])->label(false) ?></td>
        </tr>
        </tbody>
    </table>

    <div class="form-inline">
        <span id="add_row">Add row</span>
        <input class="form-control" type="text" id="row" size="2" value="1"> &emsp; #row<br>
    </div><br>

    <script>
        $(document).ready(function(){
            $("#add_row").click(function(){
                if($("#row").val() == 1){
                    var length = $('.tr_name').length;
                    $('#table tr:last').after('<tr class="tr_name">' +
                        '<td><input type="text" class="form-control" name="TblSystemNo[style_no]['+length+']"</td>' +
                        '<td><input type="text" class="form-control" name="TblSystemNo[uom]['+length+']"</td>' +
                        '<td><input type="text" class="form-control" name="TblSystemNo[prefix]['+length+']"</td>' +
                        '<td><input type="text" class="form-control" name="TblSystemNo[sufix]['+length+']"</td>' +
                        '<td><input type="text" class="form-control" name="TblSystemNo[height]['+length+']"</td>' +
                        '<td><input type="text" class="form-control" name="TblSystemNo[width]['+length+']"</td>' +
                        '<td><input type="text" class="form-control" name="TblSystemNo[length]['+length+']"</td>' +
                        '<td><input type="text" class="form-control" name="TblSystemNo[upc]['+length+']"</td>' +
                        '<td><input type="text" class="form-control" name="TblSystemNo[size_1]['+length+']"</td>' +
                        '<td><input type="text" class="form-control" name="TblSystemNo[color_1]['+length+']"</td>' +
                        '<td><input type="text" class="form-control" name="TblSystemNo[size_2]['+length+']"</td>' +
                        '<td><input type="text" class="form-control" name="TblSystemNo[color_2]['+length+']"</td>' +
                        '<td><input type="text" class="form-control" name="TblSystemNo[size_3]['+length+']"</td>' +
                        '<td><input type="text" class="form-control" name="TblSystemNo[color_3]['+length+']"</td>' +
                        '<td><input type="text" class="form-control" name="TblSystemNo[carton]['+length+']"</td>' +
                        '</tr>');
                }else{
                    let i=1;
                    var length = $('.tr_name').length;
                    for(i; i<=$('#row').val(); i++){
                        $('#table tr:last').after('<tr class="tr_name">' +
                            '<td><input type="text" class="form-control" name="TblSystemNo[style_no]['+length+']"</td>' +
                            '<td><input type="text" class="form-control" name="TblSystemNo[uom]['+length+']"</td>' +
                            '<td><input type="text" class="form-control" name="TblSystemNo[prefix]['+length+']"</td>' +
                            '<td><input type="text" class="form-control" name="TblSystemNo[sufix]['+length+']"</td>' +
                            '<td><input type="text" class="form-control" name="TblSystemNo[height]['+length+']"</td>' +
                            '<td><input type="text" class="form-control" name="TblSystemNo[width]['+length+']"</td>' +
                            '<td><input type="text" class="form-control" name="TblSystemNo[length]['+length+']"</td>' +
                            '<td><input type="text" class="form-control" name="TblSystemNo[upc]['+length+']"</td>' +
                            '<td><input type="text" class="form-control" name="TblSystemNo[size_1]['+length+']"</td>' +
                            '<td><input type="text" class="form-control" name="TblSystemNo[color_1]['+length+']"</td>' +
                            '<td><input type="text" class="form-control" name="TblSystemNo[size_2]['+length+']"</td>' +
                            '<td><input type="text" class="form-control" name="TblSystemNo[color_2]['+length+']"</td>' +
                            '<td><input type="text" class="form-control" name="TblSystemNo[size_3]['+length+']"</td>' +
                            '<td><input type="text" class="form-control" name="TblSystemNo[color_3]['+length+']"</td>' +
                            '<td><input type="text" class="form-control" name="TblSystemNo[carton]['+length+']"</td>' +
                            '</tr>');
                        length = length + 1;
                    }
                }

            });
        });
    </script>
<?php
echo Html::submitButton('Save', [ 'class'=>"btn btn-primary"]);


ActiveForm::end();
?>
    <br><a href="">Main Menu</a>
    </body>
</html>
