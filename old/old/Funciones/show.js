
document.getElementById("mostrarFormulario1").addEventListener("click", function() {
    document.getElementById("formulario1").style.display = "block";
    document.getElementById("formulario2").style.display = "none";
    document.getElementById("formulario3").style.display = "none";
    document.getElementById("formulario4").style.display = "none";
    document.getElementById("formulario4").style.display = "none";


});

document.getElementById("mostrarFormulario2").addEventListener("click", function() {
    document.getElementById("formulario1").style.display = "none";
    document.getElementById("formulario2").style.display = "block";
    document.getElementById("formulario3").style.display = "none";
    document.getElementById("formulario4").style.display = "none";

});

document.getElementById("mostrarFormulario3").addEventListener("click", function() {
    document.getElementById("formulario1").style.display = "none";
    document.getElementById("formulario2").style.display = "none";
    document.getElementById("formulario3").style.display = "block";
    document.getElementById("formulario4").style.display = "none";

});


document.getElementById("mostrarFormulario4").addEventListener("click", function() {
    document.getElementById("formulario1").style.display = "none";
    document.getElementById("formulario2").style.display = "none";
    document.getElementById("formulario3").style.display = "none";
    document.getElementById("formulario4").style.display = "block";

});
