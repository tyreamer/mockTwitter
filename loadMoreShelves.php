<?PHP

require_once("./include/membersite_config.php");

$loggedIn = true;

if(!($fgmembersite->CheckLogin()))
{
	$loggedIn = false;
}	

$key = $_POST['qry'];

$servername = "localhost";
$username = "tylre";
$password = "tylrePass";
$db = "YellowstoneDB1";

$offset = $_POST['sort'];

// Create connection
$conn = new mysqli($servername, $username, $password, $db);	

			if (isset($_POST['sort']) && $_POST['sort'] != '' && $_POST['sort'] != undefined) {
			
				$qry = "Select collection_name, collection_id, collection_image
							from collection								
									where collection_name LIKE '%".$key."%'
											LIMIT 12 OFFSET ".$_POST['sort']."";
			}	
			
			else {
				$qry = "Select collection_name, collection_id, collection_image
							from collection								
									where collection_name LIKE '%".$key."%'
											LIMIT 12";
			}

		$result = $conn->query($qry);
		
		$collectionArr = array();
		
		while ($row = $result->fetch_assoc())
		{	
				array_push($collectionArr, $row);
		}
		
		if (sizeOf($collectionArr) > 0) {
				
			for ($i = 0; $i < sizeOf($collectionArr); $i++) {
				$offset = $offset +1;
				//Get Specifics
				$name 		 = $collectionArr[$i]['collection_name'];		
				$id		 = $collectionArr[$i]['collection_id'];	
				$collectionPicture = $collectionArr[$i]['collection_image'];
							

				?>

				   
						<div class="col-xs-6 col-md-3">
						  <div class="square">
								<div class= "postName"><a href="collection.php?id=<?php echo $id ?>"> <?php echo $name ?> </a></div>
								<div class="content">
									<a href="collection.php?id=<?php echo $id ?>">
										<div class= "bg" style="background-image: url(<?php echo $collectionPicture ?>)"></div>
									</a>
								</div>
						  </div>
						  <!-- For querying more -->
						  <span class="postSort" id="<?php echo $offset;?>"></span>
						</div>
	   

	<?php 
			}//for
		}
		else {?>
			<div class="row" style="text-align: center; padding: 50px;"> Sorry, no shelves matched those search terms. </div> 
		<?php }

?>