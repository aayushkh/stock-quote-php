<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"><title>Stock Search</title>

<style>
    html, body {
        width: 100%;
        height: 100%;
        margin: auto;
        background-color: #FFF;
    }

    .formElement {
        width: 40%;
        margin: auto;
        background-color: #EEE;
    }

    h1{
        text-align: center;
        font-size: 40px;
        border-bottom: 1px solid #808080;        
    }

    .stockInput {
        text-align: center;
    }
    
    .alVan {
        padding-top: 20px;
    }
    
    .lineDiv {
        padding-top: 20px;
        padding-bottom: 20px;
    }
    
    .alVanTable {
        width: 80%;
        margin: auto;
    }
    
    td {
        text-align: center;
    }
    
    .dark{
        background-color: #BBB;
    }
    
    #container {
        padding-bottom: 20px;
        width: 80%;
        margin: auto;
    }
    
    a {
        color : #00F;
        text-decoration: none;
    }
    
    .button {
        color: gray;
        width: 100%;
        margin: auto;
        text-align: center;
    }

    #picture {
        justify-content: center;
    }

    .SATable {
        width: 80%;
        margin: auto;
    }
    
    table, th, td {
        background-color: #EEE;
        border: 2px solid #CCC;
        border-collapse: collapse;
    }
    
    #inst {
        margin-bottom: 0;
    }
    
</style>

<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
    
</head>
<body>
    
<div class="formElement">
    <form method="post" id="myForm" >
        <fieldset>
            <h1><i>Stock Search</i></h1>
                <div class="stockInput">
                    <p>Enter Stock Ticker Symbol* : 
                        <input type="text" name="symbol" id="symbol" value="<?php echo isset($_POST["symbol"]) ? $_POST["symbol"] : "" ?>">
                    </p>
                    <input type="submit" id="submit" name="submit" value="Search" onclick="submitButton()">
                    <input type="submit" id="clear" name="clear" value="Clear" onclick="resetForm();">
                    <p>-Mandatory Field*</p>
                </div>
        </fieldset>
    </form>
</div>
    
<script>   
    
function resetForm() { 
    
    var symbolElmt = document.getElementById("symbol");
    console.log(symbolElmt.value);
    //clearing symbol value
    console.log(symbolElmt.value.length);
    if (symbolElmt.value.length != 0){
        symbolElmt.value = "";
    }
}
    
function submitButton() {
    var doc = document.getElementById("symbol");
    if(doc.value == ""){
        alert(" Don't leave Blank. Enter a STOCK symbol. Eg. AAPL,MSFT,GOOGL,etc ")
    }
}
    
</script>

    
<script>       

var myChart;   
var text = new Array();
var close = new Array();
var vol = new Array();   

function drawPVChart(symbol) {
    var datesPV = new Array();
    var price = new Array();
    var volume = new Array();
    
    
    
    
    var temp = text[0];
    var tempTwo = text[0];
    tempTwo = temp.substring(1, temp.length);
    datesPV[0] = temp.substring(6, temp.length);
    
    var st = tempTwo;
    var pattern = /(\d{4})-(\d{2})-(\d{2})/;
    var dt = st.replace(pattern,'$2/$3/$1');
    
    price[0] = parseFloat(close[0]);
    volume[0] = parseInt(vol[0]);
    
    for(i=1;i<126;i++){
        temp = text[i];
        datesPV[i] = temp.substring(5, temp.length);
        price[i] = parseFloat(close[i]);
        volume[i] = parseInt(vol[i]);
//        console.log(datesPV[i] + " " + price[i] + " " + volume[i])
    } 


    if(typeof myChart == "undefined"){
        myChart =  Highcharts.chart('container',  {
            chart: {
                borderColor : '#DDDDDD',
                borderWidth: 2,
                type: 'line',
                marginRight: 180
            },
            title: {
                text: 'Stock Price(' + dt + ')'   
            },
            subtitle: {
                useHTML: true,
                text: 'Source: <a target="_blank" href="https://www.alphavantage.co/" style="text-decoration: none" >Alpha Vantage</a>',
                style: {
                    color: '#00F'
                }
            },
            legend: {
                itemStyle: {
                     fontSize:'9px'
                },
                align: 'right',
                verticalAlign: 'center',
                layout: 'vertical',
                x: 0,
                y: 200
            },
            xAxis: {
                categories: datesPV.reverse(),
//                labels: { 
//                format: '{value:%m/%d}'
//                },
                tickInterval: 5
            },
            yAxis: [{
                title: {
                  text: 'Stock Price'
               },
            },{
                title: {
                  text: 'Volume'
               },
                opposite: true
            }],
            series:[{
            name: symbol,
            type: 'area',
            data: price.reverse(),
            lineWidth: 1,
            marker: {
                enabled: false,
                symbol: 'circle',
                radius: 2,
                states: {
                    hover: {
                        enabled: true
                    }
                }
            },
            yAxis: 0,
            color: '#ED918F'
            },{name: symbol + ' Volume',
            type: 'column',
            data: volume.reverse(),
            yAxis: 1,
            color: '#EEE'
            }]
        });
    }
    
    else {
        
        //remove all series 
        while(myChart.series.length > 0){
        myChart.series[0].remove(true);
        }
        
        //remove all yAxis
        while(myChart.yAxis.length > 0){
        myChart.yAxis[0].remove(true);
        }
        
        //add yAxis
        
        myChart.addAxis({
            title: {
              text: 'Stock Price'
        }});
        
        myChart.addAxis({
            title: {
              text: 'Volume'
            },
            opposite: true
        });
        
        
        
        myChart.update({
            title: {
                text: 'Stock Price (' + dt + ')'   
            },
            legend: {
                itemStyle: {
                     fontSize:'9px'
                },
                align: 'right',
                verticalAlign: 'center',
                layout: 'vertical',
                x: 0,
                y: 200
            },
            xAxis: {
                categories: datesPV.reverse(),
                tickInterval: 5
            }
        });

        //adding new series
        myChart.addSeries({
                name: symbol,
                type: 'area',
                data: price.reverse(),
                lineWidth: 1,
                marker: {
                    enabled: false,
                    symbol: 'circle',
                    radius: 2,
                    states: {
                        hover: {
                            enabled: true
                        }
                    }
                },
                yAxis: 0,
                color: '#ED918F'
            });
        myChart.addSeries({                        
                name: symbol + ' Volume',
                type: 'column',
                data: volume.reverse(),
                yAxis: 1,
                color: '#EEE'
            });         
    }    
}   
    
