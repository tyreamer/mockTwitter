
<?php $cid = $_GET['cid']; ?>
<!-- Rename modal -->
						<div class="modal fade" id="myRenameModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-body">	
										<div class= "container" style="max-width: 100%; text-align: center;">											
											<div class="modalItem">													
												<div class="input-group" style="border-right-style:none;">													
												<div class="alert alert-danger" id="text-error" style="display: none;"></div>
													<form method="POST" action="renameCollection.php" enctype = "multipart/form-data">
														<input type="text" name = "collection_name" id="renamecollection" class="form-control" placeholder="Rename collection..."/>
														<input type="hidden" name="collection_id" class="collection_id" value="<?php echo $cid ?>"/>
														<input class="form-control" type="submit" value="Enter"></input>	
													</form>														
												</div>								
											</div>
										</div>										
									</div>
								</div>
							</div>
						</div>