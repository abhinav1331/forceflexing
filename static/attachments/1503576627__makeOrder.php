<?php 
$array =json_decode(file_get_contents("php://input"),true);
include('../wp-config.php');
global $wpdb;
$user_id=$array['userId'];
$total_price=$array['totalCharges'];
$all_products=$array['productList'];

//fetcht the payment details
$billing_first_name=$array['billing_first_name'];
$billing_last_name=$array['billing_last_name'];
$card_number=$array['card_number'];
$month=$array['month'];
$year=$array['year'];
$cvn=$array['cvn'];
$billing_email=$array['billing_email'];
$billing_address=$array['billing_address'];
$billing_city=$array['billing_city'];
$billing_zipcode=$array['billing_zipcode'];
$billing_country=$array['billing_country'];
$billing_state=$array['billing_state'];



//billing address of a user
$billing_add=array(
'first_name' => $billing_first_name,
'last_name'  => $billing_last_name,
'email'      => $billing_email,
'phone'      => '',
'address_1'  => $billing_address,
'address_2'  => '', 
'city'       => $billing_city,
'state'      => $billing_state,
'postcode'   => $billing_zipcode,
'country'    => $billing_country
);

//get shipping details of a user
$shipping_first_name=get_user_meta( $user_id,'shipping_first_name',true);
$shipping_last_name=get_user_meta( $user_id,'shipping_last_name',true);
$shipping_email=get_user_meta( $user_id,'shipping_email',true);
$shipping_phone=get_user_meta( $user_id,'shipping_phone',true);
$shipping_country_custom=get_user_meta( $user_id,'shipping_country_custom',true);
$shipping_address_1=get_user_meta( $user_id,'shipping_address_1',true);
$shipping_city=get_user_meta( $user_id,'shipping_city',true);
$shipping_state_custom=get_user_meta( $user_id,'shipping_state_custom',true);
$shipping_postcode=get_user_meta( $user_id,'shipping_postcode',true);


