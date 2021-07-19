const table_productos_lista = document.getElementById("table_productos_lista")
const container_servicios = document.getElementById("container-servicios")

if(table_productos_lista){
    table_productos_lista.addEventListener("click", async (event) => {
        event.preventDefault();
        const target = event.target
        if(target.closest('button') != undefined){
            let idproducto = target.closest('button').dataset.idproducto

            let nombreProducto = target.closest('button').parentNode.nextElementSibling.innerText
            document.getElementById('modal-nombre_producto').innerText = nombreProducto
            document.querySelector('.modal-bg').classList.add('modal-bg--active');
            document.querySelector('.modal').addEventListener('click', async (event) => {
                event.preventDefault();
                const targetModal = event.target
                if(targetModal.closest('.modal__action--cancelar') != undefined){
                    document.querySelector('.modal-bg').classList.remove('modal-bg--active');
                }
                if(targetModal.closest('.modal__action--continuar') != undefined){
                    document.querySelector('.modal-bg').classList.remove('modal-bg--active');
                    target.closest('button').parentNode.parentNode.remove()
                    const form = new FormData()
                    form.append("idproducto", idproducto)
                    form.append("btnQuitarProducto","")
                    const response = await fetch('../moduloVentas/getComprobantePago.php',{
                        method: 'POST',
                        body: form
                    })
                    const data = await response.json()
                    console.log("🚀 ~ file: comprobante.js ~ line 32 ~ document.querySelector ~ data", data)
                    document.getElementById("precioTotal").innerText = data["precioTotal"]
                    let igv = parseFloat(data["precioTotal"]).toFixed(2) * parseFloat(0.18).toFixed(2)
                    let subtotal = parseFloat(data["precioTotal"]).toFixed(2) - igv
            
                    igv = parseFloat(igv).toFixed(2)
                    subtotal = parseFloat(subtotal).toFixed(2)
                    document.getElementById("igv").innerText = igv
                    document.getElementById("subtotal").innerText = subtotal
                }
            })
        }
        if(target.closest('.input-counter') != undefined){
            let idproducto = target.closest('.input-counter').dataset.idproducto
            let value = target.closest('.input-counter').value
            const form = new FormData()
            form.append("idproducto", idproducto)
            form.append("cantidad", value)
            form.append("btnCounterProducto", "")
            const response = await await fetch("getComprobantePago.php",{
                method: 'POST',
                body: form
            });
            const data = await response.json();
            [ ...document.querySelectorAll('.input-result') ].forEach(element => {
                let precioUnitario = parseFloat(element.parentNode.previousElementSibling.previousElementSibling.firstElementChild.value)
                let cantidad = parseFloat(element.parentNode.previousElementSibling.firstElementChild.value).toFixed(2)
                element.value = parseFloat(precioUnitario*cantidad).toFixed(2);
            });
            document.getElementById("precioTotal").innerText = data["precioTotal"]
            let igv = parseFloat(data["precioTotal"]).toFixed(2) * parseFloat(0.18).toFixed(2)
            let subtotal = parseFloat(data["precioTotal"]).toFixed(2) - igv

            igv = parseFloat(igv).toFixed(2)
            subtotal = parseFloat(subtotal).toFixed(2)
            document.getElementById("igv").innerText = igv
            document.getElementById("subtotal").innerText = subtotal
        }
    })
}
if(container_servicios){
    container_servicios.addEventListener("change", async (event) => {
        const target = event.target
        let idservicio = target.dataset.idservicio
        const form = new FormData()
        form.append("idservicio", idservicio)
        form.append(target.checked ? "btnAgregarServicio":"btnQuitarServicio", "")
        const response = await await fetch("getComprobantePago.php",{
            method: 'POST',
            body: form
        });
        const data = await response.json();
        document.getElementById("precioTotal").innerText = data["precioTotal"]
        let igv = parseFloat(data["precioTotal"]).toFixed(2) * parseFloat(0.18).toFixed(2)
        let subtotal = parseFloat(data["precioTotal"]).toFixed(2) - igv

        igv = parseFloat(igv).toFixed(2)
        subtotal = parseFloat(subtotal).toFixed(2)
        document.getElementById("igv").innerText = igv
        document.getElementById("subtotal").innerText = subtotal
    })
}


const validarRuc = document.getElementById("validar-ruc")
const ruc = document.getElementById("ruc")

if(validarRuc && ruc){
    validarRuc.addEventListener("click", async (event) => {
        event.preventDefault();
        const response = await fetch("https://api.migo.pe/api/v1/ruc",{
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json"
            },
            method: 'POST',
            body: JSON.stringify({
                "ruc": ruc.value,
                "token": "rSdUJkM7rjvGAuzu3T2LnBrBAzOAnJ6miuvbG1ZwEpwJ7yM9OZgOT20bbNHh"
            }),
        })

        const data = await response.json()
        if(data["success"]){
            console.log("el ruc es correcto")
            console.log(data)
        }else{

            console.log(data)
        }
        
    })
}