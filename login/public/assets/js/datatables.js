/* Set the defaults for DataTables initialisation */
$.extend( true, $.fn.dataTable.defaults, {
	"sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span12'p i>>",
	"sPaginationType": "bootstrap",
	"oLanguage": {
		"sLengthMenu": "_MENU_",
		"sZeroRecords": "Geen gegevens gevonden",
		"sEmptyTable": "Geen gegevens beschikbaar",
		"sSearch": "Zoeken:",
		"sInfoEmpty": "",
		"sInfoFiltered": "(gefilterd uit _MAX_)",
		"sInfo": "_START_ t/m _END_ van _TOTAL_",
	}
} );


/* Default class modification */
$.extend( $.fn.dataTableExt.oStdClasses, {
	"sWrapper": "dataTables_wrapper form-inline"
} );


/* API method to get paging information */
$.fn.dataTableExt.oApi.fnPagingInfo = function ( oSettings ) 
{
	return {
		"iStart":         oSettings._iDisplayStart,
		"iEnd":           oSettings.fnDisplayEnd(),
		"iLength":        oSettings._iDisplayLength,
		"iTotal":         oSettings.fnRecordsTotal(),
		"iFilteredTotal": oSettings.fnRecordsDisplay(),
		"iPage":          oSettings._iDisplayLength === -1 ?
			0 : Math.ceil( oSettings._iDisplayStart / oSettings._iDisplayLength ),
		"iTotalPages":    oSettings._iDisplayLength === -1 ?
			0 : Math.ceil( oSettings.fnRecordsDisplay() / oSettings._iDisplayLength )
	};
};
$.fn.dataTable.ext.errMode = 'throw';


/* Bootstrap style pagination control */
$.extend( $.fn.dataTableExt.oPagination, {
	"bootstrap": {
		"fnInit": function( oSettings, nPaging, fnDraw ) {
			var oLang = oSettings.oLanguage.oPaginate;
			var fnClickHandler = function ( e ) {
				e.preventDefault();
				if ( oSettings.oApi._fnPageChange(oSettings, e.data.action) ) {
					fnDraw( oSettings );
				}
			};

			$(nPaging).addClass('pagination').append(
				'<ul>'+
					'<li class="prev disabled"><a href="#"><i class="fa fa-chevron-left"></i></a></li>'+
					'<li class="next disabled"><a href="#"><i class="fa fa-chevron-right"></i></a></li>'+
				'</ul>'
			);
			var els = $('a', nPaging);
			$(els[0]).bind( 'click.DT', { action: "previous" }, fnClickHandler );
			$(els[1]).bind( 'click.DT', { action: "next" }, fnClickHandler );
		},

		"fnUpdate": function ( oSettings, fnDraw ) {
			var iListLength = 5;
			var oPaging = oSettings.oInstance.fnPagingInfo();
			var an = oSettings.aanFeatures.p;
			var i, ien, j, sClass, iStart, iEnd, iHalf=Math.floor(iListLength/2);

			if ( oPaging.iTotalPages < iListLength) {
				iStart = 1;
				iEnd = oPaging.iTotalPages;
			}
			else if ( oPaging.iPage <= iHalf ) {
				iStart = 1;
				iEnd = iListLength;
			} else if ( oPaging.iPage >= (oPaging.iTotalPages-iHalf) ) {
				iStart = oPaging.iTotalPages - iListLength + 1;
				iEnd = oPaging.iTotalPages;
			} else {
				iStart = oPaging.iPage - iHalf + 1;
				iEnd = iStart + iListLength - 1;
			}

			for ( i=0, ien=an.length ; i<ien ; i++ ) {
				// Remove the middle elements
				$('li:gt(0)', an[i]).filter(':not(:last)').remove();

				// Add the new list items and their event handlers
				for ( j=iStart ; j<=iEnd ; j++ ) {
					sClass = (j==oPaging.iPage+1) ? 'class="active"' : '';
					$('<li '+sClass+'><a href="#">'+j+'</a></li>')
						.insertBefore( $('li:last', an[i])[0] )
						.bind('click', function (e) {
							e.preventDefault();
							oSettings._iDisplayStart = (parseInt($('a', this).text(),10)-1) * oPaging.iLength;
							fnDraw( oSettings );
						} );
				}

				// Add / remove disabled classes from the static elements
				if ( oPaging.iPage === 0 ) {
					$('li:first', an[i]).addClass('disabled');
				} else {
					$('li:first', an[i]).removeClass('disabled');
				}

				if ( oPaging.iPage === oPaging.iTotalPages-1 || oPaging.iTotalPages === 0 ) {
					$('li:last', an[i]).addClass('disabled');
				} else {
					$('li:last', an[i]).removeClass('disabled');
				}
			}
		}
	}
} );


