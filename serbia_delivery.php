<?php
class ControllerSaleSerbiaDelivery extends Controller {
 
   
    
	private $error = array();

	public function index() {
		$this->load->language('sale/serbia_delivery');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/serbia_delivery');
	
	

		$this->getList();
	}




	protected function getList() {
		if (isset($this->request->get['filter_delivery_id'])) {
			$filter_delivery_id = $this->request->get['filter_delivery_id'];
		} else {
			$filter_delivery_id = '';
		}

		if (isset($this->request->get['filter_order_id'])) {
			$filter_order_id = $this->request->get['filter_order_id'];
		} else {
			$filter_order_id = '';
		}

	
		if (isset($this->request->get['filter_delivery_status_id'])) {
			$filter_delivery_status_id = $this->request->get['filter_delivery_status_id'];
		} else {
			$filter_delivery_status_id = '';
		}

		if (isset($this->request->get['filter_date_added'])) {
			$filter_date_added = $this->request->get['filter_date_added'];
		} else {
			$filter_date_added = '';
		}

		if (isset($this->request->get['filter_date_modified'])) {
			$filter_date_modified = $this->request->get['filter_date_modified'];
		} else {
			$filter_date_modified = '';
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'r.delivery_id';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_delivery_id'])) {
			$url .= '&filter_delivery_id=' . $this->request->get['filter_delivery_id'];
		}

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}

		if (isset($this->request->get['filter_return_status_id'])) {
			$url .= '&filter_return_status_id=' . $this->request->get['filter_return_status_id'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['filter_date_modified'])) {
			$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('sale/return', 'user_token=' . $this->session->data['user_token'] . $url, true)
		);

		//$data['add'] = $this->url->link('sale/return/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
		//$data['delete'] = $this->url->link('sale/return/delete', 'user_token=' . $this->session->data['user_token'] . $url, true);
		$data['printSelected']  = $this->url->link('sale/serbia_delivery/createPDFSelected', 'user_token=' . $this->session->data['user_token'] .  '&form_id=2' . $url, true);
		$data['returns'] = array();

		$filter_data = array(
			'filter_delivery_id'        => $filter_delivery_id,
			'filter_order_id'         => $filter_order_id,
			'filter_delivery_status_id' => $filter_delivery_status_id,
			'filter_date_added'       => $filter_date_added,
			'filter_date_modified'    => $filter_date_modified,
			'sort'                    => $sort,
			'order'                   => $order,
			'start'                   => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                   => $this->config->get('config_limit_admin')
		);

		$return_total = $this->model_sale_serbia_delivery->getTotalDelivery($filter_data);

		$results = $this->model_sale_serbia_delivery->getDelivery($filter_data);

		foreach ($results as $result) {
			$data['returns'][] = array(
				'delivery_id'     => $result['delivery_id'],
				'order_id'      => $result['order_id'],
				'delivery_status' => $result['delivery_status'],
				'date_added'    => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'date_modified' => date($this->language->get('date_format_short'), strtotime($result['date_modified'])),
				'print'          => $this->url->link('sale/serbia_delivery/createPDF', 'user_token=' . $this->session->data['user_token'] . '&order_id=' . $result['order_id']. '&form_id=2' . $url, true)
			);
		}

		$data['user_token'] = $this->session->data['user_token'];

		if (isset($this->session->data['error'])) {
			$data['error_warning'] = $this->session->data['error'];

			unset($this->session->data['error']);
		} elseif (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = '';

		if (isset($this->request->get['filter_delivery_id'])) {
			$url .= '&filter_delivery_id=' . $this->request->get['filter_delivery_id'];
		}

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}

		if (isset($this->request->get['filter_return_status_id'])) {
			$url .= '&filter_return_status_id=' . $this->request->get['filter_return_status_id'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['filter_date_modified'])) {
			$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_delivery_id'] = $this->url->link('sale/serbia_delivery', 'user_token=' . $this->session->data['user_token'] . '&sort=r.delivery_id' . $url, true);
		$data['sort_order_id'] = $this->url->link('sale/serbia_delivery', 'user_token=' . $this->session->data['user_token'] . '&sort=r.order_id' . $url, true);
		$data['sort_status'] = $this->url->link('sale/serbia_delivery', 'user_token=' . $this->session->data['user_token'] . '&sort=status' . $url, true);
		$data['sort_date_added'] = $this->url->link('sale/serbia_delivery', 'user_token=' . $this->session->data['user_token'] . '&sort=r.date_added' . $url, true);
		$data['sort_date_modified'] = $this->url->link('sale/serbia_delivery', 'user_token=' . $this->session->data['user_token'] . '&sort=r.date_modified' . $url, true);

		$url = '';

		if (isset($this->request->get['filter_delivery_id'])) {
			$url .= '&filter_delivery_id=' . $this->request->get['filter_delivery_id'];
		}

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}

