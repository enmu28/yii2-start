<?php
use yii\helpers\Html;
echo Html::tag('h5', 'Information of container');
echo Html::tag('span', "- Vendor : $redis_container->vendor <br>");
echo Html::tag('span', "- Measurement System : $redis_container->measurement_system<br>");
echo Html::tag('span', "- Price : $redis_container->price<br>");
echo Html::tag('span', "- Created at : $redis_container->created_at");
?>
<table name="" id="" class="table table-bordered">
    <thead class="bg-primary">
<!--    style_no, uom, prefix, sufix, height, width, length, weight, upc, size_1, color_1, size_2, color_2, size_3, color_3,  carton-->
    <th>style_no</th>
    <th>uom</th>
    <th>prefix</th>
    <th>sufix</th>
    <th>height</th>
    <th>width</th>
    <th>length</th>
    <th>weight</th>
    <th>upc</th>
    <th>size_1</th>
    <th>color_1</th>
    <th>size_2</th>
    <th>color_2</th>
    <th>size_3</th>
    <th>color_3</th>
    <th>carton</th>
    </thead>

    <tbody>
    <?php
        foreach($redis_container->styleno as $value){
            echo "<tr>";
            echo "<td>$value->style_no</td>";
            echo "<td>$value->uom</td>";
            echo "<td>$value->prefix</td>";
            echo "<td>$value->sufix</td>";
            echo "<td>$value->height</td>";
            echo "<td>$value->width</td>";
            echo "<td>$value->length</td>";
            echo "<td>$value->weight</td>";
            echo "<td>$value->upc</td>";
            echo "<td>$value->size_1</td>";
            echo "<td>$value->color_1</td>";
            echo "<td>$value->size_2</td>";
            echo "<td>$value->color_2</td>";
            echo "<td>$value->size_3</td>";
            echo "<td>$value->color_3</td>";
            echo "<td>$value->carton</td>";
            echo "</tr>";
        }
    ?>
    </tbody>
</table>

