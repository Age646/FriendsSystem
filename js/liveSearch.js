function showHint(str)
{
    let xmlhttp = new XMLHttpRequest();
    let showimages = false;
    if (str.length ==0){
        document.getElementById("txtHint").innerHTML = "".style.border="#FFFFFF";
     return;
    }
    xmlhttp.onreadystatechange = function (){
        if (this.readyState == 4 && this.status == 200)
        {
            console.log(this.responseText);
            let data = JSON.parse(this.responseText);
            let uic = document.getElementById("txtHint");
            uic.innerHTML = "";
            data.forEach(function (obj)
            {
                uic.innerHTML += "<li><a href='index.php?id="+obj._name+"'><img src='images/" + obj._picture + "' height='30' width='30'>" +
                    obj._name + "</a></li><br/>";
                //uic.innerHTML += obj._username;
                console.log(obj._image);
            });
        }
        else{
            uic.innerHTML = "<p1>no suggestions</p1>";

        }
    };
    xmlhttp.open("GET", "liveSearch.php?q=" + str, true);
    xmlhttp.send();

}