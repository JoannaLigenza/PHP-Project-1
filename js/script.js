$("document").ready(function(){

    // Shows tooltip
    function showTooltop() {
        $('[data-toggle="tooltip"]').tooltip();
    };
    showTooltop();


    // scroll to section on page
    function smoothScrollingToElement(scrollFrom, scrollTo) {
        $(scrollFrom).on("click", function(e) {
		    e.preventDefault();
            scrollTo = $(scrollTo);
            $('html, body').animate({
                scrollTop: scrollTo.offset().top
            }, 600);
        });
    };
    smoothScrollingToElement("#scroll-to-add-answear-button", "#add-answear-div");
    

    // load heart when click add to favourites icon
    function addToFavourites() {
        $(".add-to-favourites-img-login").on("click", function(e) {
            e.preventDefault();
            const isLoggedInName = $(e.target).attr("name");
            // setting ajax options - site to load, method, data passed in to .php file, 
            $.ajax({
                url     :   '../favourites-js.php',
                method  :   'post',
                data    :   {'isLoggedInName': isLoggedInName},
               // dataType:  'text',
                success :   function(response, status, options) {
                    //console.log(response);                              // ??????????????????????????????????????????
                                // if (response === "1") {   
                                // }
                                const checkSrc = $(e.target).attr("src");
                                if (checkSrc === "img/heart-e.svg") {
                                    $(e.target).attr("src", "img/heart-f.svg");
                                } else {
                                    $(e.target).attr("src", "img/heart-e.svg");
                                }
                            }
            })
        });
    }
    addToFavourites();

    // shows login-message when clicked on heart icon and not log in first
    function showLogginMessage() {
        $(".add-to-favourites-img-notlogin").on("click", function(e) {
            e.preventDefault();
            const name_notLoggedIn = $(e.target).attr("name");
            $("#log-in-message-"+name_notLoggedIn).text("Log In First!");           
        });
    }
    showLogginMessage();

    // FORM VALIDATION FUNCTIONS //
    function checkValidation(hook) {
        const inputValue =  $(hook).val();
        let reg;
        let text;
        if (hook === "#signin-username") {
            reg = /^[a-z0-9-]{3,30}$/i;
            text = "username";
        }
        if (hook === "#signin-email") {
            reg = /^[A-Z0-9-._]+@[A-Z0-9-._]+\.[A-Z]{2,25}$/i;
            text = "email";
        }
        if (hook === "#signin-pass") {
            reg = /^[a-zA-Z0-9?!#]{6,30}$/i;
            text = "password";
        }
        if (!reg.test(inputValue)) {
            $(hook).addClass("red-border");
            $("#signin-button").prop("disabled",true);
            $("#signin-form p").text("Please enter valid "+text);
            return false;
        } else {
            $(hook).removeClass("red-border");
            $("#signin-button").prop("disabled",false);
            $("#signin-form p").text("");
            return true;
        }
    }

    // validation inputs on signin form
    function validateInputs(hook)  {
        $(hook).on("input", function() {
            checkValidation(hook);
        });
    }
    validateInputs("#signin-username");
    validateInputs("#signin-email");
    validateInputs("#signin-pass");
    

    // checking if username or email are already taken
    function isTaken(hook)  {
        $(hook).on("blur", function() {
            if (!checkValidation(hook)) {
                $("#signin-button").prop("disabled",true);
                return
            }
            const inputValue =  $(hook).val();
            const path = window.location.pathname;
            const lang = path.split("/")[1];
            let passedData;
            if (hook === "#signin-username"){
                passedData = {username: inputValue}
            }
            if (hook === "#signin-email"){
                passedData = {email: inputValue}
            }
            let options = {
                url     :   '../userdata-veryfication-js.php',
                method  :   'post',
                data    :   passedData,
                success :   function(response) {
                   // console.log(response);
                    if (response === "1") {
                        if (lang === "pl") {
                            $(hook).addClass("red-border");
                            $("#signin-button").prop("disabled",true);
                            $("#signin-form p").text("Ta nazwa użytkownika jest już zajęta");
                        } else if (lang === "en"){
                            $(hook).addClass("red-border");
                            $("#signin-button").prop("disabled",true);
                            $("#signin-form p").text("This username is already taken");
                        } 
                    } else {
                        $(hook).removeClass("red-border");
                        $("#signin-button").prop("disabled",false);
                        $("#signin-form p").text("");
                    } 
                }
            }
            $.ajax(options); 
        });    
    }
    isTaken("#signin-username");
    isTaken("#signin-email");


    // signin form validation on submit 
    function submitValidation() {
        $("#signin-form").on("submit", function(e) {
            e.preventDefault();
           // console.log("event ", e);
            const username = $("#signin-username").val();
            const email = $("#signin-email").val();
            const password = $("#signin-pass").val();
            const signinButton = $("#signin-button").attr('name');

            if (!username || !email || !password) {
                $("#signin-form p").text("Please fill all fields!");
            } else {
                $.ajax({
                    url     :   '../userdata-veryfication-js.php',
                    method  :   'post',
                    data    :   {signinButton: signinButton, 'signin-username': username, 'signin-email': email, 'signin-pass': password},
                    success :   function(response) {
                        if (response === "1") {
                            window.location.replace("?signup=success");
                        } 
                    }
                }) 
            }
        })
    }
    submitValidation();
    // FORM VALIDATION FUNCTIONS - END //
});