function parseSMA(symbol) {
    url = "https://www.alphavantage.co/query?function=SMA&symbol=" + symbol + "&interval=daily&time_period=10&series_type=close";
    url += "&apikey=DA9Y6YJPSAAPQJ4X";
    console.log(url);
    
    var req = null;
    var jsonObj;
    
    //	code for native XMLHTTPRequest()
    if	(window.XMLHttpRequest) {
        try {
            req = new XMLHttpRequest();
        }
        catch(e) {   
        }
    }
    //	code for IE/Windows ActiveX version
    else if(window.ActiveXObject) {
        try {
            req = new ActiveXObject("Microsoft.XMLHTTP");
        }
        catch(e) {
        }
    }
    if(req) {
        req.open("GET",url,true);
        req.onreadystatechange = function() {
            if(req.readyState == 4) {
                if(req.status == 200) {
                    jsonObj = JSON.parse(req.responseText); 
//                    console.log(jsonObj);
                    generateData(jsonObj,"SMA",symbol);
                }
                else {
                    alert("There was a problem retrieving the JSON data: \n" + req.statusText);
                }
            }
        };
        req.send();
    }
}     

function parseEMA(symbol) {
    url = "https://www.alphavantage.co/query?function=EMA&symbol=" + symbol + "&interval=daily&time_period=10&series_type=close";
    url += "&apikey=DA9Y6YJPSAAPQJ4X";
    console.log(url);
    
    var req = null;
    var jsonObj;
    
    //	code for native XMLHTTPRequest()
    if	(window.XMLHttpRequest) {
        try {
            req = new XMLHttpRequest();
        }
        catch(e) {   
        }
    }
    //	code for IE/Windows ActiveX version
    else if(window.ActiveXObject) {
        try {
            req = new ActiveXObject("Microsoft.XMLHTTP");
        }
        catch(e) {
        }
    }
    if(req) {
        req.open("GET",url,true);
        req.onreadystatechange = function() {
            if(req.readyState == 4) {
                if(req.status == 200) {
                    jsonObj = JSON.parse(req.responseText);
//                    console.log(jsonObj);
                    generateData(jsonObj,"EMA",symbol);
                }
                else {
                    alert("There was a problem retrieving the JSON data: \n" + req.statusText);
                }
            }
        };
        req.send();
    }
}
    