/*
 * TableTools Bootstrap compatibility
 * Required TableTools 2.1+
 */

	// Set the classes that TableTools uses to something suitable for Bootstrap
	$.extend( true, $.fn.DataTable.TableTools.classes, {
		"container": "DTTT ",
		"buttons": {
			"normal": "btn btn-white",
			"disabled": "disabled"
		},
		"collection": {
			"container": "DTTT_dropdown dropdown-menu",
			"buttons": {
				"normal": "",
				"disabled": "disabled"
			}
		},
		"print": {
			"info": "DTTT_print_info modal"
		},
		"select": {
			"row": "active"
		}
	} );

	// Have the collection use a bootstrap compatible dropdown
	$.extend( true, $.fn.DataTable.TableTools.DEFAULTS.oTags, {
		"collection": {
			"container": "ul",
			"button": "li",
			"liner": "a"
		}
	} );

var fileTable;

/* Table initialisation */
$(document).ready(function() {
    var responsiveHelper = undefined;
    var breakpointDefinition = {
        tablet: 1024,
        phone : 480
    };    

	/*
		USER FILE TABLE
	*/
	fileTable = $('#fileTable').DataTable({
		//specify AJAX source, params and response source
		ajax: {
			url: '/'+$('#fileTable').attr('url')+'/ajax',
			dataSrc: 'data'
		},
		// "order": [[ 3, "desc" ]],
		"paging": true,
		"pageLength": 25,
		"info": false,
		"deferRender": true,
		//set up table columns
		columns: [
			{title: '', data: ''},
			{title: 'Document', data: 'name', className: "v-align-middle"},
			{title: 'Door', data: 'username', className: "hidden-phone hide-phone v-align-middle"},
			{title: 'Datum', data: 'nicedate', className: "v-align-middle"}
		],
		//change column data
		"columnDefs": [
			{		
                "render": function ( data, type, row ) {
                    return '<input type="checkbox" value="'+ row.name +'" name="fileid['+ row.id +']" style="display:none;"> \
                    <a class="btn btn-info btn-mini file-modal" href="/user/viewfile/'+ row.id +'"> Bekijk </a>'
                },
                "targets": 0
            },{
                "render": function ( data, type, row ) {
                    return '<span style="display:none;">'+ row.date +'</span>'+ data
                },
                "targets": 3
            }
        ],
        //add styling to induvidual rows based on data
        "createdRow": function( row, data, dataIndex ) {
        	if ( data.rights != 1) { window.location.replace("https://login.digitar.nu"); }
			if ( data.geboekt !== 1 && data.fid !== 0 && data.highrank != false && data.bookedcheck == 1 ) {
		      	$(row).addClass( 'red checkableRow' );
			}else {
				$(row).addClass( 'checkableRow' );
			}
			$(row).addClass('tip').attr('data-placement',"top").attr('title',"Klik om te selecteren");
		 }
	});
		fileTable.on( 'xhr', function ( e, settings, json ) {
			if (json) {
				if (json.data) {
					if (json.data.length > 0) {
						if (json.data[0].rights != 1) { window.location.replace("https://login.digitar.nu"); }
					}
				}else {
					window.location.replace("https://login.digitar.nu");
				}
			}else {
				// turned off reload on empty results temporary
				// window.location.replace("https://login.digitar.nu");
			}
		} );


	/*
		USER GEBOEKT FILE TABLE
	*/
	geboektTable = $('#geboektTable').DataTable({
		//specify AJAX source, params and response source
		ajax: {
			url: '/user/ongeboekt/ajax',
			dataSrc: 'data'
		},
		"order": [[ 3, "desc" ]],
		"paging": false,
		"info": false,
		"searching": false,
		//set up table columns
		columns: [
			{title: '', data: ''},
			{title: 'Document', data: 'name', className: "v-align-middle"},
			{title: 'Door', data: 'username', className: "hidden-phone hide-phone v-align-middle"},
			{title: 'Datum', data: 'nicedate', className: "v-align-middle"}
		],
		//change column data
		"columnDefs": [
			{		
                "render": function ( data, type, row ) {
                    return '<input type="checkbox" value="'+ row.name +'" name="fileid['+ row.id +']" style="display:none;"> \
                    <a class="btn btn-info btn-mini file-modal" href="/user/viewfile/'+ row.id +'"> Bekijk </a>'
                },
                "targets": 0
            },{
                "render": function ( data, type, row ) {
                    return '<span style="display:none;">'+ row.date +'</span>'+ data
                },
                "targets": 3
            }
        ],
        //add styling to induvidual rows based on data
        "createdRow": function( row, data, dataIndex ) {
			$(row).addClass( 'red checkableRow' );
		 }
	});		


	/*
		USER FILE SHARING TABLE
	*/
    var tableElement = $('#cloudtable');
    var sortRow = 3; var sortOrder = "desc";
    if(tableElement.is('[data-sort]')) { sortRow = parseInt(tableElement.attr('data-sort')); }
    if(tableElement.is('[data-sort-order]')) { sortOrder = tableElement.attr('data-sort-order'); }
    tableElement.dataTable( {
		//Table header options
		<!-- "sDom": "<'row-fluid'<'span6'l T><'span6'f>r>t<'row-fluid'<'span12'p i>>" -->,
			
		"sPaginationType": "bootstrap",
		"aoColumnDefs": [
          { 'bSortable': false, 'aTargets': [ 0 ] }
		],
		"oLanguage": {
			"sEmptyTable": "Geen bestanden aanwezig",
			"sInfoFiltered": "(gefilterd uit _MAX_ bestanden)",
			"sZeroRecords": "Geen bestanden gevonden"
		},
		"aaSorting": [[ sortRow, sortOrder ]],
		"aLengthMenu": [[25, 50, 100, -1], [25, 50, 100, "alles"]],
		"iDisplayLength": 25,
		 bAutoWidth     : false,
		 stateSave		: true,
        fnPreDrawCallback: function () {
            // Initialize the responsive datatables helper once.
            if (!responsiveHelper) {
                responsiveHelper = new ResponsiveDatatablesHelper(tableElement, breakpointDefinition);
            }
        },
        fnRowCallback  : function (nRow) {
            responsiveHelper.createExpandIcon(nRow);
        },
        fnDrawCallback : function (oSettings) {
            responsiveHelper.respond();
        }
	});

    /*
		ADMIN DATA TABLE (USERS ETC)
    */
	var tableElement = $('#datatable');
	var sortRow = 0; var sortOrder = "asc";
    if(tableElement.is('[data-sort]')) { sortRow = parseInt(tableElement.attr('data-sort')); }
    if(tableElement.is('[data-sort-order]')) { sortOrder = tableElement.attr('data-sort-order'); }
	tableElement.dataTable( {
		//Table header options
		<!-- "sDom": "<'row-fluid'<'span6'l T><'span6'f>r>t<'row-fluid'<'span12'p i>>" -->,
		"oLanguage": {
			"sLengthMenu": "_MENU_",
			"sZeroRecords": "Geen gegevens gevonden",
			"sEmptyTable": "Geen gegevens beschikbaar",
			"sSearch": "Zoeken:",
			"sInfoEmpty": "",
			"sInfoFiltered": "_TOTAL_ gevonden",
			"sInfo": "_START_ t/m _END_ van _TOTAL_",
		},
		"sPaginationType": "bootstrap",
		"order": [[ sortRow, sortOrder ]],
		"aLengthMenu": [[25, 50, 100, -1], [25, 50, 100, "alles"]],
		"iDisplayLength": 25,
		 bAutoWidth     : false,
		 stateSave		: true,
        fnPreDrawCallback: function () {
            // Initialize the responsive datatables helper once.
            if (!responsiveHelper) {
                responsiveHelper = new ResponsiveDatatablesHelper(tableElement, breakpointDefinition);
            }
        },
        fnRowCallback  : function (nRow) {
            responsiveHelper.createExpandIcon(nRow);
        },
        fnDrawCallback : function (oSettings) {
            responsiveHelper.respond();
        }
	});
	$('#datatable_wrapper .dataTables_filter input').addClass("input-sm "); // modify table search input
    setTimeout(function() {
    	$('table .select2-search').remove();
    },400);

    /*
		CLEAN DATA TABLE FOR REGULAR USE (no filters, no paging)
    */
	var tableElement = $('#cleartable');
	var sortRow = 0; var sortOrder = "asc";
    if(tableElement.is('[data-sort]')) { sortRow = parseInt(tableElement.attr('data-sort')); }
    if(tableElement.is('[data-sort-order]')) { sortOrder = tableElement.attr('data-sort-order'); }
    tableElement.dataTable( {
		//Table header options
		"sDom": "rt",
		"aaSorting": [[ sortRow, sortOrder ]],
		"order": [[ sortRow, sortOrder ]],
		"iDisplayLength": -1,
		 bAutoWidth     : false,
		 stateSave		: true,
        fnPreDrawCallback: function () {
            // Initialize the responsive datatables helper once.
            if (!responsiveHelper) {
                responsiveHelper = new ResponsiveDatatablesHelper(tableElement, breakpointDefinition);
            }
        },
        fnRowCallback  : function (nRow) {
            responsiveHelper.createExpandIcon(nRow);
        },
        fnDrawCallback : function (oSettings) {
            responsiveHelper.respond();
        }
	});
	
    // make the datatables search input smaller
    $('.dataTables_filter input').addClass('input-sm');
});

