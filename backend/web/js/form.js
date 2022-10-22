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

                    '<td><div class="form-group">'+
                    '<input type="text" class="form-control" id="size_2-'+length+'" name="TblStyleNo[size_2]['+length+']"  onblur="check_val_1(\'size_2'+length+'\')">' +
                    '<div id="error-size_2'+length+'"></div>' +
                    '</div></td>' +

                    '<td><div class="form-group">'+
                    '<input type="text" class="form-control" id="color_2-'+length+'" name="TblStyleNo[color_2]['+length+']"  onblur="check_val_1(\'color_2'+length+'\')">' +
                    '<div id="error-color_2'+length+'"></div>' +
                    '</div></td>' +

                    '<td><div class="form-group">'+
                    '<input type="text" class="form-control" id="size_3-'+length+'" name="TblStyleNo[size_2]['+length+']"  onblur="check_val_1(\'size_3'+length+'\')">' +
                    '<div id="error-size_3'+length+'"></div>' +
                    '</div></td>' +

                    '<td><div class="form-group">'+
                    '<input type="text" class="form-control" id="color_3-'+length+'" name="TblStyleNo[color_3]['+length+']"  onblur="check_val_1(\'color_3'+length+'\')">' +
                    '<div id="error-color_3'+length+'"></div>' +
                    '</div></td>' +

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
    document.getElementById("error-"+id).innerText="Not null";
    return false;
}else if(re.test($("#"+id).val().trim())==false || $("#"+id).val() <= 0){
    document.getElementById("error-"+id).innerText="Error datatype!";
    return false;
}else{
    document.getElementById("error-"+id).innerText="";
    return true;
}
}

function check_val_1(id){
var re=/^\d+$/;
if($("#"+id).val() != ''){
    if(re.test($("#"+id).val().trim())== false || $("#"+id).val() <= 0){
        document.getElementById("error-"+id).innerText="Error datatype!";
        return false;
    }else{
        document.getElementById("error-"+id).innerText="";
        return true;
    }
}
return true;
}

function check_validate() {
    let k = 0;
    let count = 0;
    let style_no = {};
    style_no['style-no']={};style_no['uom'] = {};style_no['prefix']= {};style_no['sufix'] ={};
    style_no['height'] = {};style_no['width'] = {};style_no['length'] = {};style_no['weight'] ={};
    style_no['size_2'] = {};style_no['color_2'] = {};style_no['size_3'] = {};style_no['color_3'] ={};
    style_no['upc'] ={}; style_no['size_1'] ={}; style_no['color_1'] ={}; style_no['carton'] = {};

    for(let i=1; i<=$('.tr_name').length; i++){
        if(check_val(String("style-no-"+i)) == false && check_val(String('uom-'+i)) == false && check_val(String('prefix-'+i)) == false && check_val(String('sufix-'+i)) == false
            && check_val(String('height-'+i)) == false && check_val(String('width-'+i)) == false && check_val(String('length-'+i)) == false && check_val(String('weight-'+i)) == false
            && check_val(String('upc-'+i)) == false && check_val(String('size_1-'+i)) == false && check_val(String('color_1-'+i)) == false  && check_val(String('carton-'+i)) == false
            && check_val_1(String('size_2-'+i)) == false && check_val_1(String('color_2-'+i)) == false && check_val_1(String('size_3-'+i)) == false && check_val_1(String('color_3-'+i)) == false

            || check_val(String("style-no-"+i)) == false || check_val(String('uom-'+i)) == false || check_val(String('prefix-'+i)) == false || check_val(String('sufix-'+i)) == false
            || check_val(String('height-'+i)) == false || check_val(String('width-'+i)) == false || check_val(String('length-'+i)) == false || check_val(String('weight-'+i)) == false
            || check_val(String('upc-'+i)) == false || check_val(String('size_1-'+i)) == false || check_val(String('color_1-'+i)) == false  || check_val(String('carton-'+i)) == false
            || check_val_1(String('size_2-'+i)) == false || check_val_1(String('color_2-'+i)) == false || check_val_1(String('size_3-'+i)) == false || check_val_1(String('color_3-'+i)) == false)
        {
            k  = 1;
        }else{
            k = 0;
            style_no['style-no'][i] = $("#style-no-"+i).val();
            style_no['uom'][i] = $("#uom-"+i).val();
            style_no['prefix'][i] = $("#prefix-"+i).val();
            style_no['sufix'][i] = $("#sufix-"+i).val();
            style_no['height'][i] = $("#height-"+i).val();
            style_no['width'][i] = $("#width-"+i).val();
            style_no['length'][i] = $("#length-"+i).val();
            style_no['weight'][i] = $("#weight-"+i).val();
            style_no['upc'][i] = $("#upc-"+i).val();
            style_no['size_1'][i] = $("#size_1-"+i).val();
            style_no['color_1'][i] = $("#color_1-"+i).val();
            style_no['size_2'][i] = $("#size_2-"+i).val();
            style_no['color_2'][i] = $("#color_2-"+i).val();
            style_no['size_3'][i] = $("#size_3-"+i).val();
            style_no['color_3'][i] = $("#color_3-"+i).val();
            style_no['carton'][i] = $("#carton-"+i).val();
        }

    }
    if(check_val('id-container') != true && check_val('price') != true || check_val('id-container') != true || check_val('price') != true) {
        k = 1;
    }else{
        k =0;
    }
    console.log(k);
    if(k == 1){
        return false;
    }else{
        let data = {};
        if($("#id-vendor").val() != '' || $("#id-measurement-system").val() != ''){
            data['id-vendor'] = $("#id-vendor").val();
            data['id-measurement-system'] = $("#id-measurement-system").val();
            data['id-container'] = $("#id-container").val();
            data['price'] = $("#price").val();
            data['created-at']  = $("#created-at").val();
            data['style-no'] = style_no;
            $.ajax({
                url: '/form/validate',
                method: 'post',
                data : data,
                dataType: 'json'
            }).done(function(response){
                if(response.data.success == true){
                    alert(response.data.message);
                }else if(response.data.success == 'unique'){
                    $("#error-id-container").empty();
                    $("#error-id-container").append(response.data.message);
                }else{
                    alert(response.data.message);
                }
            })
        }else{
            alert("Error - Not data yet<br>Please contact the website!");
        }

    }
}
