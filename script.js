const users = [
    { username: "admin", password: "adminpass", isAdmin: true },
    { username: "user1", password: "userpass", isAdmin: false }
];

let currentUser = null;


function login(){

    const usernameEl = document.getElementById("username");
    const passwordEl = document.getElementById("password");
    const errorEl = document.getElementById("error");

    const user = usernameEl.value.trim();
    const pass = passwordEl.value.trim();

    const validUser = users.find(
        u => u.username === user && u.password === pass
    );

    if(validUser){

        currentUser = validUser;

        // Show inventory / hide login
        document.getElementById("loginBox").style.display = "none";
        document.getElementById("inventory").style.display = "block";

        errorEl.innerText = "";

        /* ===== ADMIN VIEW ===== */
        if(currentUser.isAdmin){

            document.querySelectorAll(".admin-control")
            .forEach(e => e.style.display = "table-cell");

            document.querySelectorAll(".claim-col")
            .forEach(e => e.style.display = "none");

        }
        /* ===== USER VIEW ===== */
        else{

            document.querySelectorAll(".admin-control")
            .forEach(e => e.style.display = "none");

            document.querySelectorAll(".claim-col")
            .forEach(e => e.style.display = "table-cell");

        }

    }else{
        errorEl.innerText = "Invalid username or password!";
    }

}

function logout(){

    currentUser = null;

    document.getElementById("inventory").style.display = "none";
    document.getElementById("loginBox").style.display = "block";

}

function claim(id, btn){

    const stockEl =
        document.getElementById(id + "-stock");

    const statusEl =
        document.getElementById(id + "-status");

    let stock = parseInt(stockEl.innerText);

    if(stock <= 0){
        btn.disabled = true;
        return;
    }

    // Reduce stock
    stock--;
    stockEl.innerText = stock;

    // If stock empty
    if(stock === 0){
        statusEl.innerText = "Not Available";
        statusEl.className = "not-available";
        btn.disabled = true;
    }

    // Thank you screen
    document.getElementById("inventory").style.display = "none";
    document.getElementById("thankYou").style.display = "block";

    setTimeout(()=>{
        document.getElementById("thankYou").style.display = "none";
        document.getElementById("inventory").style.display = "block";
    },2000);

}

function updateUniform(id){

    const stockEl =
        document.getElementById(id + "-stock");

    const statusEl =
        document.getElementById(id + "-status");

    const stockEdit =
        document.getElementById(id + "-stock-edit");

    const statusEdit =
        document.getElementById(id + "-status-edit");

    const newStock = parseInt(stockEdit.value);
    const newStatus = statusEdit.value;

    /* ===== UPDATE STOCK ===== */
    if(!isNaN(newStock)){
        stockEl.innerText = newStock;
    }

    /* ===== UPDATE STATUS ===== */
    statusEl.innerText = newStatus;

    if(newStatus === "Available"){
        statusEl.className = "available";
    }else{
        statusEl.className = "not-available";
    }

    /* ===== AUTO FIX IF ZERO ===== */
    if(parseInt(stockEl.innerText) === 0){
        statusEl.innerText = "Not Available";
        statusEl.className = "not-available";
    }

    alert("Uniform updated successfully!");

}

