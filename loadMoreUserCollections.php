<?PHP

require_once("./include/membersite_config.php");

$loggedIn = true;

if(!($fgmembersite->CheckLogin()))
{
	$loggedIn = false;
}	


$servername = "localhost";
$username = "tylre";
$password = "tylrePass";
$db = "YellowstoneDB1";

// Create connection
$conn = new mysqli($servername, $username, $password, $db);	


		if (isset($_POST['user'])) {
			
			$user = $fgmembersite->GetUserFromConnectName($_POST['user']);
			
			if (isset($_POST['sort']) && $_POST['sort'] != '' && $_POST['sort'] != undefined) {
				
					$qry = "Select collection_id, collection_name 
								from collection
									where collection_owner=".$user." 
									and collection_id < ".$_POST['sort']."	
										ORDER BY collection_id DESC
											LIMIT 12";
			}
			else {
			
					$qry = "Select collection_id, collection_name 
								from collection 
									where collection_owner=".$user."
										ORDER BY collection_id DESC
											LIMIT 12";
			}
		
		}
		else {
			return false;
		}
		
		
		$result = $conn->query($qry);
		
		$allCollections = array();
		
		while ($row = $result->fetch_assoc())
		{	
				array_push($allCollections, $row);
		}
		
		if (sizeOf($allCollections) == 0 ) {
			return false;
		}
		
		if ($user == $fgmembersite->CurrentUser()) {
			$isMyPage = true;
		}
	
		for ($j = 0; $j < sizeOf($allCollections); $j++) {
				
				$cName  = $allCollections[$j]['collection_name'];
				$cImage = $fgmembersite->GetCollectionImage($allCollections[$j]['collection_id']);
				
				if ($cImage == '') {
					$cImage = 'images/LinkDefaultImg.jpg';
				}
				
				$cid = $allCollections[$j]['collection_id'];
				
	?>				<div class="col-md-3 col-sm-3 col-sm-12">
						<div id="<?php echo $cid ?>" style="position: relative;">							
							<div class="editcollection">
									 <?php if($isMyPage) { ?> <a href="#collectionModal" data-toggle="modal" onclick="openCollectionModal(<?php echo $cid; ?>)"><i class="glyphicon glyphicon-pencil" style="font-size: 10px;"></i></a><?php }?>
							</div>
							<div class="collection"  onclick="location.href='collection.php?id=<?php echo $allCollections[$j]['collection_id']?>'"  style="background: url(<?php echo $cImage; ?>) no-repeat center center;  background-size: 180px 180px; ">								
							</div>	
							<h4 style="text-align: center; padding: 1%; font-weight: z-index: 20; border-radius: 10px; border: solid 1px rgba(0,0,0,.3);"><?php echo $cName ?></h4>
						</div>
						<!-- For querying more -->
						<span class="postSort" id="<?php echo $allCollections[$j]['collection_id']?>"></span>						
					</div>
	<?php		
				
			} //for
	?>
		