		if (isset($this->request->get['filter_return_status_id'])) {
			$url .= '&filter_return_status_id=' . $this->request->get['filter_return_status_id'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['filter_date_modified'])) {
			$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $return_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('sale/serbia_delivery', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($return_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($return_total - $this->config->get('config_limit_admin'))) ? $return_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $return_total, ceil($return_total / $this->config->get('config_limit_admin')));

		$data['filter_delivery_id'] = $filter_delivery_id;
		$data['filter_order_id'] = $filter_order_id;
		$data['filter_delivery_status_id'] = $filter_delivery_status_id;
		$data['filter_date_added'] = $filter_date_added;
		$data['filter_date_modified'] = $filter_date_modified;

		$this->load->model('sale/serbia_delivery');

		$data['delivery_statuses'] = $this->model_sale_serbia_delivery->getDeliveryStatuses('DExpress');

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('sale/serbia_delivery', $data));
	}


public function sendDExpress(){
   	$this->load->language('sale/serbia_delivery');
    
  
    
     	if (isset($this->request->get['order_id'])) {
			$order_id = $this->request->get['order_id'];
		} else {
			$order_id = '';
		}

	if (isset($this->request->get['form_id'])) {
			$form_id = $this->request->get['form_id'];
		} else {
			$form_id = '';
		}


// file_put_contents($logLocation."send_order_log_". date('Y_m_d').".txt","\n"."----------------------------------------------------------------------------------------------------------------------------- \n",FILE_APPEND);
// file_put_contents($logLocation."send_order_log_". date('Y_m_d').".txt","\n"."START : - Vreme=". date('YmdHis')."    \n",FILE_APPEND);
$products_arr=array();
$this->load->model('sale/serbia_delivery');

 	$order_info = $this->model_sale_serbia_delivery->getOrderForSent($order_id);
    $order_info_list = $this->model_sale_serbia_delivery->getOrderListForSent($order_id);
    
    //get delivery city
    $delivery_city = $this->model_sale_serbia_delivery->getDeliveryCity($this->config->get('serbia_delivery_pickup_town_name'));
    //get delivery number
    $delivery_number = $this->model_sale_serbia_delivery->getDeliveryNumber();



//prepare package
			$products = array(
			    
				'code'     => $delivery_number['delivery_number'],
				'mass'      => '200'
			);
			
			  array_push($products_arr, $products);
//	print_r($order_info);	

$PaymentBy=0; 
$PaymentType=2;
 //------------------------------------------------------------------- Ako se posebno naplacuje dostava----------------------------------------------------------------------------
/*
	if($order_info[0]['shipping_method']=='Besplatna dostava' || $order_info[0]['payment_method']=='Platna kartica' || $order_info[0]['payment_method']=='Uplata na račun'){
   $PaymentBy=0; 
     $PaymentType=2;
}		
	else{
	    $PaymentBy=2; 
	     $PaymentType=1;
	}
*/
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------


//get adress number from custom field
//$customFieldArray = explode(':', $order_info[0]['shipping_custom_field']);
//print_r($customFieldArray);
//$vowels = array("\"", "\"", "}", "{");
//$RAddressNum = str_replace($vowels, "", $customFieldArray[1]);
$RAddressNum = $order_info[0]['shipping_address_2'];


//edit phone numbers
$PuCPhone=preg_replace("/^0/", "381", $this->config->get('serbia_delivery_pickup_contact_phone'));
$PuCPhone= preg_replace("/^\+/", "", $PuCPhone);

$RCPhone=preg_replace("/^0/", "381", $order_info[0]['telephone']);
$RCPhone= preg_replace("/^\+/", "", $RCPhone);
		
// check if more than 0 record found
if(count($order_info)>0){
    
    //get reciver city
      $reciver_city =  $this->model_sale_serbia_delivery->getDeliveryCity($order_info[0]['shipping_city']); 
      
   //check reciver city   
if(count($reciver_city)>0){

  
  $order_sent=array(
 "CClientID"=>  $this->config->get('serbia_delivery_customer_clientID'),
  "CName"=>  $this->config->get('serbia_delivery_customer_name'),
  "CAddress"=>  $this->config->get('serbia_delivery_customer_address'),
  "CAddressNum"=>  $this->config->get('serbia_delivery_customer_addressnum'),
  "CTownID"=>  $delivery_city['id_city'],
  "CCName"=>  $this->config->get('serbia_delivery_customer_contact_name'),
  "CCPhone"=>  $this->config->get('serbia_delivery_customer_contact_phone'),
  "PuClientID"=>  $this->config->get('serbia_delivery_pickup_clientID'),
  "PuName"=> $this->config->get('serbia_delivery_pickup_name'),
  "PuAddress"=>  $this->config->get('serbia_delivery_pickup_address'),
  "PuAddressNum"=>  $this->config->get('serbia_delivery_pickup_addressnum'),
  "PuTownID"=>  $delivery_city['id_city'],
  "PuCName"=> $this->config->get('serbia_delivery_pickup_contact_name'),
  "PuCPhone"=>  $PuCPhone,
  "RClientID"=>  'UK0'.date('Hi'),
  "RName"=>  $order_info[0]['shipping_firstname'].' '.$order_info[0]['shipping_lastname'],
  "RAddress"=> str_replace('/','-',$order_info[0]['shipping_address_1']),
  "RAddressNum"=> $RAddressNum,
  "RTownID"=> $reciver_city['id_city'],
  "RCName"=> $order_info[0]['shipping_firstname'].' '.$order_info[0]['shipping_lastname'],
  "RCPhone"=>$RCPhone,
  "DlTypeID"=>  2,
  "PaymentBy"=> $PaymentBy ,
  "PaymentType"=> $PaymentType,
  "BuyOut"=> $order_info[0]['total']*100,
  "BuyOutFor"=> 0,
  "BuyOutAccount"=> $this->config->get('serbia_delivery_BuyOutAccount'),
  "Value"=>  $order_info[0]['total']*100,
  "Content"=> "Sadrzaj narudzbenice pod brojem ".$order_info[0]['order_id'],
  "Mass"=> count($order_info_list)*200,
  "Note"=>  $order_info[0]['comment'],
  "ReferenceID"=> $delivery_number['number'],
  "ReturnDoc"=> 0,
  "PackageList"=> $products_arr);
        
        
  //----------------------------------------------------------------------SEND ORDER-------------------------------------------------------------------------------------------      
         // show products data in json format
  // file_put_contents($logLocation."send_order_log_". date('Y_m_d').".txt","\n"."SEND:". json_encode($orders_arr)." : - Vreme=". date('YmdHis')."    \n",FILE_APPEND);
  
//  echo json_encode($order_sent, JSON_UNESCAPED_UNICODE);
    $get_data = $this->callAPI('POST',$this->config->get('serbia_delivery_urlApi').'/data/addshipment', json_encode($order_sent, JSON_UNESCAPED_UNICODE));
    $response = json_decode($get_data, true);



//array_key_exists("Message", $response);
if (is_array($response)   ){
      $responseMessage=$response['Message'];
	  $ModelState=$response['ModelState'];
      	$data['error_warning'] = "Vas zahtev je odbijen. Razlog je ".$responseMessage.". Opis: ".json_encode($ModelState);
      		$this->session->data['error'] = "Vas zahtev je odbijen. Razlog je ".$responseMessage.". Opis: ".json_encode($ModelState);
      		print_r($response);
      		  $delivery_number = $this->model_sale_serbia_delivery->deleteDeliveryNumber($delivery_number['delivery_number']);
    }
    else{
     
       $responseMessage=NULL;
	   $data['success'] = $this->language->get('text_success');
       	$this->session->data['success'] = $this->language->get('text_success');
		   $this->model_sale_serbia_delivery->addSentOrder($order_id,$delivery_number['delivery_number']);
		
		
	// SLANJE maila ------------------------------------------------------------------------------------------------------------------------------------------------------	
			$dataMail['mail']  = 'info@stivsolutions.com';//  $order_info[0]['email']; 	
$dataMail['message']  = "Vasa narudzbenica pod brojem ".$order_info[0]['order_id']." je poslata. Broj posiljke je".$delivery_number['delivery_number']; 
$dataMail['order_id']  = $order_info[0]['order_id']; 
$dataMail['delivery_number']  =   $delivery_number['delivery_number']; 
$this-> sendEmail($dataMail);
//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
      // 	print_r($response);
    }


//print_r($response);
//echo $response;
// file_put_contents($logLocation."send_order_log_". date('Y_m_d').".txt","\n"."ANSWER:". json_encode($response)." : - Vreme=". date('YmdHis')."    \n",FILE_APPEND);   
  //----------------------------------------------------------------------SEND ORDER-------------------------------------------------------------------------------------------             
        
        
// file_put_contents($logLocation."send_order_log_". date('Y_m_d').".txt","\n"."END : - Vreme=". date('YmdHis')."    \n",FILE_APPEND);
// file_put_contents($logLocation."send_order_log_". date('Y_m_d').".txt","\n"."----------------------------------------------------------------------------------------------------------------------------- \n",FILE_APPEND);



}
else{

    
    	$data['error_warning'] = "Grad pod nazivom ".$order_info[0]['shipping_city']." ne postoji u bazi. Ispravite naziv grada na narudzbenici";
    	
    	$this->session->data['error'] = "Grad pod nazivom ".$order_info[0]['shipping_city']." ne postoji u bazi. Ispravite naziv grada na narudzbenici";
    	
    	
    	$delivery_number = $this->model_sale_serbia_delivery->deleteDeliveryNumber($delivery_number['delivery_number']);
    
}

}
 
// no products found will be here
else{
    
    
      /*    file_put_contents($logLocation."send_order_log_". date('Y_m_d').".txt","\n"."response code - 404 No products found : - Vreme=". date('YmdHis')."    \n",FILE_APPEND); 
        
         file_put_contents($logLocation."send_order_log_". date('Y_m_d').".txt","\n"."END : - Vreme=". date('YmdHis')."    \n",FILE_APPEND); 
    file_put_contents($logLocation."send_order_log_". date('Y_m_d').".txt","\n"."----------------------------------------------------------------------------------------------------------------------------- \n",FILE_APPEND); 
  */
 
    // tell the user no products found
    
    
    	$data['error_warning'] = "Narudzbenica ne postoji";
    		$this->session->data['error'] = "Narudzbenica ne postoji";
    		
    		$delivery_number = $this->model_sale_serbia_delivery->deleteDeliveryNumber($delivery_number['delivery_number']);
    
}



if($form_id=='2'){
			$url = '';

			if (isset($this->request->get['filter_return_id'])) {
				$url .= '&filter_return_id=' . $this->request->get['filter_return_id'];
			}

			if (isset($this->request->get['filter_order_id'])) {
				$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
			}

			if (isset($this->request->get['filter_customer'])) {
				$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_product'])) {
				$url .= '&filter_product=' . urlencode(html_entity_decode($this->request->get['filter_product'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_model'])) {
				$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_return_status_id'])) {
				$url .= '&filter_return_status_id=' . $this->request->get['filter_return_status_id'];
			}

			if (isset($this->request->get['filter_date_added'])) {
				$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
			}

			if (isset($this->request->get['filter_date_modified'])) {
				$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

$this->response->redirect($this->url->link('sale/serbia_delivery', 'user_token=' . $this->session->data['user_token'] . $url, true));

}
else{
	$url = '';

   	$this->response->redirect($this->url->link('sale/order', 'user_token=' . $this->session->data['user_token'] . $url, true));

	
}


}

 public function callAPI($method, $url, $data){
     $login=$this->config->get('serbia_delivery_username');
     $password=$this->config->get('serbia_delivery_password');
   $curl = curl_init();
   switch ($method){
      case "POST":
         curl_setopt($curl, CURLOPT_POST, 1);
         if ($data)
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
         break;
      case "PUT":
         curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
         if ($data)
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);			 					
         break;
      default:
         if ($data)
            $url = sprintf("%s?%s", $url, http_build_query($data));
   }
   // OPTIONS:
   curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_USERPWD, "$login:$password");

   curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
   curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
      'Content-Type: application/json',
   ));

   // EXECUTE:
   $result = curl_exec($curl);
   if(!$result){die("Connection Failure");}
   curl_close($curl);
   return $result;
}