// 2 vals under each date    
function parseSTOCH(symbol) {
    url = "https://www.alphavantage.co/query?function=STOCH&symbol=" + symbol + "&interval=daily";
    url += "&apikey=DA9Y6YJPSAAPQJ4X";
    console.log(url);
    var req = null;
    var jsonObj;
    
    //	code for native XMLHTTPRequest()
    if	(window.XMLHttpRequest) {
        try {
            req = new XMLHttpRequest();
        }
        catch(e) {   
        }
    }
    //	code for IE/Windows ActiveX version
    else if(window.ActiveXObject) {
        try {
            req = new ActiveXObject("Microsoft.XMLHTTP");
        }
        catch(e) {
        }
    }
    if(req) {
        req.open("GET",url,true);
        req.onreadystatechange = function() {
            if(req.readyState == 4) {
                if(req.status == 200) {
                    jsonObj = JSON.parse(req.responseText);
//                    console.log(jsonObj);
                    generateData(jsonObj,"STOCH",symbol);
                }
                else {
                    alert("There was a problem retrieving the JSON data: \n" + req.statusText);
                }
            }
        };
        req.send();
    }
}
    
function parseRSI(symbol) {
    url = "https://www.alphavantage.co/query?function=RSI&symbol=" + symbol + "&interval=daily&time_period=10&series_type=close";
    url += "&apikey=DA9Y6YJPSAAPQJ4X";
    console.log(url);
    var req = null;
    var jsonObj;
    
    //	code for native XMLHTTPRequest()
    if	(window.XMLHttpRequest) {
        try {
            req = new XMLHttpRequest();
        }
        catch(e) {   
        }
    }
    //	code for IE/Windows ActiveX version
    else if(window.ActiveXObject) {
        try {
            req = new ActiveXObject("Microsoft.XMLHTTP");
        }
        catch(e) {
        }
    }
    if(req) {
        req.open("GET",url,true);
        req.onreadystatechange = function() {
            if(req.readyState == 4) {
                if(req.status == 200) {
                    jsonObj = JSON.parse(req.responseText);
//                    console.log(jsonObj);
                    generateData(jsonObj,"RSI",symbol);
                }
                else {
                    alert("There was a problem retrieving the JSON data: \n" + req.statusText);
                }
            }
        };
        req.send();
    }
}
    
function parseADX(symbol) {
    url = "https://www.alphavantage.co/query?function=ADX&symbol=" + symbol + "&interval=daily&time_period=10";
    url += "&apikey=DA9Y6YJPSAAPQJ4X";
    console.log(url);
    var req = null;
    var jsonObj;
    
    //	code for native XMLHTTPRequest()
    if	(window.XMLHttpRequest) {
        try {
            req = new XMLHttpRequest();
        }
        catch(e) {   
        }
    }
    //	code for IE/Windows ActiveX version
    else if(window.ActiveXObject) {
        try {
            req = new ActiveXObject("Microsoft.XMLHTTP");
        }
        catch(e) {
        }
    }
    if(req) {
        req.open("GET",url,true);
        req.onreadystatechange = function() {
            if(req.readyState == 4) {
                if(req.status == 200) {
                    jsonObj = JSON.parse(req.responseText);
//                    console.log(jsonObj);
                    generateData(jsonObj,"ADX",symbol);
                }
                else {
                    alert("There was a problem retrieving the JSON data: \n" + req.statusText);
                }
            }
        };
        req.send();
    }
}
    
function parseCCI(symbol) {
    url = "https://www.alphavantage.co/query?function=CCI&symbol=" + symbol + "&interval=daily&time_period=10";
    url += "&apikey=DA9Y6YJPSAAPQJ4X";
    console.log(url);
    var req = null;
    var jsonObj;
    
    //	code for native XMLHTTPRequest()
    if	(window.XMLHttpRequest) {
        try {
            req = new XMLHttpRequest();
        }
        catch(e) {   
        }
    }
    //	code for IE/Windows ActiveX version
    else if(window.ActiveXObject) {
        try {
            req = new ActiveXObject("Microsoft.XMLHTTP");
        }
        catch(e) {
        }
    }
    if(req) {
        req.open("GET",url,true);
        req.onreadystatechange = function() {
            if(req.readyState == 4) {
                if(req.status == 200) {
                    jsonObj = JSON.parse(req.responseText);
//                    console.log(jsonObj);
                    generateData(jsonObj,"CCI",symbol);
                }
                else {
                    alert("There was a problem retrieving the JSON data: \n" + req.statusText);
                }
            }
        };
        req.send();
    }
}
    
// 3 vals under each date
function parseBBANDS(symbol) {
    url = "https://www.alphavantage.co/query?function=BBANDS&symbol=" + symbol + "&interval=daily&time_period=10&series_type=close";
    url += "&apikey=DA9Y6YJPSAAPQJ4X";
    console.log(url);
    var req = null;
    var jsonObj;
    
    //	code for native XMLHTTPRequest()
    if	(window.XMLHttpRequest) {
        try {
            req = new XMLHttpRequest();
        }
        catch(e) {   
        }
    }
    //	code for IE/Windows ActiveX version
    else if(window.ActiveXObject) {
        try {
            req = new ActiveXObject("Microsoft.XMLHTTP");
        }
        catch(e) {
        }
    }
    if(req) {
        req.open("GET",url,true);
        req.onreadystatechange = function() {
            if(req.readyState == 4) {
                if(req.status == 200) {
                    jsonObj = JSON.parse(req.responseText);
//                    console.log(jsonObj);
                    generateData(jsonObj,"BBANDS",symbol);
                }
                else {
                    alert("There was a problem retrieving the JSON data: \n" + req.statusText);
                }
            }
        };
        req.send();
    }
}
    
