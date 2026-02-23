/* ================= CAP CLAIM ================= */
function claimCap(id, btn){

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


/* ================= CAP UPDATE (ADMIN) ================= */
function updateCap(id){

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

    /* Enable / Disable Claim Button */
    const claimBtn = document.querySelector(
        `button[onclick="claimCap('${id}',this)"]`
    );

/* ================= CAP UPDATE (FIXED VERSION) ================= */
function updateCap(id){

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

    /* UPDATE STOCK */
    if(!isNaN(newStock)){
        stockEl.innerText = newStock;
    }

    /* UPDATE STATUS */
    statusEl.innerText = newStatus;

    if(newStatus === "Available"){
        statusEl.className = "available";
    } else {
        statusEl.className = "not-available";
    }

    /* AUTO FIX IF ZERO */
    if(parseInt(stockEl.innerText) === 0){
        statusEl.innerText = "Not Available";
        statusEl.className = "not-available";
    }

    /* FIX: find claim button safely */
    const row = stockEl.closest("tr");
    const claimBtn = row.querySelector(".claim-col button");

    if(claimBtn){
        if(parseInt(stockEl.innerText) > 0 && newStatus === "Available"){
            claimBtn.disabled = false;
        } else {
            claimBtn.disabled = true;
        }
    }

    alert("Cap updated successfully!");
}
