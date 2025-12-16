$(document).ready(function() {

      if ($("#code").length) {
         var editor = CodeMirror.fromTextArea(document.getElementById("code"), {
            lineNumbers: true,
            styleActiveLine: true,
            matchBrackets: true,
            theme: "neo"
         });
      }
      if ($("#code2").length) {
         var editor = CodeMirror.fromTextArea(document.getElementById("code2"), {
            lineNumbers: true,
            styleActiveLine: true,
            matchBrackets: true,
            theme: "neo"
         });
      }

   	$('#selectDebtor').change(function() {
   		$.get('/billing/getdebtor/'+$(this).val(), function( html ) {
   			$('#debtorInfo').html(html);
            $("button[type=submit]").removeAttr('disabled');
   		});
   	});
         if ($('#selectDebtor').val() > 0) {
            $.get('/billing/getdebtor/'+$('#selectDebtor').val(), function( html ) {
               $('#debtorInfo').html(html);
               $("button[type=submit]").removeAttr('disabled');
            });
         }

      $('input[name=invoicenumber]').on('change',function() {
         $.get('/billing/checkinvoicenumber/'+$(this).val(), function( html ) {
            if(html == 0) {
               $("button[type=submit]").removeAttr('disabled');
            }else{
               $("button[type=submit]").attr('disabled','true');
            }
         });
      });

      $('.auto').autoNumeric('init');


   	var rowCount = 0;
   	var newRowSave = $('#invoicerow-copy').html();
   	$('.addnewrow').on('click',function(e) {
   		e.preventDefault();
   		
        if (checkRowsLimit() === false) { return; }
        
   		$('#invoicerows').append(newRowSave);
   		rowCount++;
   		initNewBilling();

   		return false;
   	});

      var newTextRowSave = $('#textrow-copy').html();
      $('.addnewtextrow').on('click',function(e) {
         e.preventDefault();
         
         if (checkRowsLimit() === false) { return; }

         $('#invoicerows').append(newTextRowSave);
         rowCount++;
         initNewBilling();
         $('#invoicerows .form-row').last().find('input').focus();

         return false;
      });

   	$('#invoicerows').on('keyup','input',function(event) {
   		if ($(this).hasClass('amount') || $(this).hasClass('price')) {
	   		var amount = $(this).closest('.form-row').find('.amount').val();
	   		var price = $(this).closest('.form-row').find('.price').val().replace('.','').replace(',','.').replace('€ ','');
	   		var total = formatNumber(Number(amount) * Number(price));

	   		$(this).closest('.form-row').find('.total').autoNumeric('set', total );
            // $(this).setCursorToTextEnd();

            if(event.which == 9 || event.which == 16 || event.which == 36 || event.which == 17) {
               $(this).select();
            }
	   	}
   	});
      $('#invoicerows').on('focus','input',function(event) {
         // $(this).val(($this).val());
      });

      $('#invoicerows').on('keydown','.price , .textinput',function(event) {
         if (event.which == 9) {
            event.preventDefault();
            $('.addnewrow').trigger('click');
         }
      });

      $('#invoicerows').on('click','.delete',function() {
         $(this).closest('.form-row').remove();
         // $('#invoicerows .form-row').last().find('input').last().focus();
         // if (rowCount < 1) { $('.addnewrow').trigger('click'); }
         rowCount--;
         rowNum--;
         checkRowsLimit(false);
      });

      $( "#invoicerows" ).sortable({
         connectWith: "#invoicerows",
         handle: ".handle",
         axis: "y",
         placeholder: "invoicerow-placeholder row form-row ui-corner-all"
       });
      $( "#invoicerows .form-row" ).disableSelection();




      var rowNum = 0;
      var addNum = 0;
   	function initNewBilling() {
   		$('.date').datepicker({
	  		format: "dd-mm-yyyy",
			autoclose: true,
			todayHighlight: true
	   	});

	   	$('#invoicerows .form-row').each(function() {
            $(this).find('select').select2("destroy").select2();
            $(this).find('input, select').each(function() {
   	   		name = $(this).attr('name');
   	   		if (name.indexOf('[') === -1) {
   	   			name = name +'['+ rowNum +']';
   	   			$(this).attr('name',name);
                  addNum = 1;
   	   		}else{
                  addNum = 0;
               }
            });

            if (addNum == 1) {
               rowNum++;
            }

	   	});
         $('.auto').autoNumeric('init');
         // $('#invoicerows .form-row').last().find('select').select2('open');
         $('#invoicerows .form-row').last().find('.product-select input').focus();
   	}
   	initNewBilling();
   	
   	
   	function checkRowsLimit(dispMsg = true) {
   	    if (rowNum > 99) {
   	        
   	        $('.addnewinvoicerows').hide();
   	        
   	        if (dispMsg === true) {
                Messenger.options = { extraClasses: 'messenger-fixed messenger-on-top', theme: 'flat' }
                Messenger().post({
                   message: 'Het maximum aantal regels is bereikt!', type: 'error', showCloseButton: true
                });
   	        }
            
            
            return false;
        }else { $('.addnewinvoicerows').show(); }
   	}
   	checkRowsLimit(false);
   	

      function checkRows() {
         var disableSave = false;
         $('#invoicerows .form-row').each(function() {
            if ($(this).find('.product-select option[selected]').val() == 0) {
               disableSave = true;
            }
            if ($(this).find('.price').val().length < 2) {
               disableSave = true;
            }
         });

         if (disableSave == false) {
            if ($('#selectDebtor').val() != 0) {
               return true;
            }
         }else{
            return false;
         }
      }

      $('form.invoice').on('change','select.product-select',function() {

         $('select[name="'+$(this).attr('name')+'"] option[selected]').removeAttr('selected');
         $('select[name="'+$(this).attr('name')+'"]').find('option[value='+$(this).val()+']').attr('selected','true');

         if ($(this).val() != 0) {
            var rowAmount = $(this).closest('.form-row').find('.amount');
            var rowDescription = $(this).closest('.form-row').find('.description');
            var rowTax = $(this).closest('.form-row').find('.tax');
            var rowPrice = $(this).closest('.form-row').find('.price');

            $.get('/billing/getproduct/'+$(this).val(), function( json ) {
               var data = JSON.parse(json);
               rowDescription.val( data['description'] );
               rowTax.val( data['tax'] );
               rowPrice.autoNumeric('set', formatNumber(Number(data['price'])) ).trigger('keyup');
               rowAmount.focus().select();
            });
         }
      });

      $("form.invoice").on('submit',function(event) {
         if (checkRows() == false) {
            event.preventDefault();
            Messenger.options = { extraClasses: 'messenger-fixed messenger-on-top', theme: 'flat' }
            Messenger().post({
               message: 'De factuur regels zijn niet goed gevuld!', type: 'error', showCloseButton: true
            });
            return false;
         }else {
            return true;
         }
      });

   	$('.simple .grid-body').each(function() {
   		if ($(this).html().length < 20) {
	   		$(this).parent().remove();
	   	}
   	});


      $keypress = 0;
      $(document).on('keypress',':not(input)',function(event) {
         if (event.which == 43 && $keypress == 0) {
            $('.addnewrow').trigger('click');
            $keypress++;
         }
      })


});

function formatNumber(number) {
    var number = number.toFixed(2) + '';
    var x = number.split('.');
    var x1 = x[0];
    var x2 = x.length > 1 ? ',' + x[1] : '';

    return x1 + x2;
}

(function($){
    $.fn.setCursorToTextEnd = function() {
        var $initialVal = this.val();
        this.val($initialVal);
    };
})(jQuery);