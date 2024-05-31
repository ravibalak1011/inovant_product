<?php
class Api_model extends CI_Model
{

	public function __construct()
    {
        parent::__construct();
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

    public function getAllProduct(){
        $this->db->select('*');
        $this->db->from('products');
        $query = $this->db->get();
        if ($query) {
            return $query->result_array();
        }else{
            return false;
        }   
    }

    public function insert_product_to_cart($postdata)
    {
        $insetArray = array(
                    'product_id' => $postdata['product_id'],
                    'user_id' => $postdata['user_id'],
                    'added_date' => date('Y-m-d H:i:s'),
                    'updated_date' => date('Y-m-d H:i:s')
                );
        
        $this->db->insert('cart_list',$insetArray);
        $id = $this->db->insert_id();
        return $id;
    }

    public function check_product_in_cart($postdata){
        $this->db->select('*');
        $this->db->from('cart_list');
        $this->db->where('product_id',$postdata['product_id']);
        $this->db->where('user_id',$postdata['user_id']);
        $query = $this->db->get();
        if ($query) {
            return $query->row_array();
        }else{
            return false;
        }   
    }


    public function allProdctFromCart(){
        $this->db->select('products.id,products.product_name,products.product_price,products.product_image,cart_list.user_id,cart_list.added_date,cart_list.updated_date');
        $this->db->from('cart_list');
        $this->db->join('products', 'cart_list.product_id = products.id');
        $this->db->where('cart_list.user_id',1);
        $query = $this->db->get();
        if ( $query->num_rows() > 0 )
        {
            return $query->result_array();
        }
        return false;
    }


	
}	 	  	