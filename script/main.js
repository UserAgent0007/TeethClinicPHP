let bod = document.body;

async function getData() {
    // const url = "https://chnu-student-interview-preparation.netlify.app/.netlify/functions/listItems";
    // const url = "https://chnu-student-interview-preparation.netlify.app/.netlify/functions/listItems?category=teeth"
    
    const jwtToken = localStorage.getItem("token"); // token that you retrieve upon login
    const apiUrl = "https://chnu-student-interview-preparation.netlify.app/.netlify/functions/userGetItems";

    const response = fetch(apiUrl, {
    method: "GET", // or POST for the userCreateItem endpoint
    headers: {
        "Content-Type": "application/json",
        "Authorization": `Bearer ${jwtToken}`
    }
    })

    .then(response => {
        if (!response.ok) {
        throw new Error(`HTTP error! Status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log("Data received:", data);
        let items = data.filter(item => item.category && item.category == "teeth");
        loadItems(items);
        // return data;
    })
    .catch(error => {
        console.error("Error fetching data:", error);
    });
    
    // try {
    //   const response = await fetch(url);
    //   if (!response.ok) {
    //     throw new Error(`Response status: ${response.status}`);
    //   }
  
    //   const json = await response.json();
    // const json = response.json()

    // console.log(json);
    // let items = json.filter(item => item.category && item.category == "teeth");
    // loadItems(items);
    // } catch (error) {
    //   console.error(error.message);
    // }
  }

  async function postData(item){
    // const url = "https://chnu-student-interview-preparation.netlify.app/.netlify/functions/postItem";
    const apiUrl = "https://chnu-student-interview-preparation.netlify.app/.netlify/functions/userCreateItem"
    

    if (localStorage.getItem("role") != "admin")
    {
        return;
    }

    const jwtToken = localStorage.getItem("token"); //token that you retrieve upon login
    
    // const apiUrl = "https://chnu-student-interview-preparation.netlify.app/.netlify/functions/userGetItems";

    let resp = fetch(apiUrl, {
    method: "POST", // or POST for the userCreateItem endpoint
    headers: {
        "Content-Type": "application/json",
        "Authorization": `Bearer ${jwtToken}`
    },
    body: JSON.stringify(item)
    })
    .then(response => {
        if (!response.ok) {
        throw new Error(`HTTP error! Status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log("Data received:", data);
        alert("everything OKKKKKKKK")
    })
    .catch(error => {
        console.error("Error fetching data:", error);
    });

    // try{
    //     const response = await fetch(url, {
    //         method: "POST",
    //         body: JSON.stringify(item),
    //       }).then(() => getData());
    // } catch (error){
    //   console.error(error.message);
    // }
  }

async function submitItem(){
    let name = document.getElementById("name").value;
    let description = document.getElementById("description").value;
    let image = document.getElementById("image").value;
    let price = Number(document.getElementById("price").value);
    let item = {name: name, description: description, image: image, price: price, category: "teeth"}

    await postData(item);

    document.getElementById("name").value = "";
    document.getElementById("description").value = "";
    document.getElementById("image").value = "";
    document.getElementById("price").value = "";

}

function loadItems(items){
    let itemsContainer = document.querySelector("#items");
    let newItem = document.createElement(`div`);
    let itemsHtml = '<ul class="list_deals_" id="for_description">'

    let value = Number(document.getElementById ("search").value);

    items.forEach(element => {
        if (element.price <= value && element.description != "")
        {
            itemsHtml += `<li class="deals_ no_show" id="item-${element._id}"> <img class="prod_opt" src="${element.image}" alt="new device"> 
            <b class="desc">${element.name}</b><p class="desc to_remove">${element.description}</p> <button id="add_to_cart">Добавити в кошик</button>
            <span class="price_val" style="display:none">${element.price}</span></li>`; 
        }
        
    });
    itemsHtml += `</ul>`
    itemsContainer.innerHTML = itemsHtml;
    
    itemsContainer.appendChild(newItem);

    show_description()
    add_functionality()
} 

getData()

// ============================================================ creating cart

function show_with_filter(value)
{
    document.getElementById("prices").textContent = value;
    getData();

}

function show_description ()
{
    
    let ul_elements = document.getElementById("for_description");
    
    ul_elements.querySelectorAll("li").forEach (element => {
        
        im = element.querySelector("img")
        im.addEventListener("click", function () {
            element.classList.toggle("no_show");
        });
    });
    
}

let prod_ = []

function add_functionality ()
{
    let ul_elements = document.getElementById("for_description");

    ul_elements.querySelectorAll("li").forEach(element => {

        let add_button = element.querySelector("#add_to_cart");

        let copy_li = element.innerHTML;
        let new_li = document.createElement("li");
        new_li.innerHTML = copy_li;
        new_li.querySelector("#add_to_cart").remove();
        new_li.querySelector(".to_remove").remove();

        
        let clean_li = `<li class="obj_in_cart" id="${element.id}"><div class="opt_image_name">${new_li.innerHTML.trim()}</div>
        <div class="opt_add_remove">
            <button class="style_button_add-rem" id="add">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-lg" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2"/>
                </svg>
            </button>
            <span class="count">1</span>
            <button class="style_button_add-rem" id="subtract">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-dash-lg" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M2 8a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11A.5.5 0 0 1 2 8"/>
                </svg>
            </button>
        </div>
        </li>`;
        
        let price = new_li.querySelector(".price_val").innerText;
        let name_ = new_li.querySelector(".desc").textContent;

        add_button.addEventListener("click", function ()
        {
            if (!prod_.some(el => JSON.stringify(el) === JSON.stringify([`${clean_li}`, Number(price), name_])))
            {
                prod_.push([`${clean_li}`, Number(price), name_]);
                show_cart_opt(false, false);
                
            }

            else 
            {

                alert("товар вже в корзині")

                return
                
            }

            console.log(prod_);
            // console.log(prod_.get(clean_li));
        });
        
    });

    
}

let modal3 = document.getElementById("modal3");
let button_show_cart = document.getElementById("show_cart");
let close_btn2_1 = document.getElementById("close_btn2_1");

button_show_cart.addEventListener("click", function()
{
    modal3.classList.add("show");
    bod.classList.add("pointer_ev_overflow");
    
});

close_btn2_1.addEventListener("click", function ()
{
    modal3.classList.remove("show");
    bod.classList.remove("pointer_ev_overflow");
});

let cart_options = document.getElementById("obj_in_cart");

function show_cart_opt (flag1, flag2)
{
        
    if (prod_.size === 0)
    {
        return;
    }

    let itemsHtml = ``;

    if (flag1 == true)
    {
        itemsHtml = `<input type="radio" id="sort_by_price" value="1" name="group" checked>
        <label for="sort_by_price">Сортування по ціні</label> 
        <input type="radio" id="sort_by_name" value="2" name="group"> 
        <label for="sort_by_name">Сортування по назві</label><ul class="cart-list">`
    }

    else if (flag2 == true)
    {
        itemsHtml = `<input type="radio" id="sort_by_price" value="1" name="group">
        <label for="sort_by_price">Сортування по ціні</label> 
        <input type="radio" id="sort_by_name" value="2" name="group" checked> 
        <label for="sort_by_name">Сортування по назві</label><ul class="cart-list">`
    }

    else
    {
        itemsHtml = `<input type="radio" id="sort_by_price" value="1" name="group">
        <label for="sort_by_price">Сортування по ціні</label> 
        <input type="radio" id="sort_by_name" value="2" name="group"> 
        <label for="sort_by_name">Сортування по назві</label><ul class="cart-list">`
    }
        
    
    for (let el of prod_)
    {
        itemsHtml += `${el[0]}`; 
    }
    

    itemsHtml+=`</ul>`;
    cart_options.innerHTML = itemsHtml;

    add_remove_position()

    let checkbox_sort_numb = document.getElementById("sort_by_price")
    let checkbox_sort_name = document.getElementById("sort_by_name")

    checkbox_sort_numb.addEventListener("change", function ()
    {
        if (checkbox_sort_numb.checked)
        {
            save_changes ()
            
            prod_.sort(sort_price);

            show_cart_opt(true, false);
        }

        else
        {
            save_changes ()
            
            cart_options.querySelector("ul").remove();
        }
    });

    checkbox_sort_name.addEventListener("change", function ()
    {
        if (checkbox_sort_name.checked)
        {
            save_changes ();

            prod_.sort(sort_name);

            show_cart_opt(false, true);
        }

        else
        {
            save_changes ();
            
            cart_options.querySelector("ul").remove();
        }
    });
}

function save_changes ()
{
    let ul_ = cart_options.querySelector("ul")
    let i = 0

    ul_.querySelectorAll("li").forEach(element =>
    {
        prod_[i][0] = `<li class="obj_in_cart" id="${element.id}">${element.innerHTML}</li>`;
        i++;
    }
    );
}

let sum_container = document.getElementById("sum_of_buy")

function add_remove_position ()
{
    let ul_ = cart_options.querySelector("ul")
    let overall_pr = count_suma();
    sum_container.textContent = `Вся вартість ${overall_pr} грн`;

    chart_.destroy();
    chart_ = draw_chart_bar ();


    ul_.querySelectorAll("li").forEach(element=>
    {
        let add_b = element.querySelector("#add");
        let sub_b = element.querySelector("#subtract");
        let count_numb_cont = element.querySelector(".opt_add_remove");
        let count_element = count_numb_cont.querySelector(".count");

        add_b.addEventListener("click", function ()
        {
            count_numb_cont.querySelector(".count").textContent = Number(element.querySelector(".count").textContent) + 1;

            overall_pr = count_suma ();

            chart_.destroy();
            
            chart_ = draw_chart_bar()

            sum_container.textContent = `Вся вартість ${overall_pr} грн`;
        });

        
        
        sub_b.addEventListener("click", function ()
        {
            
            count_element.textContent = Number(element.querySelector(".count").textContent) - 1;
            

            if (Number(element.querySelector(".count").textContent) == 0)
            {

                let product_to_remove = prod_.find(e => e[0].includes(element.id));
                
                prod_ = prod_.filter(e => e !== product_to_remove);

                element.remove()

            }

            overall_pr = count_suma ();
            sum_container.textContent = `Вся вартість ${overall_pr} грн`;

            
            chart_.destroy();

            chart_ = draw_chart_bar()
            
        });
    }
    );
}

function count_suma () // з цього місця продовжити
{
    let ul_ = cart_options.querySelector("ul")
    let suma = 0

    ul_.querySelectorAll("li").forEach(element=>
    {
        let price = Number(element.querySelector (".price_val").textContent);
        let count = Number(element.querySelector (".count").textContent);

        suma += price * count;
    });

    return suma;
}

function sort_name (ob1, ob2)
{
    text1 = ob1[2];
    text2 = ob2[2];

    return text1.localeCompare(text2);
}

function sort_price (mas_a, mas_b)
{
    return mas_a[1] - mas_b[1];
}

// ======================================================= draw charts

const ctx = document.getElementById ("count_chart").getContext('2d');
let chart_ = draw_chart_bar();

function draw_chart_bar ()
{
    let name_prod = [];
    let prices = [];

    prod_.forEach(element =>
    {
        name_prod.push(element[2]);
        prices.push(element[1])
    }
    );

    let chart_ = new Chart(ctx, {

        type: 'bar',
        data: {
            labels: name_prod,
            datasets: [{
                label: 'Продажі',
                data: prices,
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'blue',
                borderWidth: 2
            }]
        },
        options: {
            plugins: {
                tooltip: {
                    enabled: true 
                },
                datalabels: {
                    anchor: 'end',
                    align: 'top',
                    formatter: (value) => value, 
                    color: 'black',
                    font: {
                        weight: 'bold',
                        size: 14
                    }
                }
            }
        },
        plugins: [ChartDataLabels] 
    });

    return chart_
}
//========================================================================= Add to database values

let butt_add_opt = document.getElementById("add_option");
let modal2 = document.getElementById("modal2")
let close_btn2 = document.getElementById("close_btn2")

butt_add_opt.addEventListener("click", function()
{
    modal2.classList.add("show");
    bod.classList.add("pointer_ev_overflow");
});

close_btn2.addEventListener("click", function ()
{
    modal2.classList.remove("show");
    bod.classList.remove("pointer_ev_overflow");
});

// ===================================================================
// modal window start - end line 117


let modal = document.getElementById("modal1");

let button_close = document.getElementById("close_btn");
let button_accept = document.getElementById("accept")


function show_func()
{
    setTimeout (function () 
    {
        modal.classList.add("show");
        bod.classList.add("pointer_ev_overflow") 
    }, 5000);

    setTimeout (function()
    {
        button_close.classList.add("show_")
    }, 10000);
    
}

button_close.onclick = function ()
{
    modal.classList.remove ("show");
    bod.classList.remove ("pointer_ev_overflow");
    button_close.classList.remove ("show_");
    sessionStorage.setItem ("shown", "true")
}

button_accept.addEventListener("click", function ()
{
    // window.location.href = "../index.html";
    modal.classList.remove ("show");
    bod.classList.remove ("pointer_ev_overflow");
    button_close.classList.remove ("show_");
    sessionStorage.setItem ("shown", "true")
}
);

document.addEventListener ("DOMContentLoaded", function ()
{
    if (!sessionStorage.getItem ("shown"))
    {
        show_func();
        
    }
});

window.addEventListener ("beforeunload", function ()
{
    if (!document.hasFocus())
    {

        sessionStorage.removeItem("show_non_m");
        sessionStorage.removeItem("shown");
        
    }
});

// modal winow end

// non-modal window start end - 165 line

let button_sub = document.getElementById("sub");
let button_no_sub = document.getElementById("no-sub");
let non_modal = document.getElementById("no_modal");



button_sub.addEventListener("click", function ()
{
    non_modal.classList.remove("show_non_modale");
    sessionStorage.setItem("show_non_m", "true");
});

button_no_sub.addEventListener("click", function () 
{
    non_modal.classList.remove("show_non_modale");
    sessionStorage.setItem("non_m", "true");
});

window.addEventListener("load", function ()
{
    sessionStorage.removeItem("non_m");
})

window.addEventListener("scroll", function ()
{
    
    if (window.scrollY > 700)
    {
            
                
            if (!sessionStorage.getItem("non_m") && sessionStorage.getItem("shown") && !sessionStorage.getItem("show_non_m"))
            {
                non_modal.classList.add("show_non_modale");
                    
            }
    }
});

// non modal window end

