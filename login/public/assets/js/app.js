$(document).ready(function() {

	Messenger.options = {
	    extraClasses: 'messenger-fixed messenger-on-top',
	    theme: 'flat'
	}

	// Handeling selecting the text inside a modal and releasing outside the modal
	thisTarget = null;
	$(document).on('mousedown', 'input, textarea', function(e){ // capture the start of selecting text
		thisTarget = $(this); // set the current element
	});
	$(document).on('mouseup', function(e) { // capture the release of selecting
		if(thisTarget !== null) { // check if the select started inside a input element
			if (thisTarget.attr('id') !== $(e.target).attr('id')) {  // check if the start and release element are not the same
				setTimeout(function() { 
					thisTarget.focus(); // set the focus back on the prefered element
					thisTarget = null; // forget the curent element
				}, 5);
				
			}
		}
	});
	// end text select handling
	

		// focus on the first found input
		$('div.header input').focus();
		$('input').attr("autocomplete", "new-password");

		// $('.selectAll').each(function() {
		// 	$(this).closest('tr').each(function() {
		// 		$(this).css({
		// 			'border-left': '10px solid #ffffff'
		// 		});
		// 	}).find('th').each(function() {
		// 		$(this).css({
		// 			'padding': '10px 12px'
		// 		});
		// 	});
		// });
		// $('.selectAll').on('click',function() {
		// 	$(this).closest('table').find('tr td').each(function(e) {
		// 		$(this).trigger('click');
		// 	});
		// });

		$('.show-mobile a.search').on('click',function() {
			$('.header').toggleClass('down');
			$('.page-container').toggleClass('down');
			$('.mobile-search').toggle();
		});


		//$('#left-panel').addClass('animated bounceInRight');
		$('#project-progress').css('width', '50%');
		$('#msgs-badge').addClass('animated bounceIn');

		$('#my-task-list').popover({
			html:true
		})

		// $('#text-editor').wysihtml5();

		$('a').tooltip();
		$("select:not(.leave)").select2();
		$('.my-colorpicker-control').colorpicker({
			format: 'hex'
		}).on('changeColor', function(ev){
			$('.my-colorpicker-control').attr('style','background:'+ ev.color.toHex() +';');
			$('#colorpickerdata').val(ev.color.toHex());
			$('.my-colorpicker-control .arrow').attr('style','color:'+ ev.color.toHex() +';');
		});


		var curURL = $('curURL').attr('data');


		$('.grid-body').on('click','table.fileTable > tbody > tr',function(e) {
			clearTimeout(reloadDatatableTimer); // stop reloading the datatable
			if (e.target.nodeName == 'A' && e.target.textContent != '') { return; }

			if ($(this).find('input[type=checkbox]:first').is(":checked")) {
				$(this).find('input[type=checkbox]:first').prop('checked', false);
				$(this).removeClass('checkedRow');
			}else{
				$(this).find('input[type=checkbox]:first').prop('checked', true);
				$(this).addClass('checkedRow');
			}

			checkCount = $('table.fileTable').find('input[type=checkbox]:checked').length;
			$('.countSelected span').html(checkCount); // Show number of files selected to the user
			if (checkCount == 0) {
				$('.toolButtons').css('bottom', '-120px');
				reloadDatatable();
			}else {
				$('.toolButtons').css('bottom', '0px');
			}
		});

		$(".iWachtwoord").click(function() {
			var theser = $(this);
			theser.prop("type","text");
			theser.attr("disabled","DISABLED");
			theser.select();
			setTimeout(function(e) {
				theser.prop("type","password");
				theser.removeAttr("disabled");
				theser.blur();
			},5000);
		});

		$('.parentf').click(function() {
			var attr = $(this).attr('checked');
			var pid = $(this).attr('id');
			if (typeof attr == 'undefined' && attr == false) {
			}else{
				$(this).parent().parent().find('.subf').each(function() {
					var sattr = $(this).attr('checked');
					if($(this).attr('parent') == pid) {
						$(this).removeAttr('checked');
					}
				});
			}
		});

		$('.subf').click(function() {
			var attr = $(this).attr('checked');
			var pid = $(this).attr('parent');
			if (typeof attr == 'undefined' && attr == false) {

			}else{
				$(this).parent().parent().find('.parentf').each(function() {
					var pattr = $(this).attr('checked');
					if($(this).attr('id') == pid) {
						$(this).attr('checked','checked');
					}
				});
			}
		});

		/*
			Start reloading the datatables every 30 seconds
		*/
		var reloadDatatableTimer; 
		function reloadDatatable() {
		    reloadDatatableTimer = setTimeout(function () {
		        fileTable.ajax.reload(null,false);
		        geboektTable.ajax.reload(null,true)
		        reloadDatatable();
		    }, 10000);
		}
		reloadDatatable();

		/*
			AJAX Search bar handling
		*/
		var typingTimer;
		var doneTypingInterval = 450;
		var searchval = $('.header input[name=search]').val();
		$('.header input[name=search], .mobile-search input[name=search]').keyup(function(){
			searchval = $(this).val();
		    clearTimeout(typingTimer);
		    $(this).parent().attr('action','/user/search/'+$(this).val()+'/')
		    typingTimer = setTimeout(doneTyping, doneTypingInterval);
		}).keydown(function(){
		    clearTimeout(typingTimer);
		});
			function doneTyping () {
			    if (searchval.length > 2) {
			    	$('.page-title h3:first').html('Zoeken naar: '+searchval);
			    	fileTable.ajax.url("/user/search/"+searchval+"/ajax/").load();
			    	window.history.pushState("Digitar zoeken:"+searchval, "Digitar zoeken", "/user/search/"+searchval);
				/*}else {
					$('.page-title h3').html('Onverwerkt');
			    	fileTable.ajax.url("/user/folder/inbox/ajax").load();
			    	window.history.pushState("Digitar archief", "Digitar archief", "/user/folder/inbox");*/
				}
				disableBulkButtons();
			}

		//  END AJAX SEARCHBAR HANDLING

		/*
			FILE MODAL HANDLING
		*/
		var currentModalFileButton;
		$("body").on('click','a.file-modal', function(e){
  			e.preventDefault();
  			clearTimeout(reloadDatatableTimer);
  			$('#myModal').modal('show').find('.modal-body').load($(this).attr('href'));
  			$('#myModal').modal('show').find('.modal-body').height(100+'%');

  			currentModalFileButton = $(this);
		});
		$('#myModal').modal({
            backdrop: 'static',
            keyboard: true // enable esc key
        }).modal('hide');
		$('#myModal').on('hidden.bs.modal', function () {
			reloadDatatable();
		});

		$(document).on('keyup',function(e) {
			if ($('.modal.in').length > 0 && e.target.nodeName != 'INPUT') {
				e.stopPropagation();
				e.preventDefault();
				// console.log(e.which);
				if(e.which == 39 || e.which == 40) { // next
					var next = currentModalFileButton.closest('tr').next().find('.file-modal');
					if(next.length < 1) {
						next = currentModalFileButton.closest('table').find('.file-modal:first');
					}
					next.trigger('click');
				}
				if(e.which == 37 || e.which == 38) { // previous
					var previous = currentModalFileButton.closest('tr').prev().find('.file-modal');
					if(previous.length < 1) {
						previous = currentModalFileButton.closest('tr').parent().last().find('.file-modal');
					}
					previous.trigger('click');
				}
			}
		});

		$("body").on('submit','.viewfileform', function(e){
			e.stopPropagation();
			e.preventDefault();
			var formData = $(this).serialize();
			var formPostUrl = $(this).attr('action');
			$.ajax({
			    type:'POST',
			    data:formData,
			    url:formPostUrl,
			    success:function(data) {
			      	fileTable.ajax.reload(null,false);
			      	geboektTable.ajax.reload(null,false);
			    }
			});
			$('#myModal').modal('hide');
			
			Messenger().post('Opgeslagen');
			$('.toolButtons').css('bottom', '-120px');
		});
		/*
			END FILE MODAL HANDLING
		*/

		// Bulk file handling buttuns
		function disableBulkButtons() {
			$('.only-inbox').toggle(location.pathname == '/user/folder/inbox');
		}
		disableBulkButtons();

		//do something when navigated to a new page
		window.onpopstate = history.onpushstate = function(e) {
			disableBulkButtons();
	    };
		


		$("#sort").sortable({
			placeholder: "ui-state-highlight",
	        helper: function(e, ui) {
	             ui.children().children().each(function() {
	                 $(this).width($(this).width());
	             });
	             return ui;
	        },
	        items: "tbody",
	        axis: "y",
	        update: function(event, ui) {
		        //console.log($('#sort').sortable('serialize'));
				$.ajax({
				  	type: "POST",
				  	url: "/organization/folder/sort",
				  	data: $('#sort').sortable('serialize')
				}).done(function(response) {
					console.log(response);
				});
		    },
		    start: function(e, ui ){
			     ui.placeholder.height(ui.helper.outerHeight());
			},
		    stop: function() {
			  	$('.subitem').show();
			},
			cancel: '.subitem'
	    }).disableSelection();

		$('.fileUpload').on('change','input',function() {
			var fileName = [];
			fileName = $(this).val().split('\\');
			fileName = fileName[(fileName.length - 1)];

			$('.fileName').html(fileName);
		});

		$('.imagehover').each(function() {
			var $imgBuild = '<div style="position:absolute;top:20px;left:10px;z-index:9999;padding:5px;background:#1B1E24;display:none;">';
			$imgBuild += '<img src="'+ $(this).attr('data-img') +'" style="max-width:200px;cursor:default;" />';
			$imgBuild += '</div>';
			$(this).attr('style','position:relative;');
			$(this).prepend($imgBuild);
		});
			$('.imagehover').hover(function(e) {
				 if (e.target === this) {
					$(this).find('div').fadeIn();
				}
			},function() {
				$(this).find('div').fadeOut();
			});

	$('.gennewuserpassword').on('click',function(e) {
		e.preventDefault();
		var newPass, thisser = $(this);
		$.get( "/api/genpass", function( newPass ) {
			thisser.parent().parent().parent().parent().find('input').val(newPass);
		});

	});

	$('.popup').on('click',function(e) {
		e.preventDefault();
		newwindow = window.open($(this).attr('href'), "_blank", "toolbar=no,scrollbars=yes,resizable=yes,top=500,left=500,width=400,height=400");
		if (window.focus) {newwindow.focus()}
	    return false;

	});

	/*
		START DROPZONE 
	*/
	if ($('.fa-cloud-upload').length > 0) { // check if the dropzone clickable is available
		var dropTarget = $('.dropzone-previews'),
	    html = $('body'),
	    showDrag = false,
	    timeout = -1;
	    var upload = false;

	    var previewNode = document.querySelector("#onyx-dropzone-template");
		previewNode.id = "";
		var previewTemplate = previewNode.parentNode.innerHTML;
		previewNode.parentNode.removeChild(previewNode);

		$('.fa-cloud-upload').on('click', function(e) {
			e.preventDefault();
			return false;
		});
		
		var myDropzone = new Dropzone(document.body, {
			url: "/user/upload/post",
			previewsContainer: ".dropzone-previews #previews",
			previewTemplate: previewTemplate,
			clickable: ".fa-cloud-upload"
		});
		myDropzone.on('sending', function(file, xhr, formData){
			formData.append('fid', $('#foldertitle').attr('fid'));
		});
		myDropzone.on("drop", function() {
		  showDrag = true; 
		  upload = true; 
		});
		myDropzone.on("complete", function(file) {
		  	// timeout = setTimeout( function(){
		        // dropTarget.fadeOut(400);
		        setTimeout(function() { myDropzone.removeFile(file); },500);
		   	// }, 1000 );
		   	// fileTable.ajax.reload(null,false);
		});
		myDropzone.on("queuecomplete", function(file) {
		  	timeout = setTimeout( function(){
		        dropTarget.fadeOut(400);
		        setTimeout(function() { myDropzone.removeFile(file); },500);
		   	}, 1000 );
		   	fileTable.ajax.reload(null,false);
			disableBulkButtons();
		});
		myDropzone.on("addedfile", function(file) { 
			dropTarget.fadeIn();
			$('.preview-container').css('visibility', 'visible');
			file.previewElement.classList.add('type-' + fileType(file.name)); // Add type class for this element's preview
		});
		function fileType(fileName) {
			var fileType = (/[.]/.exec(fileName)) ? /[^.]+$/.exec(fileName) : undefined;
			return fileType[0];
		}

		myDropzone.on("totaluploadprogress", function (progress) {

			var progr = document.querySelector(".progress .determinate");

			if (progr === undefined || progr === null) return;

			progr.style.width = progress + "%";
		});
		html.bind('dragenter', function () {
		    dropTarget.fadeIn();
		    showDrag = true; 
		});
		html.bind('dragover', function(){
		    showDrag = true; 
		});
		html.bind('dragleave', function (e) {
		    if( !upload ){ showDrag = false;  } 
		    clearTimeout( timeout );
		    timeout = setTimeout( function(){
		        if( !showDrag ){ dropTarget.fadeOut(); }
		    }, 200 );
		});
	}



});