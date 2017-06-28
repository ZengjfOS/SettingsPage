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
            alert("Please Check Your IP Format.")
            return;
        }

        if (checkIP(netmask) == false) {
            alert("Please Check Your Network Format.")
            return;
        }

        if (checkIP(broadcast) == false) {
            alert("Please Check Your Broadcast Format.")
            return;
        }

        if (checkIP(gateway) == false) {
            alert("Please Check Your Gateway Format.")
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

function pingNetWork()
{
    netmask = $('input[name="pingNetWork"]').val();

    ajaxPostData = {"categories":"network", "type": "ping", "IPOrDNS": netmask};

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
                alert("Ping to WAN is OK.");
            } else {
                alert("Ping to WAN is ERROR.");
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

function updateUboot()
{
    ftpIPAddress = $('input[name="ftpIPAddress"]').val();
    ubootName = $('input[name="ftpUbootName"]').val();
    $('input[name="updateUboot"]').prop('disabled', true);

    enableCoverMask();

    if(!checkIP(ftpIPAddress)){
        alert("Please Check Your FTP IP Address Format.")
        return 
    }

    ajaxPostData = {"categories":"updateSystem", "type": "uboot", "ftpIP": ftpIPAddress, "ubootName": ubootName};

    $.ajax({
        url: "settings.php",
        type: 'POST',
        contentType:'application/json; charset=utf-8',
        data: JSON.stringify(ajaxPostData),
        dataType:'json',
        success: function(data){
            //On ajax success do this
            $('input[name="updateUboot"]').prop('disabled', false);
            disableCoverMask();
            console.info("success.");
            if (data["status"] == "ok"){
                alert("Update the U-Boot is OK.");
            } else {
                alert("Update the U-Boot is ERROR.");
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            //On error do this
            $('input[name="updateUboot"]').prop('disabled', false);
            disableCoverMask();
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

function enableCoverMask(){
    $('.bg').css({'display':'block'});
    $('.content').css({'display':'block'});
}

function disableCoverMask(){
    $('.bg').css({'display':'none'});
    $('.content').css({'display':'none'});
}

function updateKernel(){
    ftpIPAddress = $('input[name="ftpIPAddress"]').val();
    kernelName = $('input[name="ftpKernelName"]').val();
    $('input[name="updateKernel"]').prop('disabled', true);

    enableCoverMask();

    if(!checkIP(ftpIPAddress)){
        alert("Please Check Your FTP IP Address Format.")
        return 
    }

    ajaxPostData = {"categories":"updateSystem", "type": "kernel", "ftpIP": ftpIPAddress, "kernelName": kernelName};

    $.ajax({
        url: "settings.php",
        type: 'POST',
        contentType:'application/json; charset=utf-8',
        data: JSON.stringify(ajaxPostData),
        dataType:'json',
        success: function(data){
            //On ajax success do this
            $('input[name="updateKernel"]').prop('disabled', false);
            disableCoverMask();
            console.info("success.");
            if (data["status"] == "ok"){
                alert("Update Kernel is OK.");
            } else {
                alert("Update Kernel is ERROR.");
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            //On error do this
            $('input[name="updateKernel"]').prop('disabled', false);
            disableCoverMask();
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

function updateRootfs(){
    ftpIPAddress = $('input[name="ftpIPAddress"]').val();
    rootfsName = $('input[name="ftpRootfsName"]').val();
    $('input[name="updateRootfs"]').prop('disabled', true);

    enableCoverMask();

    if(!checkIP(ftpIPAddress)){
        alert("Please Check Your FTP IP Address Format.")
        return 
    }

    ajaxPostData = {"categories":"updateSystem", "type": "rootfs", "ftpIP": ftpIPAddress, "rootfsName": rootfsName};

    $.ajax({
        url: "settings.php",
        type: 'POST',
        contentType:'application/json; charset=utf-8',
        data: JSON.stringify(ajaxPostData),
        dataType:'json',
        success: function(data){
            //On ajax success do this
            $('input[name="updateRootfs"]').prop('disabled', false);
            disableCoverMask();
            console.info("success.");
            if (data["status"] == "ok"){
                alert("Updata Rootfs is OK. The system will update and reboot in time.");
            } else {
                alert("Upload Rootfs is ERROR.");
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            //On error do this
            console.info("error.");
            $('input[name="updateRootfs"]').prop('disabled', false);
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

var ip_addr = document.location.hostname;
window.WebSocket = window.WebSocket || window.MozWebSocket;
var websocket = new WebSocket('ws://' + ip_addr +':9000',
                              'dumb-increment-protocol');
function UARTOpen(){

    UARTPorts = $("#UARTPorts option:selected").val(); 
    UARTBaudRate = $("#UARTBaudRate option:selected").val(); 
    UARTStopBit = $("#UARTStopBit option:selected").val(); 
    UARTDataLen = $("#UARTDataLen option:selected").val(); 
    UARTCheckBit = $("#UARTCheckBit option:selected").val(); 
    UARTIntervalSendData = $('input[name="UARTIntervalSendData"]').val();
    UARTIntervalSendData = $('input[name="UARTIntervalSendData"]').val();
    UARTSendData = $("#UARTSendData").val();

    webSocketData = 
    {
        "categories":"uart", 
        "type":"client", 
        "command":"open",
        "UARTPorts":UARTPorts, 
        "UARTBaudrate":UARTBaudRate, 
        "UARTStopBit":UARTStopBit, 
        "UARTDataLen":UARTDataLen,
        "UARTCheckBit":UARTCheckBit,
        "UARTIntervalSendData":UARTIntervalSendData,
        "UARTSendData":UARTSendData
    }

    // console.info(webSocketData);
    websocket.send(JSON.stringify(webSocketData));

    $('#UARTReceiveData').val("");
}

function UARTClose(){
    
    webSocketData = 
    {
        "categories":"uart", 
        "type":"client", 
        "command":"close",
    }
    
    // console.info(webSocketData);
    websocket.send(JSON.stringify(webSocketData));
}

function GPIOOpen(){
    GPIOPort1Mode = $("#GPIOPort1Mode option:selected").val(); 
    GPIOPort2Mode = $("#GPIOPort2Mode option:selected").val(); 
    GPIOPort3Mode = $("#GPIOPort3Mode option:selected").val(); 
    GPIOPort4Mode = $("#GPIOPort4Mode option:selected").val(); 
    GPIOPort5Mode = $("#GPIOPort5Mode option:selected").val(); 
    GPIOPort6Mode = $("#GPIOPort6Mode option:selected").val(); 
    GPIOPort7Mode = $("#GPIOPort7Mode option:selected").val(); 
    GPIOPort8Mode = $("#GPIOPort8Mode option:selected").val(); 


    webSocketData = 
    {
        "categories":"gpio", 
        "type":"client", 
        "command":"open",
        "1":GPIOPort1Mode, 
        "2":GPIOPort1Mode, 
        "3":GPIOPort1Mode, 
        "4":GPIOPort1Mode, 
        "5":GPIOPort1Mode, 
        "6":GPIOPort1Mode, 
        "7":GPIOPort1Mode, 
        "8":GPIOPort1Mode
    }

    // console.info(webSocketData);
    websocket.send(JSON.stringify(webSocketData));
}

function GPIOClose(){
    webSocketData = 
    {
        "categories":"gpio", 
        "type":"client", 
        "command":"close",
    }
    
    // console.info(webSocketData);
    websocket.send(JSON.stringify(webSocketData));
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

    websocket.onopen = function () {
        console.info("WebSocket connect success.");
    };
    websocket.onerror = function () {
        console.info("WebSocket error.");
        alert("Please Check WebSocket Server Working Well.");
    };
    websocket.onmessage = function (message) {

        console.log(message.data);

        root = eval("(" + message.data + ")");

        if (root["categories"] == "uart" && root["type"] == "server" && root["command"] == "receive") {
            $('#UARTReceiveData').val($('#UARTReceiveData').val() + "send: " + root["send_index"] + ", recv: " + root["recv_index"] + ", cmp: " + root["cmp_index"] + ", data: " + root["data"]);
            if($('#UARTReceiveData').length)
                $('#UARTReceiveData').scrollTop($('#UARTReceiveData')[0].scrollHeight - $('#UARTReceiveData').height());
        }

        if (root["categories"] == "gpio" && root["type"] == "server") {
        }
    };

    $("#UARTSendData").val("1234567890\n");
}); 