// 3 vals under each date
function parseMACD(symbol) {
    url = "https://www.alphavantage.co/query?function=MACD&symbol=" + symbol + "&interval=daily&time_period=10&series_type=close";
    url += "&apikey=DA9Y6YJPSAAPQJ4X";
    console.log(url);
    var req = null;
    var jsonObj;
    
    //	code for native XMLHTTPRequest()
    if	(window.XMLHttpRequest) {
        try {
            req = new XMLHttpRequest();
        }
        catch(e) {   
        }
    }
    //	code for IE/Windows ActiveX version
    else if(window.ActiveXObject) {
        try {
            req = new ActiveXObject("Microsoft.XMLHTTP");
        }
        catch(e) {
        }
    }
    if(req) {
        req.open("GET",url,true);
        req.onreadystatechange = function() {
            if(req.readyState == 4) {
                if(req.status == 200) {
                    jsonObj = JSON.parse(req.responseText);
//                    console.log(jsonObj);
                    generateData(jsonObj,"MACD",symbol);
                }
                else {
                    alert("There was a problem retrieving the JSON data: \n" + req.statusText);
                }
            }
        };
        req.send();
    }
}
    
function parseJSONContent(url){
    var req = null;
    //	code for native XMLHTTPRequest()
    if	(window.XMLHttpRequest) {
        try {
            req = new XMLHttpRequest();
        }
        catch(e) {   
        }
    }
    //	code for IE/Windows ActiveX version
    else if(window.ActiveXObject) {
        try {
            req = new ActiveXObject("Microsoft.XMLHTTP");
        }
        catch(e) {
        }
    }
    if(req) {
        req.open("GET",url,true);
        req.onreadystatechange = function() {
            if(req.readyState == 4) {
                if(req.status == 200) {
                    jsonObj = JSON.parse(req.responseText);
//                    console.log(jsonObj);
                }
                else {
                    alert("There was a problem retrieving the JSON data: \n" + req.statusText);
                }
            }
        };
        req.send();
    }
}    
    
function generateData(jsonObj,indicator,symbol) {
    dates = jsonObj["Technical Analysis: " + indicator + ""];
//    console.log(dates);
    var dateKeys = Object.keys(dates);
    var dateValues = Object.values(dates);
//    console.log(dateKeys);
//    console.log(dateValues);
    var singleDateVal = new Array();
    var singleKey;
    
    singleKey = Object.keys(dates[dateKeys[0]]);
//    console.log(singleKey);
    
    for(i=0;i<dateKeys.length;i++) {        
        singleDateVal[i] = Object.values(dates[dateKeys[i]]);
    }

//    console.log(singleDateVal);

    if(indicator == "SMA" | indicator == "EMA" | indicator == "CCI" | indicator == "ADX" | indicator == "RSI") {

        var tempValOne = new Array();
        var tempY = new Array();
//        var chartValOne = new Array();

         
        tempValOne[0] = parseFloat(singleDateVal[0][0]);
        for(i=1;i<126;i++) {
        tempValOne[i] = parseFloat(singleDateVal[i][0]);
        //dealing with dates
        temp = dateKeys[i];
        tempY[i] = temp.substring(5, temp.length); 
//        console.log(tempY);
//        chartValOne[i] = [tempY[i], parseFloat(tempValOne[i])];
        }
        
        temp = dateKeys[0];
        tempY[0] = temp.substring(5, 10);

        drawOneChart(tempValOne,tempY,indicator,symbol);

    }

    if(indicator == "BBANDS" | indicator == "MACD") {

        var tempValOne = new Array();
        var tempValTwo = new Array();
        var tempValThree = new Array();
        var tempY = new Array();
//        var chartValOne = new Array();
//        var chartValTwo = new Array();
//        var chartValThree = new Array();

        tempValOne[0] = parseFloat(singleDateVal[0][0]);
        tempValTwo[0] = parseFloat(singleDateVal[0][1]);
        tempValThree[0] = parseFloat(singleDateVal[0][1]);
        
        for(i=1;i<126;i++) {
        tempValOne[i] = parseFloat(singleDateVal[i][0]);
        tempValTwo[i] = parseFloat(singleDateVal[i][1]);
        tempValThree[i] = parseFloat(singleDateVal[i][2]);
        //dealing with dates
        temp = dateKeys[i];
        tempY[i] = temp.substring(5, temp.length);
//        console.log(tempY[i] + " - " +  tempValOne[i] + " - " + tempValTwo[i] + " - " + tempValThree[i])
//        chartValOne[i] = [tempY[i], parseFloat(tempValOne[i])];
//        chartValTwo[i] = [tempY[i], parseFloat(tempValTwo[i])];
//        chartValThree[i] = [tempY[i], parseFloat(tempValThree[i])];
//        console.log(tempY[i] + " - " + tempVals[i]);
        }
        
        temp = dateKeys[0];
        tempY[0] = temp.substring(5, 10);

        drawThreeChart(tempValOne,tempValTwo,tempValThree,singleKey,tempY,indicator,symbol);

    }

    if(indicator == "STOCH") {

        var tempValOne = new Array();
        var tempValTwo = new Array();
        var tempY = new Array();
//        var chartValOne = new Array();
//        var chartValTwo = new Array();

        temp = dateKeys[0];
        tempY[0] = temp.substring(5, 10);
        
        tempValOne[0] = parseFloat(singleDateVal[0][0]);
        tempValTwo[0] = parseFloat(singleDateVal[0][1]);
        
        for(i=1;i<126;i++) {
        tempValOne[i] = parseFloat(singleDateVal[i][0]);
        tempValTwo[i] = parseFloat(singleDateVal[i][1]);
        //dealing with dates
        temp = dateKeys[i];
        tempY[i] = temp.substring(5, temp.length);
//        chartValOne[i] = [tempY[i], parseFloat(tempValOne[i])];
//        chartValTwo[i] = [tempY[i], parseFloat(tempValTwo[i])];
//        console.log(tempY[i] + " - " + tempVals[i]);
        }

        drawTwoChart(tempValOne,tempValTwo,tempY,singleKey,indicator,symbol);

    }

} 
    
