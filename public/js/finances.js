function displayCommune() {
    $("#commune").show()
    $("#eau").hide();
    $("#assainissement").hide();
}
function displayEau() {
    $("#commune").hide()
    $("#eau").show();
    $("#assainissement").hide();
}
function displayAssainissement() {
    $("#commune").hide();
    $("#eau").hide();
    $("#assainissement").show();
}

function displayNoFinance() {
    $("#commune").hide();
    $("#eau").hide();
    $("#assainissement").hide();
}

function displayFinanceFromLabel(label) {
    switch (label) {
        case 'display-commune' :
            displayCommune();
            break;
        case 'display-eau' :
            displayEau();
            break;
        case 'display-assainissement' :
            displayAssainissement();
            break;
        default:
            displayNoActivity();
            break;
    }
}

$(".finance-label").on("click", function() {
    console.log("clic libellé finance :");
    var label = $(this).attr('id');
    console.log(label);
    displayFinanceFromLabel(label);
  
   });