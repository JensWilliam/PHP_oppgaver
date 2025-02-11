$(document).ready(function() {
    $("#toggleCars").click(function() {
        let carBox = $(".biler-boks");
        let layout = $(".layout");

        if (carBox.is(":visible")) {
            // Skjuler bilboksen med fade + flytter den ut
            carBox.css("opacity", "0").css("transform", "translateX(50px)");
            setTimeout(() => { carBox.hide(); }, 300);

            // Endrer klassen slik at profilboksen får mer plass
            layout.removeClass("double").addClass("single");
        } else {
            // Viser bilboksen med fade + flytter den inn
            carBox.show().css("opacity", "0").css("transform", "translateX(50px)");
            setTimeout(() => {
                carBox.css("opacity", "1").css("transform", "translateX(0)");
            }, 10);

            // Endrer klassen slik at profilboksen flytter seg smooth
            layout.removeClass("single").addClass("double");
        }
    });
});

