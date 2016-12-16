function customerDate() 
{ 
    remoteIP = $('input[name="remoteIP"]').val();

    ajaxPostData = {"categories":"customer", "type": "configure", "remoteIP": remoteIP};

    console.info(ajaxPostData);

    $.ajax({
        url: "customerDataSettings.php",
        type: 'POST',
        contentType:'application/json; charset=utf-8',
        data: JSON.stringify(ajaxPostData),
        dataType:'json',
        success: function(data){
            //On ajax success do this
            console.info("success.");
            console.info(data);
            console.info("zengjf");
            if (data["status"] == "ok"){
                alert("Set remote IP is OK.");
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            //On error do this
            console.info("error.");
            if (xhr.status == 200) {
    
                alert(ajaxOptions);
            }
            else {
                alert(xhr.status);
                alert(thrownError);
            }
        }
    });
}