public function createPDF(){
	$this->load->model('sale/serbia_delivery');

	if (isset($this->request->get['order_id'])) {
		$order_id = $this->request->get['order_id'];
	} else {
		$order_id = '71';
	}

if (isset($this->request->get['form_id'])) {
		$form_id = $this->request->get['form_id'];
	} else {
		$form_id = '';
	}

	$deliveryOrder = $this->model_sale_serbia_delivery->getDeliveryByOrderId($order_id);
	$delivery_number = $this->model_sale_serbia_delivery->getDeliveryNumberByDeliveryNumber($deliveryOrder[0]['delivery_id']);
	$order_info = $this->model_sale_serbia_delivery->getOrderForSent($order_id);
	$order_info_list = $this->model_sale_serbia_delivery->getOrderListForSent($order_id);
	$weight=count($order_info_list)*200;


	$RCPhone=preg_replace("/^0/", "381", $order_info[0]['telephone']);
	$RCPhone= preg_replace("/^\+/", "", $RCPhone);


//get adress number from custom field
//$customFieldArray = explode(':', $order_info[0]['shipping_custom_field']);
//print_r($customFieldArray);
//$vowels = array("\"", "\"", "}", "{");
//$RAddressNum = str_replace($vowels, "", $customFieldArray[1]);
$RAddressNum=$order_info[0]['shipping_address_2'];


//chek shiping and payment method
if($order_info[0]['shipping_method']=='Besplatna dostava' || $order_info[0]['payment_method']=='Platna kartica' || $order_info[0]['payment_method']=='Uplata na račun'){
	  $PaymentType="Nalogodalac - virmanski";
	  $TotalPayment = 0;

 }		
	 else{
		$PaymentType="Primalac";
		$TotalPayment = $order_info[0]['total'];
	 }	


    //	 $this->load->library('TCPDF/tcpdf');
    require_once(DIR_SYSTEM.'library/TCPDF/tcpdf.php');
  $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
 //->ob_start();
// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('STIV Solutions');
$pdf->SetTitle('D Express');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set a barcode on the page footer
$pdf->setBarcode(date('Y-m-d H:i:s'));

// set font
$pdf->SetFont('freeserif', '', 11);

// add a page
$pdf->AddPage();


// create some HTML content
$html = '
<!-- POČETAK - NAZIV KURIRSKE SLUŽBE -->

<p style="text-align: center; font-size: 12px;"><strong>
   D Express doo, Zage Malivuk 1, Beograd
   </strong>
</p>

<!-- KRAJ - NAZIV KURIRSKE SLUŽBE -->




<!-- POČETAK - STILA ZA TABELU NAZIVA FIRME -->

<style type="text/css">
.tg {
    border-collapse:collapse;
    border-spacing:0;
}
 .tg td{
    border-color:#ffffff;
    border-style:solid;
    border-width:1px;
     overflow:hidden;
    padding:10px 5px;
    word-break:normal;
}
 .tg th{
    border-color:#ffffff;
    border-style:solid;
    border-width:1px;
     font-weight:normal;
    overflow:hidden;
    padding:10px 5px;
    word-break:normal;
}
 .tg .tg-0lax{
    text-align:left;
    vertical-align:top
}
 @media screen and (max-width: 767px) {
    .tg {
        width: auto !important;
    }
    .tg col {
        width: auto !important;
    }
    .tg-wrap {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
}
</style>

<!-- KRAJ - STILA ZA TABELU NAZIVA FIRME -->




<!-- POČETAK - TABELE NAZIVA FIRME I BROJA 1/1 -->

<div class="tg-wrap">
   <table class="tg">
      <tbody>
         <tr>
            <td class="tg-0lax">
               <p><strong>Pošiljalac:
                 </strong><br />
                '.$this->config->get('serbia_delivery_customer_name').',
                '.$this->config->get('serbia_delivery_customer_address').',
                '.$this->config->get('serbia_delivery_customer_addressnum').',
                '.$this->config->get('serbia_delivery_pickup_town_name').',
                '.$this->config->get('serbia_delivery_customer_contact_name').',
                '.$this->config->get('serbia_delivery_customer_contact_phone').'
               </p>
            </td>
            <td class="tg-0lax">
               <p style="text-align: right; font-size: 26px;"><strong>1/1</strong></p>
            </td>
         </tr>
      </tbody>
   </table>
</div>

<!-- KRAJ - TABELE NAZIVA FIRME I BROJA 1/1 -->
';

// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');

$pdf->SetFont('freeserif', '', 10);

// define barcode style
$style = array(
    'position' => 'C',
    'align' => 'C',
    'stretch' => false,
    'fitwidth' => true,
    'cellfitalign' => '',
    'border' => false,
    'hpadding' => 'auto',
    'vpadding' => 'auto',
    'fgcolor' => array(0,0,0),
    'bgcolor' => false, //array(255,255,255),
    'text' => false,
    'font' => 'helvetica',
    'fontsize' => 8,
    'stretchtext' => 4
);



// CODE 128 A
//$pdf->Cell(0, 0, 'CODE 128 A', 0, 1);
$pdf->write1DBarcode($delivery_number['delivery_number'], 'C128', '', '', '', 40, 1, $style, 'N');

///--------------------------------------------------------------------------------------------------------------------------------------------


// create some HTML content
$html = '
<!-- POČETAK - BROJ ZA PRAĆENJE I INFORMACIJE O KUPCU -->

<p style="text-align: center; font-size: 26px;">
        
    '.$delivery_number['delivery_number'].'
        
    <br />
   <strong><span style="font-size: 20px;">
   
   Primalac:
   
   </span><br />
   
   '.$order_info[0]['shipping_firstname'].' '.$order_info[0]['shipping_lastname'].'
   
   <br />
   
   '.str_replace('/','-',$order_info[0]['shipping_address_1']).' '.$RAddressNum.'
   
   <br />
   
   '.$order_info[0]['shipping_postcode'].' '.$order_info[0]['shipping_city'].'
   
   <br />
   
   '.$RCPhone.'
   
   </strong>
</p>

<!-- KRAJ - BROJ ZA PRAĆENJE I INFORMACIJE O KUPCU -->

   
  
  
<!-- POČETAK - STIL ZA INFORMACIJE O DOSTAVI -->

<style type="text/css">
.tg {
    border-collapse:collapse;
    border-spacing:0;
}
 .tg td{
    border-color:#ffffff;
    border-style:solid;
    border-width:1px;
     overflow:hidden;
    padding:10px 5px;
    word-break:normal;
}
 .tg th{
    border-color:#ffffff;
    border-style:solid;
    border-width:1px;
     font-weight:normal;
    overflow:hidden;
    padding:10px 5px;
    word-break:normal;
}
 .tg .tg-0lax{
    text-align:left;
    vertical-align:top
}
 @media screen and (max-width: 767px) {
    .tg {
        width: auto !important;
    }
    .tg col {
        width: auto !important;
    }
    .tg-wrap {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
}
</style>

<!-- KRAJ - STIL ZA INFORMACIJE O DOSTAVI -->




<!-- POČETAK - INFORMACIJE O DOSTAVI -->

<div class="tg-wrap">
   <table class="tg">
      <tbody>
         <tr>
            <td class="tg-0lax">
               <p><strong>Referentni broj: '.$delivery_number['number'].'<br />
                  Usluga plaćanja: '.$PaymentType.'<br />
                  Povratna dokumentacija: -<br />
                  Otkupnina: '.$TotalPayment.'<br />
                  Sadržaj: Sadržaj narudzbenice pod brojem '.$order_info[0]['order_id'].'<br />
                  Masa: '.$weight.'</strong>
               </p>
            </td>
            <td class="tg-0lax">
               <p><strong>Napomena:</strong> '.$order_info[0]['comment'].'</p>
            </td>
         </tr>
      </tbody>
   </table>
</div>
<p style="text-align: center;"><strong>Vreme &scaron;tampe: '.date('Y-m-d H:i:s').'</strong></p>

<!-- KRAJ - INFORMACIJE O DOSTAVI -->
';

// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');

// -----------------------------------------------------------------------------

    
    // Clean any content of the output buffer
ob_end_clean();
    
//Close and output PDF document
$pdf->Output('Nalepnica_'.$delivery_number['delivery_number'].'.pdf','D','_blankpage');
}
public function createPDFSelected(){
echo 'bio';
	$this->load->model('sale/serbia_delivery');

	$orders = array();

	if (isset($this->request->post['selected'])) {
		$orders = $this->request->post['selected'];
	}

if (isset($this->request->get['form_id'])) {
	$form_id = $this->request->get['form_id'];
} else {
	$form_id = '';
}

//------------------------config page--------------------------------------------------------------------------------------------------------
   //	 $this->load->library('TCPDF/tcpdf');
   require_once(DIR_SYSTEM.'library/TCPDF/tcpdf.php');
   $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
  //->ob_start();
 // set document information
 $pdf->SetCreator(PDF_CREATOR);
 $pdf->SetAuthor('STIV Solutions');
 $pdf->SetTitle('D Express');
 $pdf->SetSubject('TCPDF Tutorial');
 $pdf->SetKeywords('TCPDF, PDF, example, test, guide');
 
 // remove default header/footer
 $pdf->setPrintHeader(false);
 $pdf->setPrintFooter(false);
 
 // set default monospaced font
 $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
 
 // set margins
 $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
 $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
 $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
 
 // set auto page breaks
 $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
 
 // set image scale factor
 $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
 
 // set some language-dependent strings (optional)
 if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	 require_once(dirname(__FILE__).'/lang/eng.php');
	 $pdf->setLanguageArray($l);
 }
 
 // ---------------------------------------------------------
 
 // set a barcode on the page footer
 $pdf->setBarcode(date('Y-m-d H:i:s'));
 
 // set font
 $pdf->SetFont('freeserif', '', 11);
 
 // add a page
 $pdf->AddPage();
 

// define barcode style
$style = array(
    'position' => 'C',
    'align' => 'C',
    'stretch' => false,
    'fitwidth' => true,
    'cellfitalign' => '',
    'border' => false,
    'hpadding' => 'auto',
    'vpadding' => 'auto',
    'fgcolor' => array(0,0,0),
    'bgcolor' => false, //array(255,255,255),
    'text' => false,
    'font' => 'helvetica',
    'fontsize' => 8,
    'stretchtext' => 4
);



//------------------------------------------------------------------------------------------------------------------------------------------

$columnNumber=1;
$html="<table>";

	// output the HTML content
	$pdf->writeHTML("<table>", true, false, true, false, '');	

	foreach ($orders as $order_id) {
		$deliveryOrder = $this->model_sale_serbia_delivery->getDeliveryByOrderId($order_id);
		$delivery_number = $this->model_sale_serbia_delivery->getDeliveryNumberByDeliveryNumber($deliveryOrder[0]['delivery_id']);
/* Kada koristimo 3 kolone
if($columnNumber==1 ){

	echo "<tr>
	<td>
			A".$columnNumber."
		</td>";
			$columnNumber++;
}elseif($columnNumber==2){
	echo "<td>
	B".$columnNumber."
	</td>";
	$columnNumber++;
}elseif($columnNumber=3){
	echo "<td>
	 C".$columnNumber."
	</td>
	</tr>";
		$columnNumber=1;
	}
*/
if($columnNumber%2!=0 ){
	
	$html .="<tr><td>";
	$html .=$this->addHTMLHeader($order_id);
	$params = $pdf->serializeTCPDFtagParameters(array($delivery_number['delivery_number'], 'C128', '', '', 80, 30, 0.4, array('position'=>'S', 'border'=>true, 'padding'=>4, 'fgcolor'=>array(0,0,0), 'bgcolor'=>array(255,255,255), 'text'=>true, 'font'=>'helvetica', 'fontsize'=>8, 'stretchtext'=>4), 'N'));
	$html .= '<tcpdf method="write1DBarcode" params="'.$params.'" />';
	$html .=$this->addHTMLFooter($order_id);
	$html .="</td>";
	
			$columnNumber++;
}else{

	$html .="<td>";
	$html .=$this->addHTMLHeader($order_id);
	$params = $pdf->serializeTCPDFtagParameters(array($delivery_number['delivery_number'], 'C128', '', '', 80, 30, 0.4, array('position'=>'S', 'border'=>true, 'padding'=>4, 'fgcolor'=>array(0,0,0), 'bgcolor'=>array(255,255,255), 'text'=>true, 'font'=>'helvetica', 'fontsize'=>8, 'stretchtext'=>4), 'N'));
	$html .= '<tcpdf method="write1DBarcode" params="'.$params.'" />';
	$html .=$this->addHTMLFooter($order_id);
	$html .="</td></tr>";
	$columnNumber++;
}





	}

	$html .="</table>";

	// output the HTML content
	$pdf->writeHTML($html, true, false, true, false, '');	
	
 // Clean any content of the output buffer
 ob_end_clean();
    
 //Close and output PDF document
 $pdf->Output('Nalepnica_'.$delivery_number['delivery_number'].'.pdf','D','_blankpage');


}

public function addHTMLHeader($order_id){

	$deliveryOrder = $this->model_sale_serbia_delivery->getDeliveryByOrderId($order_id);
	$delivery_number = $this->model_sale_serbia_delivery->getDeliveryNumberByDeliveryNumber($deliveryOrder[0]['delivery_id']);
	$order_info = $this->model_sale_serbia_delivery->getOrderForSent($order_id);
	$order_info_list = $this->model_sale_serbia_delivery->getOrderListForSent($order_id);
	$weight=count($order_info_list)*200;


	$RCPhone=preg_replace("/^0/", "381", $order_info[0]['telephone']);
	$RCPhone= preg_replace("/^\+/", "", $RCPhone);


//get adress number from custom field
//$customFieldArray = explode(':', $order_info[0]['shipping_custom_field']);
//print_r($customFieldArray);
//$vowels = array("\"", "\"", "}", "{");
//$RAddressNum = str_replace($vowels, "", $customFieldArray[1]);
$RAddressNum=$order_info[0]['shipping_address_2'];


//chek shiping and payment method
if($order_info[0]['shipping_method']=='Besplatna dostava' || $order_info[0]['payment_method']=='Platna kartica' || $order_info[0]['payment_method']=='Uplata na račun'){
	  $PaymentType="Nalogodalac - virmanski";
	  $TotalPayment = 0;

 }		
	 else{
		$PaymentType="Primalac";
		$TotalPayment = $order_info[0]['total'];
	 }	
// create some HTML content
$htmlHeader = '
<!-- POČETAK - NAZIV KURIRSKE SLUŽBE -->

<p style="text-align: center; font-size: 12px;"><strong>
   D Express doo, Zage Malivuk 1, Beograd
   </strong>
</p>

<!-- KRAJ - NAZIV KURIRSKE SLUŽBE -->




<!-- POČETAK - STILA ZA TABELU NAZIVA FIRME -->

<style type="text/css">
.tg {
    border-collapse:collapse;
    border-spacing:0;
}
 .tg td{
    border-color:#ffffff;
    border-style:solid;
    border-width:1px;
     overflow:hidden;
    padding:10px 5px;
    word-break:normal;
}
 .tg th{
    border-color:#ffffff;
    border-style:solid;
    border-width:1px;
     font-weight:normal;
    overflow:hidden;
    padding:10px 5px;
    word-break:normal;
}
 .tg .tg-0lax{
    text-align:left;
    vertical-align:top
}
 @media screen and (max-width: 767px) {
    .tg {
        width: auto !important;
    }
    .tg col {
        width: auto !important;
    }
    .tg-wrap {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
}
</style>

<!-- KRAJ - STILA ZA TABELU NAZIVA FIRME -->




<!-- POČETAK - TABELE NAZIVA FIRME I BROJA 1/1 -->

<div class="tg-wrap">
   <table class="tg">
      <tbody>
         <tr>
            <td class="tg-0lax">
               <p><strong>Pošiljalac:
                 </strong><br />
                '.$this->config->get('serbia_delivery_customer_name').',
                '.$this->config->get('serbia_delivery_customer_address').',
                '.$this->config->get('serbia_delivery_customer_addressnum').',
                '.$this->config->get('serbia_delivery_pickup_town_name').',
                '.$this->config->get('serbia_delivery_customer_contact_name').',
                '.$this->config->get('serbia_delivery_customer_contact_phone').'
               </p>
            </td>
            <td class="tg-0lax">
               <p style="text-align: right; font-size: 26px;"><strong>1/1</strong></p>
            </td>
         </tr>
      </tbody>
   </table>
</div>

<!-- KRAJ - TABELE NAZIVA FIRME I BROJA 1/1 -->
';

	return $htmlHeader;
}




public function addHTMLFooter($order_id){
$deliveryOrder = $this->model_sale_serbia_delivery->getDeliveryByOrderId($order_id);
	$delivery_number = $this->model_sale_serbia_delivery->getDeliveryNumberByDeliveryNumber($deliveryOrder[0]['delivery_id']);
	$order_info = $this->model_sale_serbia_delivery->getOrderForSent($order_id);
	$order_info_list = $this->model_sale_serbia_delivery->getOrderListForSent($order_id);
	$weight=count($order_info_list)*200;


	$RCPhone=preg_replace("/^0/", "381", $order_info[0]['telephone']);
	$RCPhone= preg_replace("/^\+/", "", $RCPhone);


//get adress number from custom field
//$customFieldArray = explode(':', $order_info[0]['shipping_custom_field']);
//print_r($customFieldArray);
//$vowels = array("\"", "\"", "}", "{");
//$RAddressNum = str_replace($vowels, "", $customFieldArray[1]);
$RAddressNum=$order_info[0]['shipping_address_2'];


//chek shiping and payment method
if($order_info[0]['shipping_method']=='Besplatna dostava' || $order_info[0]['payment_method']=='Platna kartica' || $order_info[0]['payment_method']=='Uplata na račun'){
	  $PaymentType="Nalogodalac - virmanski";
	  $TotalPayment = 0;

 }		
	 else{
		$PaymentType="Primalac";
		$TotalPayment = $order_info[0]['total'];
	 }	


// create some HTML content
$htmlFooter = '
<!-- POČETAK - BROJ ZA PRAĆENJE I INFORMACIJE O KUPCU -->

<p style="text-align: center; font-size: 26px;">
        
    '.$delivery_number['delivery_number'].'
        
    <br />
   <strong><span style="font-size: 20px;">
   
   Primalac:
   
   </span><br />
   
   '.$order_info[0]['shipping_firstname'].' '.$order_info[0]['shipping_lastname'].'
   
   <br />
   
   '.str_replace('/','-',$order_info[0]['shipping_address_1']).' '.$RAddressNum.'
   
   <br />
   
   '.$order_info[0]['shipping_postcode'].' '.$order_info[0]['shipping_city'].'
   
   <br />
   
   '.$RCPhone.'
   
   </strong>
</p>

<!-- KRAJ - BROJ ZA PRAĆENJE I INFORMACIJE O KUPCU -->

   
  
  
<!-- POČETAK - STIL ZA INFORMACIJE O DOSTAVI -->

<style type="text/css">
.tg {
    border-collapse:collapse;
    border-spacing:0;
}
 .tg td{
    border-color:#ffffff;
    border-style:solid;
    border-width:1px;
     overflow:hidden;
    padding:10px 5px;
    word-break:normal;
}
 .tg th{
    border-color:#ffffff;
    border-style:solid;
    border-width:1px;
     font-weight:normal;
    overflow:hidden;
    padding:10px 5px;
    word-break:normal;
}
 .tg .tg-0lax{
    text-align:left;
    vertical-align:top
}
 @media screen and (max-width: 767px) {
    .tg {
        width: auto !important;
    }
    .tg col {
        width: auto !important;
    }
    .tg-wrap {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
}
</style>

<!-- KRAJ - STIL ZA INFORMACIJE O DOSTAVI -->




<!-- POČETAK - INFORMACIJE O DOSTAVI -->

<div class="tg-wrap">
   <table class="tg">
      <tbody>
         <tr>
            <td class="tg-0lax">
               <p><strong>Referentni broj: '.$delivery_number['number'].'<br />
                  Usluga plaćanja: '.$PaymentType.'<br />
                  Povratna dokumentacija: -<br />
                  Otkupnina: '.$TotalPayment.'<br />
                  Sadržaj: Sadržaj narudzbenice pod brojem '.$order_info[0]['order_id'].'<br />
                  Masa: '.$weight.'</strong>
               </p>
            </td>
            <td class="tg-0lax">
               <p><strong>Napomena:</strong> '.$order_info[0]['comment'].'</p>
            </td>
         </tr>
      </tbody>
   </table>
</div>
<p style="text-align: center;"><strong>Vreme &scaron;tampe: '.date('Y-m-d H:i:s').'</strong></p>

<!-- KRAJ - INFORMACIJE O DOSTAVI -->
';
	return $htmlFooter;
}








public function sendEmail($dataMail){

	$this->load->model('setting/setting');
	$this->load->model('sale/order');

	$order_info = $this->model_sale_order->getOrder($dataMail['order_id']);
		$from = $this->model_setting_setting->getSettingValue('config_email', $order_info['store_id']);
		
		if (!$from) {
			$from = $this->config->get('config_email');
		}
		
		$mail = new Mail($this->config->get('config_mail_engine'));
		$mail->parameter = $this->config->get('config_mail_parameter');
		$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
		$mail->smtp_username = $this->config->get('config_mail_smtp_username');
		$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
		$mail->smtp_port = $this->config->get('config_mail_smtp_port');
		$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

		$mail->setTo($dataMail['mail']);
		$mail->setFrom($from);
		$mail->setSender(html_entity_decode($order_info['store_name'], ENT_QUOTES, 'UTF-8'));
		$mail->setSubject(html_entity_decode(sprintf('%s - Narudžbina %s', 'Status Vase narudzbenice', $dataMail['order_id']), ENT_QUOTES, 'UTF-8'));
		$mail->setHtml($this->load->view('mail/serbia_delivery', $dataMail));
		$mail->send();


}




}