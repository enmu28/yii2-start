<?php

use app\models\example\TblMeasurementSystem;
use app\models\example\TblVendor;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<?php
$form = ActiveForm::begin();
?>
<?= Html::tag('h5', 'Update Container'); ?>
<?php if (Yii::$app->session->hasFlash('success')): ?>
    <span style="color: orangered">
        <?= Yii::$app->session->getFlash('success'); ?>
    </span>
<?php endif; ?>
<Br>
<div class="row">
    <div class="col-4 form-inline">
        Vendor : &emsp;
        <select name="redis_container_vendor" id="" class="form-control">
            <?php
            if(!empty($tbl_vendor)){
                foreach($tbl_vendor as $value){
                    if($redis_container->vendor == $value->id){
                        echo "<option value='$value->id' selected>$value->name</option>";
                    }else{
                        echo "<option value='$value->id'>$value->name </option>";
                    }
                }
            }else{
                echo "<option value=''>Error - Not value yet!</option>";
            }
            ?>
        </select>
    </div>
    <div class="col-4 form-inline">
        Measurement System : &emsp;
        <select name="redis_container_system" id="" class="form-control">
        <?php
        if(!empty($tbl_measurement_system)){
            foreach($tbl_measurement_system as $value){
                if($redis_container->measurement_system == $value->id){
                    echo "<option value='$value->id' selected>$value->name</option>";
                }else{
                    echo "<option value='$value->id'>$value->name</option>";
                }
            }
        }else{
            echo "<option value=''>Error - Not value yet!</option>";
        }
        ?>
        </select>
    </div>
    <div class="col-4 form-inline">
        Created at : &emsp;
        <?php echo "<input type='date' name='redis_container_created_at' class='form-control' value='$redis_container->created_at'>"; ?>
    </div>
</div>
<div class="row" style="margin-top: 10px">
    <div class="col-4 form-inline">
        <?= $form->field($redis_container, 'price') ?>
<!--        Price : &emsp;-->
<!--        --><?php //echo "<input type='text' name='redis_container_price' class='form-control' value='$redis_container->price' >"; ?>
    </div>
</div>
<br><br>


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
        <th>#</th>
    </tr>
    </thead>
    <tbody>
    <?php
        foreach($redis_container->styleno as $value){
            echo "<tr>";
            echo "<td>".$form->field($redis_style_no, "style_no[$value->id]")->textInput(['value' => $value->style_no])->label(false)."</td>";
            echo "<td>".$form->field($redis_style_no, "uom[$value->id]")->textInput(['value' => $value->uom])->label(false)."</td>";
            echo "<td>".$form->field($redis_style_no, "prefix[$value->id]")->textInput(['value' => $value->prefix])->label(false)."</td>";
            echo "<td>".$form->field($redis_style_no, "sufix[$value->id]")->textInput(['value' => $value->sufix])->label(false)."</td>";
            echo "<td>".$form->field($redis_style_no, "height[$value->id]")->textInput(['value' => $value->height])->label(false)."</td>";
            echo "<td>".$form->field($redis_style_no, "width[$value->id]")->textInput(['value' => $value->width])->label(false)."</td>";
            echo "<td>".$form->field($redis_style_no, "length[$value->id]")->textInput(['value' => $value->length])->label(false)."</td>";
            echo "<td>".$form->field($redis_style_no, "weight[$value->id]")->textInput(['value' => $value->weight])->label(false)."</td>";
            echo "<td>".$form->field($redis_style_no, "upc[$value->id]")->textInput(['value' => $value->upc])->label(false)."</td>";
            echo "<td>".$form->field($redis_style_no, "size_1[$value->id]")->textInput(['value' => $value->size_1])->label(false)."</td>";
            echo "<td>".$form->field($redis_style_no, "color_1[$value->id]")->textInput(['value' => $value->color_1])->label(false)."</td>";
            echo "<td>".$form->field($redis_style_no, "size_2[$value->id]")->textInput(['value' => $value->size_2])->label(false)."</td>";
            echo "<td>".$form->field($redis_style_no, "color_2[$value->id]")->textInput(['value' => $value->color_2])->label(false)."</td>";
            echo "<td>".$form->field($redis_style_no, "size_3[$value->id]")->textInput(['value' => $value->size_3])->label(false)."</td>";
            echo "<td>".$form->field($redis_style_no, "color_3[$value->id]")->textInput(['value' => $value->color_3])->label(false)."</td>";
            echo "<td>".$form->field($redis_style_no, "carton[$value->id]")->textInput(['value' => $value->carton])->label(false)."</td>";
            echo "<td><a href='/form/delete-style-no?id=$value->id&id_container=$redis_container->id'>Delete</a></td>";

//            echo "<td><input type='text' name='redis_style_no[$value->id]' value='$value->style_no' class='form-control'></td>";
//            echo "<td><input type='text' name='redis_uom[$value->id]' value='$value->uom' class='form-control'></td>";
//            echo "<td><input type='text' name='redis_prefix[$value->id]' value='$value->prefix' class='form-control'></td>";
//            echo "<td><input type='text' name='redis_sufix[$value->id]' value='$value->sufix' class='form-control'></td>";
//            echo "<td><input type='text' name='redis_height[$value->id]' value='$value->height' class='form-control'></td>";
//            echo "<td><input type='text' name='redis_width[$value->id]' value='$value->width' class='form-control'></td>";
//            echo "<td><input type='text' name='redis_length[$value->id]' value='$value->length' class='form-control'></td>";
//            echo "<td><input type='text' name='redis_weight[$value->id]' value='$value->weight' class='form-control'></td>";
//            echo "<td><input type='text' name='redis_upc[$value->id]' value='$value->upc' class='form-control'></td>";
//            echo "<td><input type='text' name='redis_size_1[$value->id]' value='$value->size_1' class='form-control'></td>";
//            echo "<td><input type='text' name='redis_color_1[$value->id]' value='$value->color_1' class='form-control'></td>";
//            echo "<td><input type='text' name='redis_size_2[$value->id]' value='$value->size_2' class='form-control'></td>";
//            echo "<td><input type='text' name='redis_color_2[$value->id]' value='$value->color_2' class='form-control'></td>";
//            echo "<td><input type='text' name='redis_size_3[$value->id]' value='$value->size_3' class='form-control'></td>";
//            echo "<td><input type='text' name='redis_color_3[$value->id]' value='$value->color_3' class='form-control'></td>";
//            echo "<td><input type='text' name='redis_carton[$value->id]' value='$value->carton' class='form-control'></td>";
            echo "</tr>";
        }
    ?>
    </tbody>
</table>
<button type="submit" class="btn btn-primary submit">Update</button>
<?php

ActiveForm::end();
?>
