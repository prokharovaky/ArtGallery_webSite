$(document).ready(function() {

    $('form input, form select, form textarea').on('input', function() {
        var field = $(this).attr('name');
        var value = $(this).val();         

        if (field === 'password' || field === 'passwordAgain') {
            var password = $('#password').val();  
            var passwordAgain = $('#passwordAgain').val();  

            $.ajax({
                type: 'POST',
                url: 'validate.php',
                data: {
                    field: field,  
                    password: password,  
                    passwordAgain: passwordAgain,
                    value: value
                },
                success: function(response) {
                    handleValidationResponse(response, field);
                    // toggleSubmitButton();
                }
            });
        } else {
            $.ajax({
                type: 'POST',
                url: 'validate.php',
                data: {
                    field: field,  
                    value: value   
                },
                success: function(response) {
                    handleValidationResponse(response, field);
                    // toggleSubmitButton();
                }
            });
        }
    });

    // $(document).on('click', '#reg', function(event) {
    //     alert("Все окей");
    //     event.preventDefault(); 
    
    //     var login = $('#login').val();
    //     var password = $('#password').val();
    //     var passwordAgain = $('#passwordAgain').val();
    //     var name = $('#name').val();
    //     var phone = $('#phone').val();
    //     var gender = $('#gender').val();
    //     var captcha = $('#captcha').val();
    
    //     $.ajax({
    //         url: 'registrate.php',
    //         type: 'POST',
    //         data: {
    //             login: login, 
    //             password: password, 
    //             passwordAgain: passwordAgain,
    //             name: name,
    //             phone: phone, 
    //             gender: gender, 
    //             captcha: captcha,
    //             reg: true 
    //         },
    //         dataType: "json",
    //         success: function(response) {
    //             if (response.success) {
    //                 alert("Регистрация успешна!");
    //             } else {
    //                 var errorMessages = "Ошибки при регистрации:\n";
    //                 for (var key in response) {
    //                     errorMessages += response[key] + "\n";
    //                 }
    //                 alert(errorMessages);
    //             }
    //         },
    //         error: function(xhr, status, error) {
    //             console.error("Ошибка AJAX-запроса: ", error);
    //         }
    //     });
    // });




    // $('#registrationForm').on('submit', function(event) {
    //     // alert("Данные получены успешно!");
    //     event.preventDefault(); 

    //     var formData = $(this).serialize();

    //     $.ajax({
    //         type: 'POST',
    //         url: 'registrate.php',
    //         data: formData,
    //         success: function(response) {
    //             var data = JSON.parse(response);
    //             if (data.success) {
    //                 alert("Регистрация успешна!");
    //             } else {
    //                 alert("Ошибки при регистрации:\n" + JSON.stringify(data));
    //             }
    //         }
    //     });
    // });
});

function handleValidationResponse(response, field) {
    var data = JSON.parse(response);
    $('#' + field).next('.error-message').remove();

    if (data[field]) {
        $('#' + field).after('<p class="error-message">' + data[field] + '</p>');
    }
}