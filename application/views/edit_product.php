<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <title>Update Product</title>
</head>
<body>
<div class="container">
    <br>
    <h1>Update Product</h1>  

    <br>
    <form id="myform" name="myform" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" id="id" value="<?php echo $editData['id'];?>">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Product Name</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" id="product_name" name="product_name" value="<?php echo $editData['product_name'];?>">
                <span id="name_error" class="text-danger"></span>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Product Price</label>
            <div class="col-sm-4">
                <input type="number" class="form-control" name="product_price" id="product_price" value="<?php echo $editData['product_price'];?>">
                <span id="price_error" class="text-danger"></span>
            </div>
        </div>
        
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Upload Image</label>
            <div class="col-sm-4">
                <input class="" type="file" id="fileToUpload" name="fileToUpload[]" multiple="multiple" onchange="previewImages()">
            </div>
        </div>
        <?php if (!empty($editData['product_image']) && $editData['product_image'] != '[]' && $editData['product_image'] != 'null'){ ?>
        <div class="form-group row">
            <table id="table_id" class="table table-striped" style="width:50%" border="indextable" >
                <thead>
                    <tr>
                        <th>Sr.No</th>
                        <th>Image</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="imageTableBody">
                    <?php $i=1;
                    $allImages = json_decode($editData['product_image']); 
                    foreach ($allImages as $getImage) { ?>
                    <tr id="tr_edit_<?php echo $getImage;?>">
                        <td><?php echo $i; ?></td>
                        <td><img src="<?php echo base_url() ?>assets/upload/product_images/<?php echo $getImage; ?>" height="40%" width="10%"></td>
                        <td>
                            <input class="btn btn-danger" value="Remove" type="button" name="delete" id="delete" onclick="deleteData('<?php echo $getImage; ?>','<?php echo $editData['id'];?>')">
                        </td>
                    </tr>
                    <?php $i++;} ?>
                </tbody>
            </table>
        </div>
        <?php } ?>
        <div class="form-group row">
            <label for="inputPassword3" class="col-sm-2 col-form-label"></label>
            <div class="col-sm-4">
                <input type="submit" class="btn btn-primary" id="submit" name="submit" value="Update Product">
            </div>
        </div>
    </form>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script type="text/javascript">
$(document).ready(function() {
    $("#myform").submit(function(e){
        e.preventDefault();

        var name = $("#product_name").val();
        var price = $("#product_price").val();
        if (name == "") {
            $('#name_error').show();
            $('#name_error').text('Please enter product name');
            setTimeout(function() {$('#name_error').hide()}, 5000);
            $("#product_name").focus();
            return false;
        }
        if (price == "") {
            $('#price_error').show();
            $('#price_error').text('Please enter product price');
            setTimeout(function() {$('#price_error').hide()}, 5000);                
            $("#product_price").focus();
            return false;
        }
        if (!price.match(/^\d+$/)) {
            alert("Enter price only");
            $("#product_price").focus();
            return false;
        }
        $.ajax({
            url: '<?php echo base_url('product/update_product'); ?>',
            type: 'POST',
            dataType: 'json',
            contentType:false,
            processData:false,
            cache:false,
            data: new FormData(this),
            success:function(res){
                if (res.status == 1) {
                    // alert("Product details updated successfully");
                    window.location.href = '<?php echo base_url(); ?>'
                }
            }
        });
    });
});

function deleteData(image_name, id) {
    Swal.fire({
        title: 'Are you sure you want to delete this product Image?',
        showDenyButton: true,
        confirmButtonText: 'Yes',
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url : '<?php echo base_url('product/delete_images'); ?>',
                type : 'POST',
                data: {id: id, image_name:image_name},
                dataType:'json',
                success : function(res) {   
                    if(res.status == 1){         
                        // Swal.fire('Product image deleted successfully!', '', 'success');
                        window.location.reload();
                    }
                },
                error : function(request,error)
                {
                    Swal.fire('Changes are not saved. Please try again later.', '', 'error');
                }
            });
        }
    });
}

function previewImages() {
    var fileInput = document.getElementById('fileToUpload');
    var files = fileInput.files;
    var tableBody = document.getElementById('imageTableBody');
    
    for (var i = 0; i < files.length; i++) {
        var file = files[i];
        var reader = new FileReader();
        
        reader.onload = (function(file) {
            return function(e) {
                var newRow = tableBody.insertRow();
                var srNoCell = newRow.insertCell(0);
                var imageCell = newRow.insertCell(1);
                var actionCell = newRow.insertCell(2);

                srNoCell.innerText = tableBody.rows.length;
                imageCell.innerHTML = '<img src="' + e.target.result + '" height="40%" width="10%">';
                actionCell.innerHTML = '<input class="btn btn-danger" value="Remove" type="button" onclick="removeTempImage(this)">';

                newRow.id = 'tr_temp_' + file.name;
            };
        })(file);
        
        reader.readAsDataURL(file);
    }
}

function removeTempImage(button) {
    var row = button.parentNode.parentNode;
    row.parentNode.removeChild(row);
}
</script>
</body>
</html>
