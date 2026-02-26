/* ================= USERS (FRONTEND LOGIN ONLY) ================= */

const users = [
    { username: "admin", password: "adminpass", isAdmin: true },
    { username: "user1", password: "userpass", isAdmin: false }
];

let currentUser = null;


/* ================= LOGIN ================= */

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

        document.getElementById("loginBox").style.display = "none";
        document.getElementById("inventory").style.display = "block";

        errorEl.innerText = "";

        if(currentUser.isAdmin){

            document.querySelectorAll(".admin-control")
            .forEach(e => e.style.display = "table-cell");

            document.querySelectorAll(".claim-col")
            .forEach(e => e.style.display = "none");

        }else{

            document.querySelectorAll(".admin-control")
            .forEach(e => e.style.display = "none");

            document.querySelectorAll(".claim-col")
            .forEach(e => e.style.display = "table-cell");

        }

    }else{
        errorEl.innerText = "Invalid username or password!";
    }

}


/* ================= LOGOUT ================= */

function logout(){

    currentUser = null;

    document.getElementById("inventory").style.display = "none";
    document.getElementById("loginBox").style.display = "block";

}


/* ================= CLAIM (CONNECTED TO DATABASE) ================= */

function claim(id, btn){

    fetch("claim_uniform.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: "key=" + encodeURIComponent(id)
    })
    .then(res => res.text())
    .then(data => {

        console.log("Claim response:", data);

        if(data === "out"){
            alert("Out of stock!");
            btn.disabled = true;
            return;
        }

        if(data === "error"){
            alert("Error processing request.");
            return;
        }

        const result = JSON.parse(data);

        const stockEl = document.getElementById(id + "-stock");
        const statusEl = document.getElementById(id + "-status");

        stockEl.innerText = result.stock;
        statusEl.innerText = result.status;

        if(result.status === "Available"){
            statusEl.className = "available";
        }else{
            statusEl.className = "not-available";
            btn.disabled = true;
        }

        document.getElementById("inventory").style.display = "none";
        document.getElementById("thankYou").style.display = "block";

        setTimeout(()=>{
            document.getElementById("thankYou").style.display = "none";
            document.getElementById("inventory").style.display = "block";
        },2000);

    })
    .catch(err => {
        console.log(err);
        alert("Server error.");
    });

}


/* ================= ADMIN UPDATE (CONNECTED TO DATABASE) ================= */

function updateUniform(id){

    const stockEdit =
        document.getElementById(id + "-stock-edit");

    const statusEdit =
        document.getElementById(id + "-status-edit");

    const newStock = parseInt(stockEdit.value);
    const newStatus = statusEdit.value;

    if(isNaN(newStock)){
        alert("Enter valid stock number.");
        return;
    }

    fetch("update_uniform.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body:
            "key=" + encodeURIComponent(id) +
            "&stock=" + encodeURIComponent(newStock) +
            "&status=" + encodeURIComponent(newStatus)
    })
    .then(res => res.text())
    .then(data => {

        console.log("Update response:", data);

        if(data.trim() === "Update Successful"){

            const stockEl =
                document.getElementById(id + "-stock");

            const statusEl =
                document.getElementById(id + "-status");

            stockEl.innerText = newStock;
            statusEl.innerText = newStatus;

            if(newStatus === "Available"){
                statusEl.className = "available";
            }else{
                statusEl.className = "not-available";
            }

            alert("Saved to database!");

        }else{
            alert("Database update failed!");
        }

    })
    .catch(err => {
        console.log(err);
        alert("Server connection error.");
    });

}
