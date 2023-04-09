

var area = document.getElementById('area-select');
var city = document.getElementById('cities-select');
var storeList = document.getElementById('store_list_items');

var maper = document.getElementById('maper');

area.addEventListener('change', loadStoreAndCities);
city.addEventListener('change', loadStoreInCities);


function loadStoreAndCities(){

    let areaId = area.value;
    if(areaId == ''){
        return;
    }
    let areaRequest = new XMLHttpRequest();
    areaRequest.open('GET', 'http://localhost/wp-scg/index.php/wp-json/wp/v2/area-store?parent='+areaId+'');
    
    areaRequest.onload = function(){
        if(areaRequest.status >= 200 && areaRequest.status < 400){
            
            var data = JSON.parse(areaRequest.responseText);
            option(data);

        }else{
            console.log("Can't connect to server");
        }
    };
    areaRequest.onerror = function (){
      
        console.log("Connection error");

    }

    areaRequest.send();

}


//load cities
function loadStoreInCities(){

    let cityId = city.value;
    let cityRequest = new XMLHttpRequest();
    let typeId = document.getElementById('location').getAttribute('data-type-id');
    cityRequest.open('GET', 'http://localhost/wp-scg/index.php/wp-json/wp/v2/store-api?area-store='+cityId+'&cat-store='+typeId+'');
    
    cityRequest.onload = function(){
        if(cityRequest.status >= 200 && cityRequest.status < 400){
            var data = JSON.parse(cityRequest.responseText);
            if(data.length == 0){
                alert('Không có cửa hàng nào ở thành phố này, vui lòng chọn thành phố khác');
                return;
            }
            storesHtml(data);
            maper.setAttribute("data-pins", JSON.stringify(filterJson(data)));
            initMap();
            
        }else{
            console.log("Can't connect to server");
        }
    };
    cityRequest.onerror = function (){
      
        console.log("Connection error");

    }

    cityRequest.send();

}

//load cities for each region;
function option(citiesData){
    let html = '';
    for(i=0; i < citiesData.length; i++){
        html+= '<option value="'+citiesData[i].id+'">'+citiesData[i].name+'</option>';
    }
    city.innerHTML = html;
}

function filterJson(jsonData){
    let datas = [];
   
    if(jsonData == ''){
        return;
    }

    for(i = 0; i < jsonData.length; i++){
        let data = {   title: '', 
                position: {
                    lat: '',
                    lng: ''
                }
            };

        data.title = jsonData[i].title.rendered;
        data.position.lat = jsonData[i]._store_lat;
        data.position.lng = jsonData[i]._store_lang;
        datas.push(data);
    }
    return datas;
}

function storesHtml(storesData){
									
    let html = '';
    for(i=0; i < storesData.length; i++){
        html += '<div class="location__item">';
        html += '<h4 class="title">'+storesData[i].title.rendered+'</h4>';
        html += '<ul class="location__item__info">';
        if(storesData[i]._store_address != ''){
            html += '<li><i class="dvu dvu-pin mr-2"></i>Địa chỉ: '+storesData[i]._store_address+'</li>';
        }
        if(storesData[i]._store_phone != ''){
            html += '<li><i class="dvu dvu-phone mr-2"></i>Điện thoại: '+storesData[i]._store_phone+'</li>';
        }
        if(storesData[i]._store_hotline != ''){
            html += '<li><i class="dvu dvu-phone mr-2"></i>Địa chỉ: '+storesData[i]._store_hotline+'</li>';
        }
        if(storesData[i]._store_email != ''){
            html += '<li><i class="dvu dvu-email mr-2"></i>Email: '+storesData[i]._store_email+'</li>';
        }
        if(storesData[i]._store_fax != ''){
            html += '<li><i class="dvu dvu-inbox mr-2"></i>Fax: '+storesData[i]._store_fax+'</li>';
        }
        if(storesData[i]._store_website != ''){
            html += '<li><i class="dvu dvu-web mr-2"></i>Web: <a href="'+storesData[i]._store_website_link+'" target="_blank">'+storesData[i]._store_website+'</a></li>';
        }
        html+= '</ul></div>';
    }

    storeList.innerHTML = html;

}



function initMap() {
       
    let locations = maper.getAttribute('data-pins');
    locations = jQuery.parseJSON(locations);

    let mapicon = {
        url: 'https://scg-athome.vn/wp-content/themes/scghome/images/icon-pin.svg',
        anchor: new google.maps.Point(15,30),
        scaledSize: new google.maps.Size(30,30)
    };

    let vinh = {
        lat:  17.432314, 
        lng: 106.557798,
    };

    let mapOptions = { 
        "center": vinh, 
        "clickableIcons": true, 
        "disableDoubleClickZoom": false, 
        "draggable": true, 
        "fullscreenControl": false, 
        "keyboardShortcuts": true, 
        "mapMaker": true, 
        "mapTypeControl": false, 
        "mapTypeControlOptions": {  "text": "Default (depends on viewport size etc.)",  "style": 0 }, 
        "mapTypeId": "roadmap", 
        "rotateControl": true, 
        "scaleControl": true, 
        "scrollwheel": false, 
        "streetViewControl": false, 
        "styles": [  {   
            "featureType": "all",   
            "elementType": "all",   
            "stylers": [    
                {"saturation": -100},    
                {"gamma": 1 }   
            ]  
        } ], 
        "zoom": 6, 
        "zoomControl": true};
    
    let map = new google.maps.Map(maper,mapOptions);

    locations.forEach(function(location){

        let marker = new google.maps.Marker({
           
            position: { lat: parseFloat(location.position.lat), lng: parseFloat(location.position.lng) },
            map: map,
            icon: mapicon,
            title: location.title,
            animation: google.maps.Animation.DROP,
        });
       
    })

}