const formularioLogin = document.getElementById("formularioLogin");

formularioLogin.addEventListener("submit", function(event) {
    event.preventDefault();

    const usuario = document.getElementById("usuario").value.trim();
    const clave = document.getElementById("clave").value;
    const mensaje = document.getElementById("mensaje");
if (usuario === "" || clave === ""){
        mensaje.textContent = "Completa todos los campos.";
        mensaje.style.color = "red";
        return;
}
if (usuario === "admin" && clave === "1234") {
        mensaje.textContent = "Ingreso correcto.";
        mensaje.style.color = "green";
    } else {
        mensaje.textContent = "Usuario o contraseña incorrectos.";
        mensaje.style.color = "red";
    }
});
const formularioCompra = document.getElementById("formularioCompra");

formularioCompra.addEventListener("submit", function(event) {
    event.preventDefault();

    const nombre = document.getElementById("nombre").value.trim();
    const email = document.getElementById("email").value.trim();
    const cantidad = document.getElementById("cantidad").value;
    const mensajeCompra = document.getElementById("mensajeCompra");

    if (nombre === "" || email === "" || cantidad === "") {
        mensajeCompra.textContent = "Completa todos los campos.";
        mensajeCompra.style.color = "red";
        return;
    }
    if (Number(cantidad) <= 0) {
        mensajeCompra.textContent = "La cantidad debe ser mayor que cero.";
        mensajeCompra.style.color = "red";
        return;
    }
     if (!email.includes("@") || !email.includes(".")) {
        mensajeCompra.textContent = "Ingresá un correo válido.";
        mensajeCompra.style.color = "red";
        return;
    }
  mensajeCompra.textContent = "Compra realizada correctamente.";
    mensajeCompra.style.color = "green";
});
