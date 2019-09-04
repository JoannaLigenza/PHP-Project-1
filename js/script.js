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
            const currentLang = window.location.pathname;
            // setting ajax options - site to load, method, data passed in to .php file, 
            $.ajax({
                url     :   '../favourites-js.php',
                method  :   'post',
                dataType:   'json',
                data    :   {'isLoggedInName': isLoggedInName, currentLang: currentLang},
                success :   function(response) {
                                if (response === "yes") {   
                                    const checkSrc = $(e.target).attr("src");
                                    if (checkSrc === "img/heart-e.svg") {
                                        $(e.target).attr("src", "img/heart-f.svg");
                                    } else {
                                        $(e.target).attr("src", "img/heart-e.svg");
                                    }
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
            const path = window.location.pathname;
            const lang = path.split("/")[1];
            const name_notLoggedIn = $(e.target).attr("name");
            if (lang === "pl") {
                    $("#log-in-message-"+name_notLoggedIn).text("Zaloguj się najpierw!");  
                } else if (lang === "en") {
                    $("#log-in-message-"+name_notLoggedIn).text("Log In First!");  
                }         
        });
    }
    showLogginMessage();


    // load up/down icon when click on rating system
    function rateAnswear() {
        $(".rate-up, .rate-down").on("click", function(e) {
            e.preventDefault();
            const logoutButton = $("#logout-button");
            const path = window.location.pathname;
            const lang = path.split("/")[1];
            const clickedButton = $(e.target.closest("button")).attr("name").split('-');
            const answearId = clickedButton[2];
            const arrDirection = clickedButton[1];
            const clickedArr = $(e.target.closest("button")).attr("name");
            if (logoutButton.length < 1) {
                answearParagraphId = $("#login-first-message-"+answearId);
                if (lang === "pl") {
                    answearParagraphId.text("Zaloguj się najpierw!");
                } else if (lang === "en") {
                    answearParagraphId.text("Log in first!");
                }
            } else {
                $.ajax({
                    url     :   '../favourites-js.php',
                    method  :   'post',
                    dataType:   'json',
                    data    :   {clickedArr: clickedArr, answearId: answearId, arrDirection: arrDirection, currentLang: path},
                    success :   function(response) {
                                    const votesDiv = $("#votes-"+answearId);
                                    const votesText = votesDiv.text();
                                    if (arrDirection === "up") {
                                        if (response[0] === "orange") {   
                                            $(e.target).attr("src", "../img/arr-up.svg");
                                            // if arr up is orange change arr down to grey
                                            $("#arr-down-"+answearId+" img").attr("src", "../img/arr-down-grey.svg");
                                            // change number of votes
                                            votesDiv.text(parseInt(votesText)+response[1]);
                                        }
                                        else if (response[0] === "grey") {   
                                            $(e.target).attr("src", "../img/arr-up-grey.svg");
                                            votesDiv.text(parseInt(votesText)-response[1]);
                                        }
                                    } else if (arrDirection === "down") {
                                        if (response[0] === "orange") {   
                                            $(e.target).attr("src", "../img/arr-down.svg");
                                            // if arr down is orange change arr up to grey
                                            $("#arr-up-"+answearId+" img").attr("src", "../img/arr-up-grey.svg");
                                            // change number of votes
                                            votesDiv.text(parseInt(votesText)-response[1]);
                                        }
                                        else if (response[0] === "grey") {   
                                            $(e.target).attr("src", "../img/arr-down-grey.svg");
                                            votesDiv.text(parseInt(votesText)+response[1]);
                                        }
                                    }
                                    
                                }
                })
            }

        })
    }
    rateAnswear();


    // FORM VALIDATION FUNCTIONS //
    function checkValidation(hook) {
        const path = window.location.pathname;
        const lang = path.split("/")[1];
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
            if (lang === "pl") {
                $("#signin-form p").text("Wprowadź poprawny "+text);
            } else if (lang === "en") {
                $("#signin-form p").text("Please enter valid "+text);
            }
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
                dataType:   'json',
                data    :   passedData,
                success :   function(response) {
                    if (response === 1) {
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
                    dataType:   'json',
                    data    :   {signinButton: signinButton, 'signin-username': username, 'signin-email': email, 'signin-pass': password},
                    success :   function(response) {
                        if (response === 1) {
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