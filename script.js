const users = [
    { username: "admin", password: "adminpass", isAdmin: true },
    { username: "user1", password: "userpass", isAdmin: false }
];

let currentUser = null;

// ---------------- LOGIN ----------------
function login() {
    const user = username.value.trim();
    const pass = password.value.trim();

    const validUser = users.find(
        u => u.username === user && u.password === pass
    );

    if (validUser) {
        currentUser = validUser;
        loginBox.style.display = "none";
        inventory.style.display = "block";
        error.innerText = "";

        if (currentUser.isAdmin) {
            document.querySelectorAll(".admin-control")
                .forEach(e => e.style.display = "table-cell");
            document.querySelectorAll(".claim-col")
                .forEach(e => e.style.display = "none");
        } else {
            document.querySelectorAll(".admin-control")
                .forEach(e => e.style.display = "none");
            document.querySelectorAll(".claim-col")
                .forEach(e => e.style.display = "table-cell");
        }
    } else {
        error.innerText = "Invalid username or password!";
    }
}

// ---------------- LOGOUT ----------------
function logout() {
    currentUser = null;
    inventory.style.display = "none";
    loginBox.style.display = "block";
    username.value = "";
    password.value = "";
}

// ---------------- CLAIM ----------------
function claim(id, btn) {
    const stockEl = document.getElementById(id + "-stock");
    const statusEl = document.getElementById(id + "-status");

    let stock = parseInt(stockEl.innerText);

    if (stock <= 0 || statusEl.innerText === "Not Available") {
        btn.disabled = true;
        return;
    }

    stock--;
    stockEl.innerText = stock;

    if (stock === 0) {
        statusEl.innerText = "Not Available";
        statusEl.className = "not-available";
        btn.disabled = true;
    }

    inventory.style.display = "none";
    thankYou.style.display = "block";

    setTimeout(() => {
        thankYou.style.display = "none";
        inventory.style.display = "block";
    }, 2000);
}

// ---------------- ADMIN UPDATE ----------------
function updateUniform(id) {
    if (!currentUser || !currentUser.isAdmin) return;

    const stockEl = document.getElementById(id + "-stock");
    const statusEl = document.getElementById(id + "-status");
    const stockInput = document.getElementById(id + "-stock-edit");
    const statusInput = document.getElementById(id + "-status-edit");

    let newStock = parseInt(stockInput.value);
    let newStatus = statusInput.value;

    if (!isNaN(newStock)) {
        stockEl.innerText = newStock;
    }

    statusEl.innerText = newStatus;
    statusEl.className =
        newStatus === "Available" ? "available" : "not-available";

    // enable / disable claim button
    const row = stockEl.parentElement;
    const btn = row.querySelector(".claim-col button");

    if (btn) {
        if (newStatus === "Not Available" || newStock === 0) {
            btn.disabled = true;
        } else {
            btn.disabled = false;
        }
    }

    // clear inputs
    stockInput.value = "";
}
