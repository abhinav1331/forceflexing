<?php 
class FF_Payouts
{
	public $Stripe_Keys = array();
	public $Stripe_PBKeys;
	public $Stripe_SCKeys;
	
	public function __construct()
	{
		require(dirname(__FILE__) . '/stripe/init.php');		
		$arguments = func_get_args();
		if(isset($arguments[0]->live_mode))
		{
			$this->Stripe_PBKeys = $arguments[0]->Lpb_key;	
			$this->Stripe_SCKeys = $arguments[0]->Lsk_key;	
		}
		else
		{
			$this->Stripe_PBKeys = $arguments[0]->pb_key;	
			$this->Stripe_SCKeys = $arguments[0]->sk_key;	
		}
		/* Initialise Secret Keys */
		\Stripe\Stripe::setApiKey($this->Stripe_SCKeys);		
	}
	
	public function Create_customer($tokenID,$EmailID)
	{
		
		$customer = \Stripe\Customer::create(array(
		  "description" => "Customer for $EmailID",
		  "source" => $tokenID,
		  "email" =>$EmailID		  
		));
		return $customer->jsonSerialize();		
	}
	
	public function Update_customer($tokenID,$CustomerID)
	{
		$customer = \Stripe\Customer::retrieve($CustomerID);
		$customer->source = $tokenID;
		$customer->save();
		return $customer->jsonSerialize();
	}
	
	public function ListAllCust()
	{
		return \Stripe\Customer::all(array("limit" => 10));			
	}	
	
	public function VerifyEmployer()
	{
		
	$popup = "<script src='https://checkout.stripe.com/checkout.js'></script>
		<script>
		var handler = StripeCheckout.configure({
			key: '".$this->Stripe_PBKeys."',
			image: '".SITEURL."static/images/logo.png',
			locale: 'auto',
			token: function(token) 
			{	
				$.ajax({
					type:'POST',
					url:'".SITEURL."employer/StripeCardSave',
					data:{TokenID:token.id,ResponseDetails:token,Email:token.email},
					success:function(res)
					{
						toastr.success('Payment Details Saved.', 'Success');
					}
				});
								
			}
		});

		document.getElementById('customButton').addEventListener('click', function(e) {
		  // Open Checkout with further options:
		  handler.open({
			name: 'ForceFlexing, Inc.',
			//description: 'Grow WithUs',
			panelLabel:'Save Card Details',			
			label:'Save Card Details',
			allowRememberMe:false,			
		  });
		  e.preventDefault();
		});

		// Close Checkout on page navigation:
		window.addEventListener('popstate', function() {
		  handler.close();
		});
		</script>";
		
		return $popup;			
	}
	
	public function Create_Charge($Amount,$CustomerStripeID,$ActivityID)
	{
		$charge = \Stripe\Charge::create(array(
			  "amount" => $Amount,
			  "currency" => "usd",
			  "customer" => $CustomerStripeID,
			  "description" => "Charge for Activity ID $ActivityID"
			));
		
		return $charge->jsonSerialize();

	}
}