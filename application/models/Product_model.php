<?php 
class Product_model extends CI_model{
	
	public function __construct()
    {
        parent::__construct();
    }

	public function productData(){
		$this->db->select('*');
		$this->db->from('products');
		$this->db->order_by('id','desc');
		$query = $this->db->get();
		if ($query) {
			return $query->result_array();
		}else{
			return false;
		}
	}

	public function insertProduct($postdata){
		// echo "<pre>";print_r($postdata);die;
		$insertArray['product_name'] = $postdata['product_name'];
		$insertArray['product_price'] = $postdata['product_price'];
		if (isset($postdata['product_images'])) {
			$insertArray['product_image'] = $postdata['product_images']; 
		}
		$insertArray['added_date'] = date('Y-m-d H:i:s');
		
		// echo "<pre>";print($insertArray);die();
		$this->db->insert('products',$insertArray);
	}

	public function edit_product($id){
		$this->db->select('*');
		$this->db->from('products');
		$this->db->where('id',$id);
		$query = $this->db->get();
		if ($query) {
			return $query->row_array();
		}else{
			return false;
		}	
	}
	
	public function show_product($id){
		$this->db->select('*');
		$this->db->from('products');
		$this->db->where('id',$id);
		$query = $this->db->get();
		if ($query) {
			return $query->row_array();
		}else{
			return false;
		}	
	}

	public function update_product($postdata){
		// echo "<pre>";print_r($postdata);die();
		$updateArray['product_name'] = $postdata['product_name'];
		$updateArray['product_price'] = $postdata['product_price']; 
		if (isset($postdata['product_images'])) {
			$updateArray['product_image'] = $postdata['product_images']; 
		}
		$updateArray['updated_date'] = date('Y-m-d H:i:s');
		
		$this->db->where('id',$postdata['id']);
		$this->db->update('products',$updateArray);
	}

	public function rowFieldUpdate($postdata){
		// echo "<pre>";print_r($postdata);die();
		$updateArray = array(
					'product_image' => $postdata['getRowImage']
				);
		$this->db->where('id',$postdata['id']);
		$this->db->update('products',$updateArray);
	} 

	public function deleteData($id){
		$this->db->where('id',$id);
		$this->db->delete('products');
	}

	public function deleteFromCart($id){
		$this->db->where('product_id',$id);
		$this->db->where('user_id',1);
		$this->db->delete('cart_list');
	}

	public function deleteCartData($id){
		$this->db->where('id',$id);
		$this->db->where('user_id',1);
		$this->db->delete('cart_list');
	}
	public function selectDataForImages($postdata){
		$this->db->select('id,product_image');
		$this->db->from('products');
		$this->db->where('id',$postdata['id']);
		$query = $this->db->get();
		if ($query) {
			return $query->row_array();
		}else{
			return false;
		}	
	}

	public function getAllCartData()
	{
		$this->db->select('products.id,products.product_name,products.product_price,products.product_image,cart_list.user_id,cart_list.added_date,cart_list.updated_date,users.name');
        $this->db->from('cart_list');
        $this->db->join('products', 'cart_list.product_id = products.id');
        $this->db->join('users', 'cart_list.user_id = users.id');
        $this->db->where('cart_list.user_id',1);
        $query = $this->db->get();
        if ( $query->num_rows() > 0 )
        {
            return $query->result_array();
        }
        return false;
	}
}
 ?>