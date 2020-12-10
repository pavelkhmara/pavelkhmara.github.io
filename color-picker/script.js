console.log('Стартовая позиция Куки ► ' + document.cookie);
// Запуск для загрузки
window.onload = function() {
  showCookies()
}


let rectColorsArray = []
let cookies = document.cookie
let rectArray = document.querySelectorAll('.color-rect')
// let rectanglesArray = document.getElementsByClassName('color-rect') // Может этот вариант даже и не нужен
const rectangleWrapper = document.getElementsByClassName('rectangleWrapper')[0]
// Second variant getting of rects wrapper const rectangleWrapper = document.querySelector('.rectangleWrapper');

// Adding newRect
function addRect(color) {
  rectangleWrapper.appendChild(createNewDiv(color))
  refreshRectColorsArray()
  deleteRectFromCookie()
  addRectToCookie()
  console.log('Добавил ♦ прямоугольник');
}

function putColor() {
  addRect(document.forms['form'].color.value)
  console.log('Передал цвет из палитры');
}

function rectDeleter(event) {
  // console.log(event.path[0]);
  // console.log(event);
  if (event.path[0].innerHTML === 'x') {
    rectangleWrapper.removeChild(event.path[0].parentNode.parentNode)
    refreshRectColorsArray()
    deleteRectFromCookie()
    addRectToCookie()
    console.log('▼ Удалил');
  }
}

