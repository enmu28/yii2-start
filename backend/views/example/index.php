<!DOCTYPE html>
<html lang="en">
<head>
    <title>Example</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <div class="row">
        <div class="col-4 form-inline">
            Vendor &emsp;
            <select name="" id="" class="form-control">
                <option value="#id">Victory Aircraft</option>
                <option value="#id">Example</option>
            </select>
        </div>
        <div class="col-4">
            Measurement System:
            <select name="" id="" class="form-control" style="width: 70%;">
                <option value="#id">US-Imperal</option>
                <option value="#id">US-Imperal</option>
            </select>
        </div>
        <div class="col-4">
            Date:
            <input type="datetime-local" name="" class="form-control" style="width: 70%;">
        </div>
    </div>

    <div class="row">
        <div class="col-4 form-inline">
            Container #: &emsp;
            <input type="input" width="80%" name="" class="form-control">
        </div>
    </div>
    <div class="row" style="margin-top: 10px">
        <div class="col-4 form-inline">
            Receiving #: &emsp;
            <input type="input" width="80%" name=""  class="form-control">
        </div>

    </div>

    <br><br>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>STYLE NO</th>
            <th>UOM</th>
            <th>PREFIX</th>
            <th>SUFIX</th>
            <th>HEIGHT</th>
            <th>WIDTH</th>
            <th>LENGTH</th>
            <th>UPC</th>
            <th>SIZE 1</th>
            <th>COLOR 1</th>
            <th>SIZE 2</th>
            <th>COLOR 2</th>
            <th>SIZE 3</th>
            <th>COLOR 3</th>
            <th>CARTON</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>1</td>
            <td>1</td>
            <td>1</td>
            <td>1</td>
            <td>1</td>
            <td>1</td>
            <td>1</td>
            <td>1</td>
            <td>1</td>
            <td>1</td>
            <td>1</td>
            <td>1</td>
            <td>1</td>
            <td>1</td>
            <td>1</td>
        </tr>
    </tbody>
</table>

<div class="form-inline">
    <button type="button" id="bt-add-row" class="btn btn-info">Add</button> &emsp;
    <input class="form-control" type="text" id="row" width="100px"> &emsp; #row<br>
</div><br>

<button id="submit" type="button" class="btn btn-primary">Submit</button>
<button id="cancel" type="button" class="btn btn-danger">Cancel</button>

<br><a href="">Main Menu</a>
</body>
</html>