function drawOneChart(tempValOne,tempY,indicator,symbol){
    
    //remove all series 
    while(myChart.series.length > 0){
    myChart.series[0].remove(true);
    }
    
    //add new series
    myChart.addSeries({                        
        name: symbol,
        data: tempValOne.reverse()
    });
    
    //remove yAxis
    if(myChart.yAxis.length == 2){
        myChart.yAxis[1].remove(true);
    }
    
    myChart.update({
        legend: {
                itemStyle: {
                     fontSize:'9px'
                },
                align: 'right',
                verticalAlign: 'center',
                layout: 'vertical',
                x: 0,
                y: 200
        },
        xAxis: {
            categories: tempY.reverse(),
            tickInterval: 5
        },
        yAxis: {
            title: {
              text: indicator
           }
        },
        plotOptions: {
        series: {
            lineWidth: 1,
            marker: {
                symbol: 'square',
                radius: 1.5,
                enabled: true
                }
            }
        }
    })
    
    if(indicator == "SMA"){
        
        myChart.update({
            title: {
                text: 'Simple Moving Average (SMA)'   
            },
        })
        
    }
    if(indicator == "EMA"){

        myChart.update({
            title: {
                text: 'Exponential Moving Average (EMA)'   
            },
        })            
    }
    if(indicator == "CCI"){

        myChart.update({
            title: {
                text: 'Commodity Channel Index (CCI)'   
            },
        })

    }
    if(indicator == "ADX"){

        myChart.update({
            title: {
                text: 'Average Directional Movement Index (ADX)'   
            },
        })

    }
    if(indicator == "RSI"){

        myChart.update({
            title: {
                text: 'Relative Strength Index (RSI)'   
            },
        })

    } 
}
   
function drawTwoChart(tempValOne,tempValTwo,tempY,singleKey,indicator,symbol) {
    
    //remove series 
    while(myChart.series.length > 0){
    myChart.series[0].remove(true);
    }

    //remove yAxis
    if(myChart.yAxis.length == 2){
        myChart.yAxis[1].remove(true);
    }
    
    myChart.update ({
        title: {
            text: 'Stochastic Oscillator(STOCH)'   
        },
        legend: {
            itemStyle: {
                 fontSize:'9px'
            },
            align: 'right',
            verticalAlign: 'center',
            layout: 'vertical',
            x: 0,
            y: 200
        },
        xAxis: { 
            categories: tempY.reverse(),
            tickInterval: 5
        },
        yAxis: {
            title: {
              text: 'STOCH'
           }
        },
        plotOptions: {
        series: {
            lineWidth: 1,
            marker: {
                symbol: 'square',
                radius: 1,
                enabled: true
                }
            }
        },
    })
    
    //add new series
    myChart.addSeries({                        
        name: symbol + " " + singleKey[0],
        data: tempValOne.reverse()
    },false);
    myChart.addSeries({                        
        name: symbol + " " + singleKey[1],
        data: tempValTwo.reverse()
    },false);
    myChart.redraw();
    
}

