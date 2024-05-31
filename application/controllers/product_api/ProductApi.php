<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ProductApi extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('api_model/Api_model','aModel');
	}

	//Problem  statement - You have to create an API to display these products along with multiple images.
	//this is API for get single product using product id using GET method
	public function show_product($id)
	{
		$data = $this->aModel->show_product($id);
	    if (!empty($data)) {
	        
	        $product_images = str_replace(['[', ']', '\\'], '', $data['product_image']);
	        
	        $img_arr = explode(',', $product_images);
	        
	        $img_arr = array_map(function($img) {
	            return trim($img, '"');
	        }, $img_arr);

	        $data['product_image'] = $img_arr;
	        $res = array('product' => $data);
	    } else {
	        $res = array('message' => 'Record from this id is not available.');
	    }

    	echo json_encode($res);
	}

	//Problem  statement - Create a GET api which will display all the products and multiple images for the products
	//using GET method display all product using USER ID
	public function get_all_product()
	{
		$data = $this->aModel->getAllProduct();

		if (!empty($data)) {
			foreach ($data as $key => $value) {

				$product_images = str_replace(['[', ']', '\\'], '', $value['product_image']);
		        
		        $img_arr = explode(',', $product_images);
		        
		        $img_arr = array_map(function($img) {
		            return trim($img, '"');
		        }, $img_arr);

				$creatArr['id'] = $value['id'];
				$creatArr['product_name'] = $value['product_name'];
				$creatArr['product_price'] = $value['product_price'];
				$creatArr['added_date'] = $value['added_date'];
				$creatArr['updated_date'] = $value['updated_date'];
				
				$creatArr['product_image'] = $img_arr;
				$res[] = $creatArr;
			}
			$getData = array('products' => $res);

		} else {
			$getData = array(['message' => 'No records found']);	
		}
	    echo json_encode($getData);
		// echo "<pre>";print_r($data);die();
		
	}

	//This API for add product in cart Using POST method
	public function addToCart()
	{
		$postdata = $this->input->post();

		$getCartData = $this->aModel->check_product_in_cart($postdata);

		if (!empty($getCartData)) {
			$res = array(['message' => 'This product already in cart']);
			echo json_encode($res);
			return false;
		}


		$data = $this->aModel->show_product($postdata['product_id']);

		if (!empty($data)) {
			$this->aModel->insert_product_to_cart($postdata);
			$res = array(['message' => 'Product added to cart']);
		} else {
			$res = array(['message' => 'Product not found']);
		}
		
		echo json_encode($res);

	}

	// This method for get all data from cart table using GET method
	public function get_cart_list_product()
	{
		$data = $this->aModel->allProdctFromCart();
		// echo "<pre>";print_r($data);die();
		if (!empty($data)) {
			foreach ($data as $key => $value) {

				$product_images = str_replace(['[', ']', '\\'], '', $value['product_image']);
		        
		        $img_arr = explode(',', $product_images);
		        $img_arr = array_map(function($img) {
		            return trim($img, '"');
		        }, $img_arr);
		        
				$cartArr['id'] = $value['id'];
				$cartArr['product_name'] = $value['product_name'];
				$cartArr['product_price'] = $value['product_price'];
				$cartArr['user_id'] = $value['user_id'];
				$cartArr['added_date'] = $value['added_date'];
				$cartArr['updated_date'] = $value['updated_date'];
				// $img_arr = explode(',', $value['product_image']);
				$cartArr['product_image'] = $img_arr;
				$res[] = $cartArr;
			}
			$getCartData = array('products' => $res);

		} else {
			$getCartData = array(['message' => 'Cart is Empty']);	
		}
	    echo json_encode($getCartData);
		// echo "<pre>";print_r($data);die();
		
	}
}
