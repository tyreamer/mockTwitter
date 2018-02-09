<?php  $cid = $_GET['cid']; ?>

<div class="modal fade" id="collectionModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-body">	
										<div class= "container" style="max-width: 100%; text-align: center;">
											<div class="modalItem">		
												<div id="cModalRename"><div class="glyphicon glyphicon-tag" onclick="setandOpenRenameModal(<?php echo $cid ?>)"> </div></div>
											</div>
											<div class="modalItem">		
												<div id="cModalImage"><div class="glyphicon glyphicon-camera" onclick="setandOpenImageModal(<?php echo $cid ?>)"></div></div>
											</div>
											<div class="modalItem">		
												<div id="cModalDelete"><div class="glyphicon glyphicon-trash" onclick="removeCollection(<?php echo $cid ?>)"> </div></div>
											</div>											
										</div>										
									</div>
								</div>
							</div>
						</div>	
						