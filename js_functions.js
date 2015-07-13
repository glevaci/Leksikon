// funkcije za provjeru forme

function checkUsername() {
    var name = $("input[name=txtUsername]").val();

    if (name.length <= 6)
        $("#spanUsername").text("Korisničko ime mora biti duljine barem 6!");
    else
        $("#spanUsername").text("");
        
}

function checkMail() {
    var mail = $("input[name=regMail]").val();
    console.log(mail);

    regex = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    if (!regex.test(mail))
        $("#spanMail").text("Pogrešna e-mail adresa!");
    else 
        $("#spanMail").text("");
}

function checkPasswordMatch() {
    var password = $("input[name=regPassword]").val();
    var confirmPassword = $("input[name=regConfirmPassword]").val();

    if (password != confirmPassword)
        $("#spanPasswordMatch").text("Lozinke nisu jednake!");
    else
        $("#spanPasswordMatch").text("");
}