function drawThreeChart(tempValOne,tempValTwo,tempValThree,singleKey,tempY,indicator,symbol) {

//    for(i=0;i<122;i++) {
//        console.log(tempValOne[i] + " - " + tempValTwo[i] + " - " + tempValThree[i]);
//    }
    
    //remove series 
    while(myChart.series.length > 0){
    myChart.series[0].remove(true);
    }     
    
    //remove yAxis
    if(myChart.yAxis.length == 2){
        myChart.yAxis[1].remove(true);
    }
    
    myChart.update({
        legend: {
                itemStyle: {
                     fontSize:'9px'
                },
                align: 'right',
                verticalAlign: 'center',
                layout: 'vertical',
                x: 0,
                y: 200
            },
            xAxis: { 
                categories: tempY.reverse(),
                tickInterval: 5
            },
            yAxis: {
                title: {
                  text: indicator
               }
            },
            plotOptions: {
            series: {
                lineWidth: 1,
                marker: {
                    symbol: 'square',
                    radius: 1.5,
                    enabled: true
                    }
                }
            }
    })
    
    if(indicator == "BBANDS"){
        
        myChart.addSeries({                        
            name: symbol + " " + singleKey[0],
            data: tempValOne.reverse()
        });
        myChart.addSeries({                        
            name: symbol + " " + singleKey[1],
            data: tempValTwo.reverse()
        });
        myChart.addSeries({                        
            name: symbol + " " + singleKey[2],
            data: tempValThree.reverse()
        });
           
        myChart.update({
            title: {
                text: 'Bollinger Bands (BBANDS)'   
            }
        })
    }
    
    if(indicator == "MACD"){
        
        myChart.addSeries({                        
            name: symbol + " " + singleKey[0],
            data: tempValOne.reverse()
        });
        myChart.addSeries({                        
            name: symbol + " " + singleKey[1],
            data: tempValTwo.reverse()
        });
        myChart.addSeries({                        
            name: symbol + " " + singleKey[2],
            data: tempValThree.reverse()
        },);
        
        myChart.update ({
            title: {
                text: 'Moving Average Convergence / Divergence (MACD)'   
            }
        })
    }   
}
    
</script>    
    
<?php
    
function getAlphaVantageURL($symbol) {
    $url = "http://www.alphavantage.co/";
    $url = $url . "query?function=TIME_SERIES_DAILY&symbol=" . $symbol . "&outputsize=full";
    $url = $url . "&apikey=DA9Y6YJPSAAPQJ4X";
    return $url;
}
                               
function getSeekingAlphaURL($symbol) {
    $url = "https://seekingalpha.com/api/sa/combined/";
    $url = $url . $symbol . ".xml";
    return $url;
}                           
    
