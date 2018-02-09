<!-- hide script from old browsers
jQuery= jQuery.noConflict();

	function setandOpenRenameModal (cid){
		jQuery('.modal').modal('hide');
		createModal('rename', cid);
		jQuery('#myRenameModal').modal('show');
	}
	
	function setandOpenImageModal(cid) {
		jQuery('.modal').modal('hide');
		createModal('image', cid);
		jQuery('#myPictureModal').modal('show');
	}
			
	function openCollectionModal(cid) {
		jQuery('.modal').modal('hide');
		createModal('collection', cid);
	}
	
	function openCommentModal (post_id) {
		jQuery('.modal').modal('hide');
		createModal('comment', post_id);
		jQuery('#myCommentModal').modal('show');
	}
	
	function createModal(modalType, id) 
	{		
		switch (modalType) 
		{
			case 'collection': $.get('modalCollection.php', {cid: id}, function (resp) { $('.modalFiller').html(resp);	});	
				break;
			case 'rename': $.get('modalCollectionRename.php', {cid: id}, function (resp) { $('.modalFiller').html(resp);	});	
				break;
			case 'image': $.get('modalCollectionImage.php', {cid: id}, function (resp) { $('.modalFiller').html(resp);	});	
				break;
			case 'comment': $.get('modalComment.php', {pid: id}, function (resp) { $('.modalFiller').html(resp);	});	
				break;
		}
	}
// end hiding script from old browsers -->