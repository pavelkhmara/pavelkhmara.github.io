  let newCookie = document.cookie
  let rectIDs = document.querySelectorAll('.color-rect')
  let rectanglesArray = document.getElementsByClassName('color-rect')
  // const rectangleWrapper = document.querySelector('.rectangleWrapper');
  const rectangleWrapper = document.getElementsByClassName('rectangleWrapper')[0]

// Adding newRect

function addRect() {
  const colorValue = document.forms['form'].color.value
  const newDiv = document.createElement('div')
  const newRectDiv = document.createElement('div')
  // const onmouseover = document.createAttribute('onmouseover')
  const onmouseenter = document.createAttribute('onmouseenter')
  const onmouseleave = document.createAttribute('onmouseleave')
  onmouseenter.value = "displayCloser(event)"
  onmouseleave.value = "noneCloser(event)"
  newRectDiv.classList.add('color-rect')
  // newRectDiv.attributes[length] = "onmouseover"
  // newRectDiv.setNamedItem("onmouseover") = "displayCloser(event)"
  newRectDiv.attributes.setNamedItem(onmouseenter)
  newRectDiv.attributes.setNamedItem(onmouseleave)
  // newRectDiv.attributes.push("onmouseout")
  // newRectDiv.onmouseout = "noneCloser(event)"

  newRectDiv.style.backgroundColor = colorValue
  // newRectDiv.style.color = colorValue;
  newRectDiv.id = colorValue
  const p = document.createElement('p')
  p.innerText = colorValue
  const pCloser = document.createElement('p')
  pCloser.innerText = "x"
  newRectDiv.appendChild(pCloser)
  // rectIDs += newRectDiv;
  newDiv.appendChild(newRectDiv)
  newDiv.appendChild(p)
  rectangleWrapper.appendChild(newDiv)
  rectIDs = document.querySelectorAll('.color-rect')
  console.log(rectIDs);
  for (rectID of rectIDs) {
    rectID.firstChild.addEventListener("click", rectDeleter, true)
  }
}
//
// function rectClick(e) {console.log('Click on ' + e.id);}
// // rectIDs.addEventListener("click", rectClick)
// rectIDs.onclick = rectClick

function rectDeleter(event) {
  // console.log(event.path[0]);
  console.log(event);
  if (event.path[0].innerHTML === 'x') {
    rectangleWrapper.removeChild(event.path[0].parentNode.parentNode)
    rectIDs = document.querySelectorAll('.color-rect')
  }
}

function displayCloser(event) {
  // console.log(event.path[0].firstChild);
  // console.log(event)
  if (event.relatedTarget !== event.path[1]) {
    console.log('Target is: ' + event.relatedTarget);
  }
  // console.log(event.toElement.nodeName)
  event.path[0].firstChild.style.display = "block"
}

function noneCloser(event) {
  event.path[0].firstChild.style.display = "none"
}


// Cookies
// function setCookie(cname, cvalue, exdays) {
//   var d = new Date();
//   d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
//   var expires = "expires="+d.toUTCString();
//   document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
// }
//
// function getCookie(cname) {
//   var name = cname + "=";
//   var ca = document.cookie.split(';');
//   for(var i = 0; i < ca.length; i++) {
//     var c = ca[i];
//     while (c.charAt(0) == ' ') {
//       c = c.substring(1);
//     }
//     if (c.indexOf(name) == 0) {
//       return c.substring(name.length, c.length);
//     }
//   }
//   return "";
// }
//
// function checkCookies() {
//   var user = getCookie("username");
//   if (user != "") {
//     alert("Welcome again " + user);
//   } else {
//     user = prompt("Please enter your name:", "");
//     if (user != "" && user != null) {
//     setCookie("username", user, 365);
//     }
//   }
//
// }

// document.addEventListener("load", checkCookies);


// Сохрани Куки
// function saveCookies() {
//   if (navigator.cookieEnabled) {
//       var txt1 = document.getElementById("txt1");
//       var txt2 = document.getElementById("txt2");
//       if (txt1.value != "" && txt2.value != "") {
//         var d = new Date();
//         d.setTime(d.getTime() + 3600000); // Задан 1 час
//         var endDate = d.toUTCString();    // Дата удаления cookies
//         var str = "name1=" + encodeURIComponent(txt1.value);
//         str += ";expires=" + endDate + ";";
//
//         document.cookie = str;
//         str = "name2=" + encodeURIComponent(txt2.value);
//         str += ";expires=" + endDate + ";";
//
//         document.cookie = str;
//         txt1.value = "";
//         txt2.value = "";
//         showCookies();
//       }
//       else {
//         window.alert("Не заполнено обязательное поле");
//       }
//   }
// }
//
// // Удали Куки
// function deleteCookies() {
//   if (navigator.cookieEnabled) {
//       if (document.cookie != "") {
//         var d = new Date();
//         d.setTime(1000); // Дата в прошлом
//         var endDate = d.toUTCString();
//         document.cookie = "name1=;expires=" + endDate + ";";
//         console.log(document.cookie);
//         document.cookie = "name2=;expires=" + endDate + ";";
//         showCookies();
//       }
//       console.log("Deleted: " + document.cookie);
//   }
// }
//
// // Покажи Куки
// function showCookies() {
//   if (navigator.cookieEnabled) {
//       var div1 = document.getElementById("div1");
//
//       console.log("Cookies are: " + str);
//       console.log(txt1 + ". Last name: " + txt2);
//
//       if (document.cookie != "") {
//         var arr1 = [], arr2 = [];
//         var obj = {};
//         var str = document.cookie;
//         if (str.indexOf("; ") != -1) {
//             arr1 = str.split("; ");
//             for (var i = 0, c = arr1.length; i < c; i++) {
//               arr2 = arr1[i].split("=");
//               obj[arr2[0]] = arr2[1];
//             }
//         }
//         else {
//             arr2 = str.split("=");
//             obj[arr2[0]] = arr2[1];
//         }
//         var msg = "Привет, ";
//         msg += decodeURIComponent(obj.name2).replace("<", "&lt;");
//         msg += " " + decodeURIComponent(obj.name1).replace("<", "&lt;");
//         div1.innerHTML = msg;
//       }
//       else div1.innerHTML = "";
//   }
// }
//
// // Запуск для загрузки
// window.onload = function() {
//   showCookies();
// }
//
//
// console.log(document.cookie);
