<?php  
	$cid = $_GET['cid'];	
?>

<!--Collection Image Modal-->
<div class="modal fade" id="myPictureModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">	
				<div style="text-align: center;">
					<h3>New collection image: </h3>				
				</div>							
				<div style = "text-align: center; ">
					   <form action = "updateCollectionImage.php" method = "POST" enctype = "multipart/form-data">	
						<label class="control-label"></label>
						<input type = "file" class="file" name = "collectionbackground" />
						<input type="hidden" name="collection_id" class="collection_id" value="<?php echo $cid ?>"/>
						<br/>
						 <img src="images/loading.gif" id="gif" style="margin: 0 auto; display:none; visibility: hidden;">
						<input type="submit" name="submit" class = "btn btn-large" id="submit" value="Submit" style = "background-color: #75C0C0; width: 100%;"></p>									 
					  </form>
				</div>		
			</div>
		</div>
	</div>
</div>
