<!doctype html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

        <title>Show Product</title>
    </head>
    <body>
    <div class="container">
        <br>
        <h1>Show Product</h1>  
      
        <br>
        <form id="myform" name="myform" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" id="id" value="<?php echo $showData['id'];?>">
            <div class="form-group row">
                <label class="col-sm-2 col-form-label"><strong>Product Name:</strong></label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" id="product_name" name="product_name" value="<?php echo $showData['product_name'];?>" readonly>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label"><strong>Product Price:</strong></label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="product_price" id="product_price" value="<?php echo $showData['product_price'];?>" readonly>
                </div>
            </div>
            
            
            <?php //echo "<pre>";print_r($showData);die(); ?>
            <?php if (empty($showData['product_image']) || $showData['product_image'] == '[]' || $showData['product_image'] == 'null'){ ?>
            <?php }else{ ?>
                
            <div class="form-group row">
                <!-- <label class="col-sm-2 col-form-label">Product Desccription</label> -->
                <table id="table_id" class="table table-striped" style="width:50%" border="indextable" >
                    <thead>
                        <tr>
                            <th>Sr.No</th>
                            <th>Image</th>
                            
                        </tr>
                    </thead>
                    <tbody>

                        <?php $i=1;
                        $allImages = json_decode($showData['product_image']); 
                        foreach ($allImages as $getImage) { ?>
                            
                        <tr id="tr_edit_<?php echo $getImage;?>">
                            <td><?php echo $i; ?></td>
                            <td><img src="<?php echo base_url() ?>assets/upload/product_images/<?php echo $getImage; ?>" height="80%" width="50%"></td>
                            
                            
                        </tr>
                        <?php $i++;} ?>
                    </tbody>
                </table>
            </div>
            <?php } ?>
            
        </form>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    

  </body>
</html>