function checkIP(ip) 
{ 
    obj=ip; 
    var exp=/^(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])$/; 
    var reg = obj.match(exp); 
    if(reg==null) { 
        return false;//不合法 
    } else { 
        return true; //合法 
    } 
} 

function checkMask(mask) 
{ 
    obj=mask; 
    var exp=/^(254|252|248|240|224|192|128|0)\.0\.0\.0|255\.(254|252|248|240|224|192|128|0)\.0\.0|255\.255\.(254|252|248|240|224|192|128|0)\.0|255\.255\.255\.(254|252|248|240|224|192|128|0)$/; 
    var reg = obj.match(exp); 
    if(reg==null) { 
        return false; //"非法" 
    } else { 
        return true; //"合法" 
    } 
} 

function setNetworkConfigure()
{
    var value = $('input[type="radio"][name="IPSettings"]:checked').val();
    console.info(value);

    var ajaxPostData = {};
    if (value == "DHCP"){
        console.info("execute DHCP.");
        ajaxPostData = {"categories":"network", "type": "dhcp"};
        console.info(ajaxPostData);
    } else {
        ip = $('input[name="ip"]').val();
        netmask = $('input[name="netmask"]').val();
        broadcast = $('input[name="broadcast"]').val();
        gateway = $('input[name="gateway"]').val();
        ajaxPostData = {"categories":"network", "type": "staticIP", "ip":ip, "netmask":netmask, "broadcast":broadcast, "gateway":gateway}
        console.info(ajaxPostData);

        if (checkIP(ip) == false) {
            alert("Please Check Your IP format.")
            return;
        }

        if (checkIP(netmask) == false) {
            alert("Please Check Your network format.")
            return;
        }

        if (checkIP(broadcast) == false) {
            alert("Please Check Your broadcast format.")
            return;
        }

        if (checkIP(gateway) == false) {
            alert("Please Check Your gateway format.")
            return;
        }
    }


    $.ajax({
        url: "settings.php",
        type: 'POST',
        contentType:'application/json; charset=utf-8',
        data: JSON.stringify(ajaxPostData),
        dataType:'json',
        success: function(data){
            //On ajax success do this
            console.info("success.");
            if (data["status"] == "ok"){
                alert("Settings is Ok. The Machine is rebooting.");
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

$("#pickdate").dateDropper({
	animate: false,
	format: 'Y-m-d',
	maxYear: '2020'
});
$("#picktime").timeDropper({
	meridians: false,
	format: 'HH:mm',
});

function setDataAndTime()
{
    date = $("#pickdate").val();
    time = $("#picktime").val();
    
    ajaxPostData = {"categories":"dateAndTime", "type": "dateAndTime", "date":date, "time":time};
    console.info(ajaxPostData);

    $.ajax({
        url: "settings.php",
        type: 'POST',
        contentType:'application/json; charset=utf-8',
        data: JSON.stringify(ajaxPostData),
        dataType:'json',
        success: function(data){
            //On ajax success do this
            console.info("success.");
            if (data["status"] == "ok"){
                alert("Data and Time Set is Ok.");
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

function dhcpRadioClick()
{
    $('input[type="radio"][name="IPSettings"]').filter('[value=DHCP]').prop('checked', true);
    $("#staticSettingsAglinDiv").hide();
    
}

function staticIPRadioClick()
{
    $('input[type="radio"][name="IPSettings"]').filter('[value=StaticIP]').prop('checked', true);
    $("#staticSettingsAglinDiv").show();
}

$(function(){  
    var value = $('input[type="radio"][name="IPSettings"]:checked').val();

    if (value == "DHCP"){
        $("#staticSettingsAglinDiv").hide();

        $('input[name="ip"]').val("");
        $('input[name="netmask"]').val("");
        $('input[name="broadcast"]').val("");
        $('input[name="gateway"]').val("");
    } else {
        $("#staticSettingsAglinDiv").show();
    }
}); 
