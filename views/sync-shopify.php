<?php
/*
  Schema for an "patronbase" performances table.

  Name:
  patronbase

  Structure:
  id – INT
  production_id – TEXT
  performance_id – TEXT
  venue – TEXT
  booking_url – TEXT
  date_time – DATETIME
  duration – INT
  status_code – TEXT
  date_modified – DATETIME
  date_created – DATETIME

*/
?>
<div id="body-container">
	<div id="body">
		<div id="self-container"><?
		if($rr->action != "sync")
		{
		?>
			<form action="<? echo $admin_path; ?>sync-shopify" method="post">
				<span>Sync with Shopify </span>
				<input name='action' type='hidden' value='sync'>
				<input name='submit' type='submit' value='Sync'>
			</form><?
		}
		else
		{
			$buy_id = end($oo->urls_to_ids(array('buy')));
			$buy_children = $oo->children($buy_id);
			$existing_product_ids = array();
			foreach($buy_children as $child)
			{
				$existing_product_ids[] = get_single_tag($child['deck']);
			}
			?>
			<script>
			var existing_product_ids = <?= json_encode($existing_product_ids); ?>;
			console.log(existing_product_ids);
			var isTest = false;
			if(isTest){
          var shopUrl = "https://bookstore-n-y-c-test.myshopify.com";
          var accessToken = "f5e95d28e4d2850830979b66aa4cab7e";
      }
      else{
          var shopUrl = "https://new-york-consolidated-2.myshopify.com";
          var accessToken = "0df4a2d60f5c99276aaba8f4265b06e4";
      }
      const query_all = `query FirstProduct {
          products(first:100) {
              edges {
                  node {
                      id
                      title
                      description
                      variants(first:1) {
                          edges {
                              node {
                                  title
                                  id
                                  priceV2 {
                                      amount
                                      currencyCode
                                  }
                              }
                          }
                      }
                  }
              }
          }   
      }`;
      const fetchQuery1 = () => {
		    // Define options for first query with no variables and body is string and not a json object
		    const optionsQuery_all = {
		        method: "post",
		        headers: {
		            "Content-Type": "application/graphql",
		            "X-Shopify-Storefront-Access-Token": accessToken
		        },
		        body: query_all
		    };

		    // Fetch data and remember product id
		    fetch(shopUrl + `/api/graphql`, optionsQuery_all)
		        .then(res => res.json())
		        .then(response => {
		            productId = response.data.products.edges[0].node.id; 
		            console.log("=============== Fetch First Product ===============");
		            console.log(JSON.stringify(response, null, 4));
		            // fetchQuery2(productId)  
		        });
			}
   	 </script>
 	 	<?
		}
		?>
		</div>
	</div>
</div>

<?php

?>