//main function
if(isset($_POST["submit"])) {
    
    set_time_limit(10);
    
    $check = true;
    $symbol = $_POST["symbol"];
    $symbol = strtoupper($symbol);
    if($symbol == ""){
        $check = false;
    }
    $url = getAlphaVantageURL($symbol);
    echo "<script>console.log('" . $url . "');</script>";
    
    $json = file_get_contents($url);
    if($json === false) { 
        $html .= '<div class="lineDiv"><hr></div><div class="alVan"><table class="alVanTable" border="1" id="alVanTable"><tr><td><b>ERROR</b></td>';
        $html .= '<td>ERROR: Sorry could not retrieve the Alpha Vantage content. Please Try again.</td></tr></table></div>';
        echo $html;
    }
    $alVanData = json_decode($json, TRUE);
//    echo "<script>console.log('" . $alVanData . "');</script>";
    
    
    
    //producing the HTML 
    $html = "";
    
    
    if(isset($alVanData["Meta Data"])) {    
        
        $close = "";
        $open = "";
        $prevClose = "";
        $change = "";
        $changePercent = "";
        $daysRange = "";
        $volume = "";
        $timeStamp = "";   

        $timeStamp = $alVanData["Meta Data"]["3. Last Refreshed"];
        
        $dateVals = array();
        $openArr = array();
        $highArr = array();
        $lowArr = array();
        $closeArr = array();
        $volumeArr = array();
//        echo "<script> var text = new Array();</script>";
        foreach($alVanData["Time Series (Daily)"] as $dates => $value) {
            $dateVals[] = $dates;
//            echo "<script> var text = '" . $dates . "' ;</script>";
            foreach($value as $info => $details) {
                if(strcasecmp($info,"1. open") == 0){
                    $openArr[] = $details;
                }
                else if(strcasecmp($info,"2. high") == 0){
                    $highArr[] = $details;  
                }
                else if(strcasecmp($info,"3. low") == 0){
                    $lowArr[] = $details; 
                }
                else if(strcasecmp($info,"4. close") == 0){
                    $closeArr[] = $details; 
                }
                else if(strcasecmp($info,"5. volume") == 0){
                    $volumeArr[] = $details; 
                }
            }    
        }
        
//      print_r($dateVals);
//      echo '<script>var text =' . json_encode($dateVals) . ';</script>';
      
        $textOne = implode("','", $dateVals);
        $textTwo = implode("','", $closeArr);
        $textThree = implode("','", $volumeArr);
//      echo gettype( $text);
        echo "<script> text = [' $textOne '];</script>";
        echo "<script> close = [' $textTwo '];</script>";
        echo "<script> vol = [' $textThree '];</script>";
//        echo '<script>console.log(text);</script>';
//        echo '<script>console.log(text);</script>';
//        echo '<script>console.log(text);</script>';
            
        $close = $closeArr[0];
        $open = $openArr[0];
        $prevClose = $closeArr[1];
        $change = $closeArr[0] - $closeArr[1];
        $changePercent = ($change/$closeArr[0])*100;
        $daysRange = $lowArr[0] . " - " . $highArr[0];
        $volume = $volumeArr[0];
        
        

        $html .= '<div class="lineDiv"></div><div class=""><table class="alVanTable" border="1" id="alVanTable">'; 
        $html .= '<tr><td class="dark" style="text-align:left"><b>Stock Ticker Symbol</b></td><td>' . $symbol . '</td></tr>';
        $html .= '<tr><td class="dark" style="text-align:left"><b>Close</b></td><td>' . $close . '</td></tr>';
        $html .= '<tr><td class="dark" style="text-align:left"><b>Open</b></td><td>' . $open . '</td></tr>';
        $html .= '<tr><td class="dark" style="text-align:left"><b>Previous Close</b></td><td>' . $prevClose . '</td></tr>';
        $html .= '<tr><td class="dark" style="text-align:left"><b>Change</b></td><td>' . number_format($change,2) . " "; 

        if ($change>0) {
            $html .= '<img src="http://cs-server.usc.edu:45678/hw/hw6/images/Green_Arrow_Up.png" height=10px width=10px></td></tr>';
        }
        else if ($change<0) {
            $html .= '<img src="http://cs-server.usc.edu:45678/hw/hw6/images/Red_Arrow_Down.png" height=10px width=10px></td></tr>';
        }
        else if ($change==0) {
            $html .= '</td></tr>';
        }

        $html .= '<tr><td class="dark" style="text-align:left"><b>Change Percent</b></td><td>' . number_format($changePercent,2) . "% ";

        if ($changePercent>0) {
            $html .= '<img src="http://cs-server.usc.edu:45678/hw/hw6/images/Green_Arrow_Up.png" height=10px width=10px></td></tr>';
        }
        else if ($changePercent<0) {
            $html .= '<img src="http://cs-server.usc.edu:45678/hw/hw6/images/Red_Arrow_Down.png" height=10px width=10px></td></tr>';
        }
        else if ($changePercent==0) {
            $html .= '</td></tr>';
        }

        $html .= '<tr><td class="dark" style="text-align:left"><b>Day\'s Range</b></td><td>' . $daysRange . '</td></tr>';
        $html .= '<tr><td class="dark" style="text-align:left"><b>Volume</b></td><td>' . $volume . '</td></tr></tr>';
        $html .= '<tr><td class="dark" style="text-align:left"><b>Timestamp</b></td><td>' . $timeStamp . '</td></tr>';
        $html .= '<tr><td class="dark" style="text-align:left"><b>Indicators</b></td><td>';
        $html .= '<a href="javascript:drawPVChart(\'' . $symbol . '\');">Price</a>&nbsp';
        $html .= '<a href="javascript:parseSMA(\'' . $symbol . '\');">SMA</a>&nbsp';
        $html .= '<a href="javascript:parseEMA(\'' . $symbol . '\');">EMA</a>&nbsp';
        $html .= '<a href="javascript:parseSTOCH(\'' . $symbol . '\');">STOCH</a>&nbsp';
        $html .= '<a href="javascript:parseRSI(\'' . $symbol . '\');">RSI</a>&nbsp';
        $html .= '<a href="javascript:parseADX(\'' . $symbol . '\');">ADX</a>&nbsp';
        $html .= '<a href="javascript:parseCCI(\'' . $symbol . '\');">CCI</a>&nbsp';
        $html .= '<a href="javascript:parseBBANDS(\'' . $symbol . '\');">BBANDS</a>&nbsp';
        $html .= '<a href="javascript:parseMACD(\'' . $symbol . '\');">MACD</a>&nbsp';
        $html .= '</td></tr></tr></table><div class="lineDiv"></div></div>';
        $html .= '<div id="container"></div>';
        echo $html;
        echo '<script>drawPVChart(\'' . $symbol . '\');</script>';
        
        $urlSA = getSeekingAlphaURL($symbol);
        echo "<script>console.log('" . $urlSA . "');</script>";
        
        $xml = simplexml_load_file($urlSA);
        if ($xml === false) {
            echo "Failed loading XML: ";
            foreach(libxml_get_errors() as $error) {
                echo "<br>", $error->message;
            }
        } else {
            $i=0;
            $check = "https://seekingalpha.com/symbol/" . $symbol . "/news?source=feed_symbol_" . $symbol;
            
            $title = array();
            $pubDate = array();
            $href = array();
            
            foreach($xml->channel->item as $item){
                $var = $item->link;
                
                if($i<5){
                    if ((strcmp($var,$check))==0) {
                    }
                    else { 
//                        foreach($item->children() as $key=>$value){
////                            echo $key . "<br>";
////                            echo $value . "<br>";
//                            if((strcmp($key,"title"))==0){
//                                $title[] = $value;
//                            }
//                            if((strcmp($key,"link"))==0){
//                                $href[] = $value;
//                            }
//                            if((strcmp($key,"pubDate"))==0){
//                                $pubDate[] = $value;
//                            }
//                        } 
                        $title[] = $item->title;
                        $pubDate[] = $item->pubDate;
                        $href[] = $item->link;
                        $i++;
                    }
                }
            }
//            print_r($title);
//            echo implode(",",$title);
            
            $json_title = json_encode($title);
            $json_pubDate = json_encode($pubDate);
            $json_href = json_encode($href);
//            echo $json_title;
//            echo $json_pubDate;
//            echo $json_href;
            $htmlTwo = "";
        
            $htmlTwo .= '<div class="button"><p id="inst" onclick="switchImage()">click to show stock news<br></p><img id="arrow" onclick="switchImage()" src="//cs-server.usc.edu:45678/hw/hw6/images/Gray_Arrow_Down.png" style="width:50px;height:20px;border:0;"></a></div><div id="SeekAlTable"></div><div class="lineDiv"></div><div class="lineDiv"></div>';

            echo $htmlTwo;
        }

    }
    else if(isset($alVanData["Error Message"]) && ($check == true)) {
        $html .= '<div class="lineDiv"></div><div class="alVan"><table class="alVanTable" border="1" id="alVanTable"><tr><td><b>ERROR</b></td>';
        $html .= '<td>ERROR: NO record has been found, please enter a valid symbol</td></tr></table></div>';
        echo $html;
    }
}

