<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Cart List</title>
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
	
	
</head>
<body>

<div id="container-fluid" class="container">
    <div class="row">
        <div class="col-sm-6">
            <h1 style="margin-top: 50px;">Cart List</h1>
        </div>
        <div class="col-sm-6">
            <a class="btn btn-danger" style="float: right;margin-top: 50px;" href="<?php echo base_url(); ?>">Back</a>
        </div>
    </div>
	<?php //echo "<pre>";print_r($product);die(); ?>
	<table id="example" class="table table-striped" style="width:100%" border="indextable">
        <thead>
            <tr>
                <th>Sr.No</th>
                <th>Product Name</th>
                <th>Product Price</th>
                <th>User Name</th>
                <th>Added At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>

        	<?php $i=1; 
        	foreach ($cart as $row){ ?>
        		<?php //echo "<pre>";print_r($row);die; ?>
            <tr id="tr_edit_<?php echo $row['id'];?>">
                <td><?php echo $i; ?></td>
                <td><?php echo $row['product_name']; ?></td>
                <td><?php echo $row['product_price']; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['added_date']; ?></td>
                <td>
                    <input class="btn btn-danger" value="Remove" type="button" name="delete" id="delete" onclick="deleteCartData('<?php echo $row['id'];?>')">
                    <a class="btn btn-success" href="<?php echo base_url() ?>product/show_product/<?php echo $row['id']; ?>">Show</a>
                </td>
                
            </tr>
        	<?php $i++;} ?>
        </tbody>
    </table>
</div>



    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            var ref = $('#example').DataTable();
        });
        function deleteCartData(id) {
            //alert($id);
            var del = confirm("Remove From cart");
            if (del) {
                $.ajax({
                    url: '<?php echo base_url('product/delete_from_cart'); ?>',
                    type: 'POST',
                    dataType: 'json',
                    data: {id: id},
                    success:function(res) {
                        alert('Product deleted successfully!!!');
                        $("#tr_edit_"+id).remove();
                    }
                });
            }
        }
    </script>
</body>
</html>
