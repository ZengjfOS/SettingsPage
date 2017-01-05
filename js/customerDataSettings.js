
function enableCoverMask(){
    $('.bg').css({'display':'block'});
    $('.content').css({'display':'block'});
}

function disableCoverMask(){
    $('.bg').css({'display':'none'});
    $('.content').css({'display':'none'});
}

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

function updateApp(){

    ftpIPAddress = $('input[name="ftpIPAddress"]').val();
    ftpApplicationName = $('input[name="ftpApplicationName"]').val();

    enableCoverMask();

    ajaxPostData = {"categories":"customer", "type": "updateApp", "ftpIPAddress": ftpIPAddress, "appName": ftpApplicationName};

    console.info(ajaxPostData);

    $.ajax({
        url: "customerDataSettings.php",
        type: 'POST',
        contentType:'application/json; charset=utf-8',
        data: JSON.stringify(ajaxPostData),
        dataType:'json',
        success: function(data){
            //On ajax success do this
            disableCoverMask();
            if (data["status"] == "ok"){
                alert("Update Application is OK.");
            } else {
                alert("Update Application is Faile.");
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            //On error do this
            console.info("error.");
            disableCoverMask();
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