//shipping address for a user
$shipping_address = array(
	'first_name' => $shipping_first_name,
	'last_name'  => $shipping_last_name,
	'email'      => $shipping_email,
	'phone'      => $shipping_phone,
	'address_1'  => $shipping_address_1,
	'address_2'  => '', 
	'city'       => $shipping_city,
	'state'      => $shipping_state_custom,
	'postcode'   => $shipping_postcode,
	'country'    => $shipping_country_custom
);

	$billing_address = sanitize_email($billing_address);
	if(empty($billing_address)) { $billing_address = $_POST['billing_address']; }
		$postArr = array();
		$today = date("Ymd");
		$rand = strtoupper(substr(uniqid(sha1(time())),0,4));
		$unique = $today . $rand;
		$amount = $total_price;
		$paymentType = urlencode('Sale');                   // or 'Sale' or 'Authorization'  
		$billing_first_name = urlencode($billing_first_name);   
		$billing_last_name = urlencode($billing_last_name);  
		$card_number = urlencode($card_number);  // credit card number  
		
		
		$year = urlencode($year);     // expiry year  
		$cvn = urlencode($cvn);  // cvv2 or the last 3/4 digit at the back of credit card  
		
		$billing_city = urlencode($billing_city);    // city  
		$state = '';     // state  
		$billing_zipcode = urlencode($billing_zipcode);   // zipcode  
		$billing_country = urlencode($billing_country); // US or other valid country code  
		$currencyID = urlencode('USD');   // USD or other currency ('GBP', 'EUR', 'JPY', 'CAD', 'AUD')  
		$total_price = urlencode($total_price); // amount/rate  
		
		
		/*++++++++++++++++++Test Data+++++++++++++++++*/
		$month = urlencode($month); 	
		$ipaddress   =     $_SERVER['REMOTE_ADDR'];
		#update Credit card detail	end 		
		$expire_date = $month.''.$year;
		$post_values = array(
		// the API Login ID and Transaction Key must be replaced with valid values
		//"x_login"			=> "4UEu67rnyt", // Testing
		//"x_tran_key"		=> "35Uw4F6Q6wP76Yaf", // Testing
		"x_login"			=> "29L86Wtf8S", // Live
		"x_tran_key"		=> "65V233dqrjR6aL8c", // Live New 2	
		"x_version"			=> "3.1",
		"x_delim_data"		=> "TRUE",
		"x_delim_char"		=> "|",
		"x_relay_response"	=> "FALSE",

		"x_type"			=> "AUTH_CAPTURE",
		"x_method"			=> "CC",
		"x_card_num"		=>  $card_number, //"4111111111111111",
		"x_exp_date"		=> $expire_date,

		"x_amount"			=> $amount,
		"x_description"		=> "Topup Transaction",

		"x_first_name"		=> $billing_first_name,
		"x_last_name"		=> $billing_last_name,
		"x_email"			=> $billing_email,
		"x_address"			=> $billing_address,
		"x_state"			=> $billing_state,
		"x_city"			=> $billing_city,
		"x_country"			=> $billing_country,
		"x_customer_ip"		=>	$ipaddress,
		"x_card_code"     	=>	$cvn,
		"x_zip"				=> $billing_zipcode
		
		// Additional fields can be added here as outlined in the AIM integration
		// guide at: http://developer.authorize.net
		);
		
		$response_AUTH = cc_payment($post_values);
		// echo '<pre>';
		// print_r($response_AUTH);
		// exit();
		$sucess_status = $response_AUTH[0];
		//Response Code (1 = Approved, 2 = Declined, 3 = Error, 4 = Held for Review)
		
			if($sucess_status == 1)
			{
				$order = wc_create_order();
				//add products to order
				foreach($all_products as $product)
				{
					$product_id=$product['partId'];
					$product_color=$product['productColor'];
					if($product['productColor'] !="")
					{
						$variableProduct = new WC_Product_Variable($product_id);
						$all_variatons = $variableProduct->get_available_variations();
						foreach ($all_variatons as $variation) 
						{
							if ($variation['attributes']['attribute_pa_color'] == $product_color) 
							{
								$variationID = $variation['variation_id'];
								
							}
						}
						$varProduct = new WC_Product_Variation($variationID);
						$order->add_product($varProduct);
					}
					else
					{
						$product_obj = new WC_Product($product_id);
						$order->add_product($product_obj);
					}
				}
						
				update_post_meta($order->id,'_customer_user',$user_id);
				$order->set_address($billing_add, 'billing');
				$order->set_address( $shipping_address, 'shipping' );
				
				//add payment method 
				$payment=new WC_Gateway_Simplify_Commerce();
				$order->set_payment_method($payment);
				add_post_meta($order->id, 'transaction_id', $response_AUTH[6], true); 
				
				// add shipping details to order
				$shipping = new WC_Shipping_Rate();
				$shipping->id = 'flat_rate'; // OR with setter, if the class has it $object->setId('local_delivery');
				$shipping->label = 'Flat Rate';
				$shipping->cost  = $total_price;
				$shipping->method_id="Items";
				$order->add_shipping($shipping);
				
				$order->update_status('wc-processing'); 
				$order->set_total($total_price);
				$test=$wpdb->delete( 'wp_cart', array( 'userId' => $user_id) );
				$result="1";
			}
			else
			{
				$result=$response_AUTH[3];
			}
						
function cc_payment($post_values)
{
	
	//$post_url = "https://test.authorize.net/gateway/transact.dll"; //test
	$post_url = "https://secure.authorize.net/gateway/transact.dll"; //live
	
	$post_string = "";
	foreach( $post_values as $key => $value )
		{ $post_string .= "$key=" . urlencode( $value ) . "&"; }
	$post_string = rtrim( $post_string, "& " );
	
	$request = curl_init($post_url); // initiate curl object
	curl_setopt($request, CURLOPT_HEADER, 0); // set to 0 to eliminate header info from response
	curl_setopt($request, CURLOPT_RETURNTRANSFER, 1); // Returns response data instead of TRUE(1)
	curl_setopt($request, CURLOPT_POSTFIELDS, $post_string); // use HTTP POST to send form data
	curl_setopt($request, CURLOPT_SSL_VERIFYPEER, FALSE); // uncomment this line if you get no gateway response.
	$post_response = curl_exec($request); // execute curl post and store results in $post_response
	
	curl_close ($request);
	
	//convert response into array
	return	$response_array = explode($post_values["x_delim_char"],$post_response);

}

if($result=="1")
{
	$json = array("success"=> 1,"result"=> $result ,"error"=>"No Error Found");
}
else
{
	$json = array("success"=> 0,"result"=> $result ,"error"=>$result);
}
echo json_encode($json);