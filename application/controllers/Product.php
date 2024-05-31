<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Product_model','pModel');
		$this->load->library('upload');
	}
	public function index()
	{
		$data['product'] = $this->pModel->productData();
		$this->load->view('product_list',$data);
	}
	
	public function add_product()
	{

		// $this->form_validation->set_rules('name', 'Name', 'required');
	 //    $this->form_validation->set_rules('price', 'Price', 'required');

	 //    if ($this->form_validation->run() == true) {
	 //    	echo "string";die;
	 //    }else{
	 //    	$res['status'] = 0;
	 //        $res['name'] = strip_tags(form_error('name'));
	 //        $res['price'] = strip_tags(form_error('price'));
	 //        echo json_encode($res);
	 //        return false;
	 //    }
		$postdata = $this->input->post();
		if ($postdata) {
			$this->load->library('upload');
			$image = array();
			if (!empty($_FILES['fileToUpload']['name'][0])) {
				$ImageCount = count($_FILES['fileToUpload']['name']);

			    for($i = 0; $i < $ImageCount; $i++){
			        $_FILES['file']['name']       = $_FILES['fileToUpload']['name'][$i];
			        $_FILES['file']['type']       = $_FILES['fileToUpload']['type'][$i];
			        $_FILES['file']['tmp_name']   = $_FILES['fileToUpload']['tmp_name'][$i];
			        $_FILES['file']['error']      = $_FILES['fileToUpload']['error'][$i];
			        $_FILES['file']['size']       = $_FILES['fileToUpload']['size'][$i];

			        $uploadPath = './assets/upload/product_images/';
			        $config['upload_path'] = $uploadPath;
			        $config['allowed_types'] = 'jpg|jpeg|png|gif';

			        $this->load->library('upload', $config);
			        $this->upload->initialize($config);

			        if($this->upload->do_upload('file')){
			            $imageData = $this->upload->data();
			            $uploadImgData[$i]['fileToUpload'] = $imageData['file_name'];

			        }
			    }
			    if(!empty($uploadImgData)){
		            // $postdata['product_image'] = $uploadImgData;
		            $getFullImageArray = array_column($uploadImgData, 'fileToUpload');
		            $postdata['product_images'] = json_encode($getFullImageArray);
		            // echo "<pre>";print_r($product_images);die();
		        }
			}
			
			$this->pModel->insertProduct($postdata);
			$this->session->set_flashdata('success', 'Product added successfully.');
			echo json_encode(array('status' => 1 ));
		}else{
			$this->load->view('add_product');
		}
		
	}

	public function edit_product($id)
	{
		$data['editData'] = $this->pModel->edit_product($id);
		// echo "<pre>";print_r($select_data['data']);die();
		$this->load->view('edit_product',$data);	
	}




	public function update_product()
	{
		$postdata = $this->input->post();
		if ($postdata) {
			$this->load->library('upload');
			$image = array();
			if (!empty($_FILES['fileToUpload']['name'][0])) {
				$ImageCount = count($_FILES['fileToUpload']['name']);

			    for($i = 0; $i < $ImageCount; $i++){
			        $_FILES['file']['name']       = $_FILES['fileToUpload']['name'][$i];
			        $_FILES['file']['type']       = $_FILES['fileToUpload']['type'][$i];
			        $_FILES['file']['tmp_name']   = $_FILES['fileToUpload']['tmp_name'][$i];
			        $_FILES['file']['error']      = $_FILES['fileToUpload']['error'][$i];
			        $_FILES['file']['size']       = $_FILES['fileToUpload']['size'][$i];

			        $uploadPath = './assets/upload/product_images/';
			        $config['upload_path'] = $uploadPath;
			        $config['allowed_types'] = 'jpg|jpeg|png|gif';

			        $this->load->library('upload', $config);
			        $this->upload->initialize($config);

			        if($this->upload->do_upload('file')){
			            $imageData = $this->upload->data();
			            $uploadImgData[$i]['fileToUpload'] = $imageData['file_name'];

			        }
			    }
			    if(!empty($uploadImgData)){
		            // $postdata['product_image'] = $uploadImgData;
		            $selectOriginalDataOfImages = $this->pModel->selectDataForImages($postdata);
		            if ($selectOriginalDataOfImages['product_image'] == 'null' || $selectOriginalDataOfImages['product_image'] == '') {
		            	$getFullImageArray = array_column($uploadImgData, 'fileToUpload');
		            	$postdata['product_images'] = json_encode($getFullImageArray);
		            }else{
		            	$getFullImageArray = array_column($uploadImgData, 'fileToUpload');
			            // echo "<pre>";print_r($getFullImageArray);die();
			            $convertToArray = json_decode($selectOriginalDataOfImages['product_image']);
			            $getTotoalArrayofiamges = array_merge($getFullImageArray,$convertToArray);

			            $postdata['product_images'] = json_encode($getTotoalArrayofiamges);	
		            }
		            
		            
		        }
			}
			// echo "<pre>";print_r($postdata);die();
			$this->pModel->update_product($postdata);
			$this->session->set_flashdata('success', 'Product updated successfully.');
			echo json_encode(array('status' => 1 ));
		}
	}
	

	public function delete_product()
	{
		$id = $this->input->post('id');
		$this->pModel->deleteData($id);
		$this->pModel->deleteFromCart($id);

		echo json_encode(array('status' => 1 ));
	}

	public function delete_from_cart()
	{
		$id = $this->input->post('id');
		$this->pModel->deleteCartData($id);
		echo json_encode(array('status' => 1 ));
	}

	public function delete_images()
	{
		$postdata = $this->input->post();
		// echo "<pre>===>>>";print_r($postdata['image_name']);die();
		if ($postdata) {
			$getRowImage = $this->pModel->selectDataForImages($postdata);
			$getRowImage = json_decode($getRowImage['product_image'],true);
			// echo "<pre>===>>>";print_r($getRowImage);die();
		}
		if (($key = array_search($postdata['image_name'], $getRowImage)) !== false) {
		    unset($getRowImage[$key]);
		}
		$out = array_values($getRowImage);
		// echo "<pre>";print_r($out);die;
		$postdata['getRowImage'] = json_encode($out);
		// echo $postdata['getRowImage'];die();
		$image_path = 'assets/upload/product_images/';
		$filename = $image_path . $postdata['image_name'];
		if (file_exists($filename))
        {
            unlink($filename);
        }
		$this->pModel->rowFieldUpdate($postdata);
        if (file_exists($filename))
        {
            unlink($filename);
        }
		echo json_encode(array('status' => 1 ));	
	}

	public function show_product($id)
	{
		$data['showData'] = $this->pModel->show_product($id);
		// echo "<pre>";print_r($select_data['data']);die();
		$this->load->view('show_product',$data);	
	}

	public function view_cart()
	{
		$data['cart'] = $this->pModel->getAllCartData();
		// echo "<pre>";print_r($data['cart']);die();
		$this->load->view('cart_list',$data);
	}
}
