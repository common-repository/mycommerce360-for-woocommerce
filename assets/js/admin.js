var ajax_url = mc360Params.ajax_url;



var isadmin = mc360Params.isadmin;



$ = jQuery;







$(window).load(function(){



	var orderiddiv = $("#orderidmc360");



	if( typeof(orderiddiv)!="undefined" && orderiddiv.length>=1 && isadmin==1 && orderiddiv!=null && orderiddiv!='' )



		mc360_ajaxCall();



})


var timesRun=0;
var interval =setInterval(mc360_ajaxCall, 3000); /*300000 MS == 5 minutes*/







function mc360_ajaxCall() {

if($("#mC360_other_fields").length>0){
 timesRun += 1;
 if(timesRun === 3){
        clearInterval(interval);
    }

var orderiddiv = $("#orderidmc360");



	if( typeof(orderiddiv)!="undefined" && orderiddiv.length>=1 && isadmin==1 && orderiddiv!=null && orderiddiv!='' ){



		if (isadmin == 1) {



			var tracknumber = $("#trackingnumber").val();



			if (tracknumber.length == 1 ) {



				var orderid = $("#orderidmc360").attr("orderid");



				var data = {



					'action': 'mc360_checktracking',



					'order': orderid,



					'ajax': true,



				};



				jQuery.post(ajax_url, data, function(response) {



					if (response.length > 1 && response != "hide") {



						$("#orderidmc360").hide();



						$("#trackingnumber").val(response);



					} else if (response === "hide") {



						$("#orderidmc360").hide();
						 /* $("#mc360-message").show(); */
						 $(".mc360-messageerror").hide();



					}







				});



			} else {



				$("#orderidmc360").hide();
				







			}



		}



	}	

}

}







function mc360_sendorder(order) {



    var data = {



        'action': 'mc360_sendorder',



        'order': order,



        'ajax': true,

        'dataType': "json",



    };

    jQuery.post(ajax_url, data, function(response) {

         if (typeof(response) === "json") {

            var res = JSON.parse(response);

           }

        if (typeof(response) === "string") {

			var res=JSON.parse(response);
          }
		  
		  


        if ((typeof(res) != "undefined") && (typeof(res.error) != "undefined" && res.error != '' && res.error != null)) {

            $("#mc360-message").hide();

            $("<div class='mc360-messageerror alert-danger'>" + res.error + "</div>").insertAfter("#mc360-message");

        } else if ((typeof(res) != "undefined") && (typeof(res.warning) != "undefined" && res.warning != '' && res.warning != null)) {

            $("#mc360-message").hide();

            $("<div class='mc360-messageerror alert-danger'>" + res.warning + "</div>").insertAfter("#mc360-message");

            $("#orderidmc360").hide();

        } 

		else if ((typeof(res) != "undefined") && (typeof(res.mc360_shipId) != "undefined" && res.mc360_shipId != '' && res.mc360_shipId != null)) {

            $("#mc360_shipId").html(res.mc360_shipId);
            $("#default_collis").attr("readonly","readonly");

            $("#mc360-message").show();

            $("#orderidmc360").hide();

        } else {

            $("#orderidmc360").hide();

            $("#mc360-message").show();

        }

    });



}
function changeCollis(orderid){
	var data = {



        'action': 'mc360_changeCollis',



        'order': orderid,
        'default_collis': $("#default_collis").val(),



        'ajax': true,

        'dataType': "json",



    };

    jQuery.post(ajax_url, data, function(response) {
		if(response){
			$("#showmessage").show();
		}
	});
}
/* $(window).load(function(){
	console.log("consoled");
	var orderid = $("#orderidmc360").attr("orderid");
	changeCollis(orderid);
}); */