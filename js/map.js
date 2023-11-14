
class XMLRequest{
    constructor(){
        this.xmlhttp = new XMLHttpRequest();
    }

    makeRequest(url,input,token){
        this.xmlhttp.open("GET", url + "?q=" + input + "&token" + token, true);
        this.xmlhttp.send();
        // return this.xmlhttp;
    }

    getXml()
    {
        return this.xmlhttp;
    }

}

map = new OpenLayers.Map("mapdiv");
map.addLayer(new OpenLayers.Layer.OSM());
var feature;
epsg4326 =  new OpenLayers.Projection("EPSG:4326"); //WGS 1984 projection
projectTo = map.getProjectionObject(); //The map projection (Spherical Mercator)

constructMarker();

function constructMarker(){
    console.log("updating positions");
    xml = new XMLRequest();
    xml.xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            //create javascript objects using retreived data
            people = JSON.parse(this.responseText);
            //console.log(people);
            //vectorLayer.destroyFeatures();
            vectorLayer.removeFeatures(feature);
            people.forEach(function (obj){
                //console.log(obj.id);
                //for each object make a call to makeMap() and pass coords
                makeMarker(obj.lng,obj.lat,obj.name,obj.picture,obj.picture);
            });
        }
    };
    xml.makeRequest("mapData.php");
// xmlhttp.open("GET", "mapData.php", true);
// xmlhttp.send();
    //setTimeout(Testing, 3000);
}
//live location tracking
function updateCurrentuserLocation(){
//update user live location
    navigator.geolocation.getCurrentPosition((position) => {
        //console.log(position.coords.latitude, position.coords.longitude);
        lat = position.coords.latitude;
        lng = position.coords.longitude;
        console.log("current location: ",lat, lng);
        updateUserGPS(lng,lat);
        updateRequest = new XMLHttpRequest();
        //new xml request for updating coords
        updateRequest.open("GET", "locationUpdate.php?q="+lat+","+lng, true);
        updateRequest.send();

        setTimeout(updateCurrentuserLocation, 3000);//recall the function every 60 seconds
    })}
updateCurrentuserLocation();//call to live location timer

// navigate to this coords when map is opened
var lonLat = new OpenLayers.LonLat( -2.272282,53.483554 ).transform(epsg4326, projectTo);
var zoom=10; //map zoom
map.setCenter (lonLat, zoom);
var vectorLayer = new OpenLayers.Layer.Vector("Overlay");

// Define markers as "features" of the vector layer:
function makeMarker(lng,lat,name,img,marker) {//taking ajax data as input
    //console.log(feature.id);
    feature = new OpenLayers.Feature.Vector(
        new OpenLayers.Geometry.Point( lng, lat ).transform(epsg4326, projectTo),
        {description:name+' '+"<img src='images/"+ img +"' height='45px'>"} ,
        {externalGraphic: 'images/'+marker, graphicHeight: 30, graphicWidth: 30, graphicXOffset:-12, graphicYOffset:-25,}
    );
    vectorLayer.addFeatures(feature);
    map.addLayer(vectorLayer);
    //console.log(feature.id);
    //Adds map layer with coords to the vectorLayer
//    setTimeout(makeMap, 5000);
}
var GPS;
function updateUserGPS(lng,lat){
    //console.log("GPS: "+lng);
    if(!GPS){
        //if not set do nothing
    }
    else {
        //anything else destroy it
        destroyPopup(GPS);
        vectorLayer.removeFeatures(GPS);
    }
    GPS = new OpenLayers.Feature.Vector(
        new OpenLayers.Geometry.Point( lng, lat ).transform(epsg4326, projectTo),
        {description:"You're here"} ,
        {externalGraphic: 'images/marker.png', graphicHeight: 30, graphicWidth: 30, graphicXOffset:-12, graphicYOffset:-25,}
    );
    vectorLayer.addFeatures(GPS);
    map.addLayer(vectorLayer);
}
Controller(vectorLayer);
var controls;
function Controller(vectorLayer){
    controls = {
        selector: new OpenLayers.Control.SelectFeature(
            vectorLayer, { onSelect: createPopup, onUnselect: destroyPopup })

    };
    map.addControl(controls['selector']);
    controls['selector'].activate();
}
//Add a selector control to the vectorLayer with popup functions

// myPops = [];

function createPopup(feature) {
    feature.popup = new OpenLayers.Popup.FramedCloud("pop",
        feature.geometry.getBounds().getCenterLonLat(),
        null,
        '<div class="markerContent">'+feature.attributes.description+'</div>',
        null,
        true,
        function() { controls['selector'].unselectAll(); }
    );
    //feature.popup.closeOnMove = true;
    //myPops.push(feature);
    map.addPopup(feature.popup);
    //feature1 = feature.popup;
}
//console.log(myPops);
updateMarker();
function updateMarker(){
    if(!feature){
        //do nothing
    }
    else{
        //remove pop up
        destroyPopup(feature);
    }
    constructMarker();
    setTimeout(updateMarker, 5000);
}
//unshown pop up
function destroyPopup(feature) {
    //if the feature is active then destroy it
    if (feature.popup) {
        //feature.popup.hide();
        feature.popup.destroy();
        feature.popup = null;
    }
}
