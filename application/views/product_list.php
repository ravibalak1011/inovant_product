<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Product Page</title>
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
	
	
</head>
<body>

<div id="container-fluid" class="container">
    <div class="row">
        <div class="col-sm-6">
            <h1 style="margin-top: 50px;">Product Table</h1>
        </div>
        <div class="col-sm-6">
            <a class="btn btn-secondary" style="float: right;margin-top: 50px;margin-left: 8px;" href="<?php echo base_url() ?>product/view_cart">View Cart</a>
            <a class="btn btn-primary" style="float: right;margin-top: 50px;" href="<?php echo base_url() ?>product/add_product">Add Product</a>
        </div>
        <div class="header header-flash-message">
            <?php if ($this->session->flashdata('error')!='') {?>
            <div class="alert alert-danger" id="myDiv">
                <!-- <button data-dismiss="alert" class="close"></button> -->
                <?php echo $this->session->flashdata('error');?>
            </div>
            <?php } ?>
            <?php if ($this->session->flashdata('success')!='') {?>
            <div class="alert alert-success" id="myDiv">
                <?php echo $this->session->flashdata('success');?>
            </div>
            <?php } ?>
        </div>
    </div>
	<?php //echo "<pre>";print_r($product);die(); ?>
	<table id="example" class="table table-striped" style="width:100%" border="indextable">
        <thead>
            <tr>
                <th>Sr.No</th>
                <th>Product Name</th>
                <th>Product Price</th>
                <th>Created At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>

        	<?php $i=1; 
        	foreach ($product as $row){ ?>
        		
            <tr id="tr_edit_<?php echo $row['id'];?>">
                <td><?php echo $i; ?></td>
                <td><?php echo $row['product_name']; ?></td>
                <td><?php echo $row['product_price']; ?></td>
                <td><?php echo $row['added_date']; ?></td>
                <td><a class="btn btn-primary" href="<?php echo base_url() ?>product/edit_product/<?php echo $row['id']; ?>">Edit</a>
                	<input class="btn btn-danger" value="Delete" type="button" name="delete" id="delete" onclick="deleteData('<?php echo $row['id'];?>')">
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript">
    	$(document).ready(function () {
    	    var ref = $('#example').DataTable();

            setTimeout(function(){
                $("#myDiv").fadeOut("slow");
            }, 5000);
    	});
        
        function deleteData(id) {
            Swal.fire({
                title: 'Are u sure, you want to delete this product?',
                showDenyButton: true,
                confirmButtonText: 'Yes',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url : '<?php echo base_url('product/delete_product'); ?>',
                            type : 'POST',
                            data: {id: id},
                            dataType:'json',
                            success : function(res) {   
                                
                                if(res.status == 1){         
                                    Swal.fire('Product Deleted Successfully!!!', '', 'success');
                                    $("#tr_edit_"+id).remove();
                                }
                            },
                            error : function(request,error)
                            {
                                Swal.fire('Changes are not saved pleasae try after sometime', '', 'error')
                            }
                        });
                        
                    } 
                })
        }
    </script>
</body>
</html>
