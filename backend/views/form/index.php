<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\example\TblVendor;
use app\models\example\TblMeasurementSystem;
use backend\assets\BackendAsset;

?>
    <?php
//    $form = ActiveForm::begin([
//        'enableAjaxValidation'=>true,
//        'id' => 'form_active',
//        'enableClientValidation'=>false
//            ]);
//    ?>
<?php $this->registerJsFile('@web/js/form.js'); ?>
<form name="" id="" method="post">
    <div class="row">
        <div class="col-4 form-inline">
            <label>Vendor</label>
            <select class="form-control" id="id-vendor">
                <?php
                if(count(TblVendor::find()->all()) >0){
                    foreach(TblVendor::find()->all() as $value){
                        echo "<option value='$value->id'>$value->name </option>";
                    }
                }else{
                    echo "<option value=''>Error - Not value yet!</option>";
                }
                ?>
            </select>
        </div>
        <div class="col-4 form-inline">
            <label>Vendor</label>
            <select class="form-control" id="id-measurement-system">
                <?php
                if(count(TblMeasurementSystem::find()->all()) >0){
                    foreach(TblMeasurementSystem::find()->all() as $value){
                        echo "<option value='$value->id'>$value->name </option>";
                    }
                }else{
                    echo "<option value=''>Error - Not value yet!</option>";
                }
                ?>
            </select>
        </div>
        <div class="col-4 form-inline">
            <label>Created at</label>
            <input type="date" class="form-control" id="created-at" value="<?php echo date('Y-m-d'); ?>">
            <div id="error-created-at"></div>
        </div>
    </div>

    <div class="row" style="margin-top: 10px">
        <div class="col-4 form-inline">
            <label>Container #:</label>
            <input type="text" class="form-control" id="id-container" onblur="check_val('id-container')">
            <div id="error-id-container"></div>
        </div>
    </div>
    <div class="row" style="margin-top: 10px">
        <div class="col-4 form-inline">
            <label>Price #:</label>
            <input type="text" class="form-control" id="price" onblur="check_val('price')">
            <div id="error-price"></div>
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
            </td>
            <td>
                <div class="form-group">
                    <input type="text" class="form-control" id="color_1-1" name="TblStyleNo[color_1][1]" onblur="check_val('color_1-1')">
                    <div id="error-color_1-1"></div>
                </div>
            </td>
            <td>
                <div class="form-group">
                    <input type="text" class="form-control" id="size_2-1" name="TblStyleNo[size_2][1]" onblur="check_val_1('size_2-1')">
                    <div id="error-size_2-1"></div>
                </div>
            </td>
            <td>
                <div class="form-group">
                    <input type="text" class="form-control" id="color_2-1" name="TblStyleNo[color_2][1]" onblur="check_val_1('color_2-1')">
                    <div id="error-color_2-1"></div>
                </div>
            </td>
            <td>
                <div class="form-group">
                    <input type="text" class="form-control" id="size_3-1" name="TblStyleNo[size_3][1]" onblur="check_val_1('size_3-1')">
                    <div id="error-size_3-1"></div>
                </div>
            </td>
            <td>
                <div class="form-group">
                    <input type="text" class="form-control" id="color_3-1" name="TblStyleNo[color_3][1]" onblur="check_val_1('color_3-1')">
                    <div id="error-color_3-1"></div>
                </div>
            </td>
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
    <button type="button" class="btn btn-primary" onclick="check_validate()">Save</button>
</form>
<br><?= Html::a('Main menu', ['form/show'], ['class' => 'profile-link']) ?>