?>
   
<script>

                               
function switchImage() {
    var image = document.getElementById('arrow');
    var inst = document.getElementById('inst');
    var saTable = document.getElementById('SeekAlTable');
    if (image.src.match("http://cs-server.usc.edu:45678/hw/hw6/images/Gray_Arrow_Up.png") | (inst.innerHTML === "click to hide stock news")) {
        image.src = "http://cs-server.usc.edu:45678/hw/hw6/images/Gray_Arrow_Down.png";
        html = "<div class='lineDiv'></div><div class='lineDiv'></div><div class='lineDiv'></div>";
        saTable.innerHTML = html;
        inst.innerHTML = "click to show stock news";
    } else {
        image.src = "http://cs-server.usc.edu:45678/hw/hw6/images/Gray_Arrow_Up.png";
        inst.innerHTML = "click to hide stock news";
        var title = <?php echo $json_title; ?>;
        var pubDate = <?php echo $json_pubDate; ?>;
        var href = <?php echo $json_href; ?>;
//         console.log(title);
        html = "<table class='SATable' border='1'' >";
        for(var i = 0; i < title.length; i++) {
            var obj = title[i];
            var obs = href[i];
            var obx = pubDate[i];
            obx[0] = obx[0].slice(0, -6);
            html += "<tr>"
            html += "<td style='text-align:left'><a target='_blank' href='" + obs[0] + "'> " + obj[0] + "</a>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp Publicated Time:&nbsp&nbsp" + obx[0] + "</td>"
            html += "</tr>";
//            console.log(obx[0]);
        }
        html += "</table><div class='lineDiv'></div>";
        saTable.innerHTML = html;
    }    
}  
    
</script>
    
</body>
</html>
