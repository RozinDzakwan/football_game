// $(document).ready(function () {
//     alert("cek");
// });
$("#button-back-default").on("click", function () {
    document.getElementById("button-back-default").style.display = "none";
    document.getElementById("button-back-clicked").style.display = "block";
    document.getElementById("fb-loading").style.display = "block";
});

$("#button-next-default").on("click", function () {
    document.getElementById("button-next-default").style.display = "none";
    document.getElementById("button-next-clicked").style.display = "block";
    document.getElementById("fb-loading").style.display = "block";
});

$("#button-start-default").on("click", function () {
    document.getElementById("button-start-default").style.display = "none";
    document.getElementById("button-start-clicked").style.display = "block";
    document.getElementById("fb-loading").style.display = "block";
});

$("#choose-default").on("click", function () {
    document.getElementById("choose-default").style.display = "none";
    document.getElementById("choose-clicked").style.display = "block";
    document.getElementById("fb-loading").style.display = "block";
});

$("#play-default").on("click", function () {
    document.getElementById("play-default").style.display = "none";
    document.getElementById("play-clicked").style.display = "block";
    document.getElementById("fb-loading").style.display = "block";
});

$("#store-default").on("click", function () {
    document.getElementById("store-default").style.display = "none";
    document.getElementById("store-clicked").style.display = "block";
    document.getElementById("fb-loading").style.display = "block";
});