function displayCloser(event) {
  // console.log(event.path[0].firstChild);
  // console.log(event)
  // if (event.relatedTarget !== event.path[1]) {
  //   console.log('Target is: ' + event.relatedTarget);
  // }
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
function saveCookies() {
  if (navigator.cookieEnabled) {
      const txt1 = document.getElementById("txt1");
      const txt2 = document.getElementById("txt2");
      if (txt1.value != "" && txt2.value != "") {
        const d = new Date();
        d.setTime(d.getTime() + 3600000); // Задан 1 час
        const endDate = d.toUTCString();    // Дата удаления cookies

        let str = "name1=" + encodeURIComponent(txt1.value);
        str += ";expires=" + endDate + ";";
        document.cookie = str;

        str = "lastName=" + encodeURIComponent(txt2.value);
        str += ";expires=" + endDate + ";";
        document.cookie = str;
        console.log('Куки до ШОУ ' + document.cookie);
        txt1.value = "";
        txt2.value = "";
        showCookies();
        console.log('☻ Сохранил Имя и Фамилию. Новые Куки: ' + document.cookie);
      }
      else {
        window.alert("Не заполнено обязательное поле");
      }

  }
}

// Удали Куки
function deleteCookies() {
  if (navigator.cookieEnabled) {
      if (document.cookie != "") {
        const d = new Date();
        d.setTime(1000); // Дата в прошлом
        const endDate = d.toUTCString();
        document.cookie = "name1=;expires=" + endDate + ";";

        document.cookie = "lastName=;expires=" + endDate + ";";
        console.log('Удалил Имя и Фамилию. ' + document.cookie);
        showCookies();
        console.log('Cookies changes: ' + document.cookie);
      }
  }
}

function deleteRectFromCookie() {
  if (navigator.cookieEnabled) {
    if (document.cookie != "") {
      const d = new Date();
      d.setTime(1000); // Дата в прошлом
      const endDate = d.toUTCString();
      document.cookie = "myColorRectangles=;expires=" + endDate + ";";
      document.cookie = "rectNumber=;expires=" + endDate + ";";
      console.log('Удалил прямоугольники из Куки: ' + document.cookie);
    }
  }
}

function getCookieObj() {
  if (navigator.cookieEnabled) {
    let arr1 = [], arr2 = [], arr3 = [];
    let obj = {}
    const str = document.cookie;
    if (str.indexOf("; ") != -1) {
        arr1 = str.split("; ");
        for (let i = 0, c = arr1.length; i < c; i++) {
          arr2 = arr1[i].split("=");
          obj[arr2[0]] = arr2[1];
        }
    }
    else {
        arr2 = str.split("=");
        obj[arr2[0]] = arr2[1];
    }
    return obj
  }
}

function showRectsFromCookie() {
  let obj = getCookieObj()
  if (obj.myColorRectangles) {
    if (rectangleWrapper.childNodes.length === 0) {
      console.log('♥ ♥ ♥ Есть прямоугольники в Куки. Рисую. Состояние записи про прямоугольники у Куки: ' + decodeURIComponent(obj.myColorRectangles))
      let rectsString = decodeURIComponent(obj.myColorRectangles).replace("<", "&lt;")
      arr3 = rectsString.split(",")
      for (item of arr3) {
        addRect(item)
      }
    }
  } else {
    console.log('Name + lastName detected. Rectangles Cookie status: NO.  Start loading default Rects');
    // const defaultRectColors = ["#FF5733", "#F47913", "#F4EA13", "#8EF413", "#13F465", "#13F4CB", "#13B3F4", "#1376F4", "#7913F4", "#E313F4"]
    // for (color of defaultRectColors) {
    //   addRect(color)
    // }
  }
}

// Покажи Куки
function showCookies() {
  if (navigator.cookieEnabled) {
      const div1 = document.getElementById("div1");

      if (document.cookie != "") {
        let arr1 = [], arr2 = [], arr3 = [];
        let obj = {}
        const str = document.cookie;
        if (str.indexOf("; ") != -1) {
            arr1 = str.split("; ");
            for (let i = 0, c = arr1.length; i < c; i++) {
              arr2 = arr1[i].split("=");
              obj[arr2[0]] = arr2[1];
            }
        }
        else {
            arr2 = str.split("=");
            obj[arr2[0]] = arr2[1];
        }
        if (obj.lastName) {
          let msg = "Hi, ";
          msg += decodeURIComponent(obj.lastName).replace("<", "&lt;");
          msg += " " + decodeURIComponent(obj.name1).replace("<", "&lt;");
          msg += "! "
          div1.innerHTML = msg;
        } else {
          div1.innerHTML = ""
        }

        showRectsFromCookie()
        // console.log(rectangleWrapper.childNodes.length);
        // if (rectangleWrapper.childNodes.length === 3) {
        //
        // } else {
        //
        // }

        // let rectsString = decodeURIComponent(obj.myColorRectangles).replace("<", "&lt;")
        // arr3 = rectsString.split(",")
        // for (item of arr3) {
        //   addRect(item)
        // }

        // for (item of arr1) {
        //   arr3 = item.split("=")
        //   if (arr3[1] !== "" && arr3[1] !== obj.lastName && arr3[1] !== obj.name1)
        //     addRect(decodeURIComponent(arr3[1]).replace("<", "&lt;"))
        // }
      } else {
        console.log('Start loading default Rects');
        const defaultRectColors = ["#FF5733", "#F47913", "#F4EA13", "#8EF413", "#13F465", "#13F4CB", "#13B3F4", "#1376F4", "#7913F4", "#E313F4"]
        for (color of defaultRectColors) {
          addRect(color)
        }
        div1.innerHTML = ""
      }
  }
}


function addRectToCookie() {
  if (navigator.cookieEnabled) {
    const d = new Date();
    d.setTime(d.getTime() + 3600000); // Задан 1 час
    const endDate = d.toUTCString();    // Дата удаления cookies
    let str = "myColorRectangles=" + encodeURIComponent(rectColorsArray.toString())
    str += ";expires=" + endDate + ";";
    document.cookie = str
    str = "rectNumber=" + encodeURIComponent(rectColorsArray.length)
    str += ";expires=" + endDate + ";";
    document.cookie = str
    console.log('Already add rect to cookie');
  }
}





// Number(r).toString(16) + Number(g).toString(16) + Number(b).toString(16)
// parseInt(hex,16)
function getRectColorsArray(nodeListOfRects) {
  for (rect of nodeListOfRects) {
    const rgbColor = rect.style.backgroundColor
    const str = rgbColor.substring(4, rgbColor.length - 1).split(",")
    let hexColor = "#"
    for (item of str) {
      if (Number(item).toString(16) === "0") {
        hexColor += "00"
      } else {
        hexColor += Number(item).toString(16)
      }
    }
    rectColorsArray.push(hexColor)
  }
  return rectColorsArray
}


function createNewDiv(color) {
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
  newRectDiv.style.backgroundColor = color
  // newRectDiv.style.color = colorValue;
  newRectDiv.id = color
  const p = document.createElement('p')
  p.innerText = color
  const pCloser = document.createElement('p')
  pCloser.innerText = "x"
  newRectDiv.appendChild(pCloser)
  // rectArray += newRectDiv;
  newDiv.appendChild(newRectDiv)
  newDiv.appendChild(p)
  newDiv.addEventListener("click", rectDeleter, true)

  console.log('• прямоугольник создан');

  return newDiv
}


function refreshRectColorsArray() {
  rectColorsArray = []
  rectArray = document.querySelectorAll('.color-rect')
  getRectColorsArray(rectArray)
}






function showDefautRects() {
  let wrapper = document.querySelector('.rectangleWrapper')
  while (wrapper.hasChildNodes()) {
    console.log(wrapper.children.length);
    console.log(wrapper.firstChild);
    wrapper.removeChild(wrapper.firstChild)
    console.log('Wrapper length after deleting: ' + wrapper.children.length);
  }

  refreshRectColorsArray()
  deleteRectFromCookie()
  const defaultRectColors = ["#FF5733", "#F47913", "#F4EA13", "#8EF413", "#13F465", "#13F4CB", "#13B3F4", "#1376F4", "#7913F4", "#E313F4"]
  for (color of defaultRectColors) {
    addRect(color)
  }
}
