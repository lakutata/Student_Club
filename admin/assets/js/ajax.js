/**
 * Created by M on 7/25/15.
 */
function Ajax(requestTarget,callback){
    var xmlhttp = null;
    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
        xmlhttp.open('GET', requestTarget, true);
        xmlhttp.send();
        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                callback(xmlhttp.responseText);
            }
        }
    } else {
        alert('您的浏览器不支持XMLHttprequest，请您更换浏览器后再使用本程序。');
    }
}