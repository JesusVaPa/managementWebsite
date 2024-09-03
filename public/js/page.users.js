function clearForm() {
    $('#user-form').get(0).reset();
    $("#create-button").prop("disabled", false);
    $("#clear-button").prop("disabled", true);
    $("#update-button").prop("disabled", true);
    $("#delete-button").prop("disabled", true);
    document.getElementById("user-selector").value = "";
}

function updateClearButtonState() {
    let dirtyElements = $("#user-form")
        .find('*')
        .filter(":input")
        .filter((index, element) => {
            return $(element).val();
        });
    if (dirtyElements.length > 0) {
        $("#clear-button").prop("disabled", false);
    } else {
        $("#clear-button").prop("disabled", true);
    }
}

function getFormDataAsUrlEncoded() {
    let formData = new FormData();
    formData.set("userId", $("#user-id").val());
    formData.set("username", $("#user-username").val());
    formData.set("password", $("#user-password").val());
    formData.set("email", $("#user-email").val());
    formData.set("dateCreated", $("#user-date-created").val());
    formData.set("dateLastModified", $("#user-date-last-modified").val());
    console.log(Object.fromEntries(formData));
    return (new URLSearchParams(formData)).toString();
}

function fillFormFromResponseObject(entityObject) {
    if ('userId' in entityObject) {
        $("#user-id").val(entityObject.userId);
    }
    if ('username' in entityObject) {
        $("#user-username").val(entityObject.username);
    }
    if ('password' in entityObject) {
        $("#user-password").val(entityObject.password);
    }
    if ('email' in entityObject) {
        $("#user-email").val(entityObject.email);
    }
    if ('dateCreated' in entityObject) {
        $("#user-date-created").val(entityObject.dateCreated);
    }
    if ('dateLastModified' in entityObject) {
        $("#user-date-last-modified").val(entityObject.dateLastModified);
    }
    $("#create-button").prop("disabled", true);
    $("#clear-button").prop("disabled", false);
    $("#update-button").prop("disabled", false);
    $("#delete-button").prop("disabled", false);
}

function displayResponseError(responseErrorObject) {
    let errorContainer = $(".error-display");
    let classnameContainer = $("#error-class");
    let messageContainer = $("#error-message");
    let previousContainer = $("#error-previous");
    let stacktraceContainer = $("#error-stacktrace");
    if ('exception' in responseErrorObject && typeof responseErrorObject.exception === "object") {
        let exception = responseErrorObject.exception;
        classnameContainer.empty();
        messageContainer.empty();
        previousContainer.empty();
        if ('exceptionClass' in exception) {
            classnameContainer.html(exception.exceptionClass);
        }
        if ('message' in exception) {
            messageContainer.html(exception.message);
        }
        while ('previous' in exception && typeof exception.previous === "object") {
            exception = exception.previous;
            if ('exceptionClass' in exception && 'message' in exception) {
                previousContainer.append(`Caused by: ${exception.exceptionClass}: ${exception.message}<br/>`);
            }
        }
    }
    stacktraceContainer.empty();
    if ('stacktrace' in responseErrorObject) {
        stacktraceContainer.html(responseErrorObject.stacktrace.replace(/\r\n/g, '\n'));
    }
    errorContainer.slideToggle().delay(5000).slideToggle();

}

function loadUser() {
    let selectedRecordId = document.getElementById("user-selector").value;

    const options = {
        "url": `${API_USER_URL}?userId=${selectedRecordId}`,
        "method": "get",
        "dataType": "json"
    };

    $.ajax(options)
        .done((data, status, jqXHR) => {
            console.log("Received data: ", data);
            fillFormFromResponseObject(data);
        })
        .fail((jqXHR, textstatus, error) => {
            if ('responseJSON' in jqXHR && typeof jqXHR.responseJSON === "object") {
                displayResponseError(jqXHR.responseJSON);
            }
        });
}

function createUser() {
    const options = {
        "url": `${API_USER_URL}`,
        "method": "post",
        "data": getFormDataAsUrlEncoded(),
        "dataType": "json"
    };

    $.ajax(options)
        .done((data, status, jqXHR) => {
            console.log("Received data: ", data);

            if ('username' in data) {
                let selector = document.getElementById("user-selector");
                let newOptionElement = document.createElement("option");
                newOptionElement.value = data.id;
                newOptionElement.innerHTML = `${data.username}`;
                selector.appendChild(newOptionElement);
                selector.value = data.id;
            }
            fillFormFromResponseObject(data);
        })
        .fail((jqXHR, textstatus, error) => {
            if ('responseJSON' in jqXHR && typeof jqXHR.responseJSON === "object") {
                displayResponseError(jqXHR.responseJSON);
            }
        });
}

function updateUser() {
    const options = {
        "url": `${API_USER_URL}`,
        "method": "put",
        "data": getFormDataAsUrlEncoded(),
        "dataType": "json"
    };

    $.ajax(options)
        .done((data, status, jqXHR) => {
            console.log("Received data: ", data);

            // Replace the text in the selector with the updated values
            let formIdValue = document.getElementById("user-id").value;
            if ('username' in data) {
                let selector = /** @type {HTMLSelectElement} */ document.getElementById("user-selector");
                // Note: voluntary non-identity equality check ( == instead of === ): disable warning
                // noinspection EqualityComparisonWithCoercionJS
                [...selector.options].filter(elem => elem.value == formIdValue).forEach(elem => {
                    elem.innerHTML = `${data.username}`;
                });
            }
            fillFormFromResponseObject(data);
        })
        .fail((jqXHR, textstatus, error) => {
            if ('responseJSON' in jqXHR && typeof jqXHR.responseJSON === "object") {
                displayResponseError(jqXHR.responseJSON);
            }
        });
}

function deleteUser() {
    const options = {
        "url": `${API_USER_URL}`,
        "method": "delete",
        "data": getFormDataAsUrlEncoded(),
        "dataType": "json"
    };

    $.ajax(options)
        .done((data, status, jqXHR) => {
            console.log("Received data: ", data);
            let formIdValue = document.getElementById("user-id").value;
            if (formIdValue) {
                let selector = /** @type {HTMLSelectElement} */ document.getElementById("user-selector");
                // Note: voluntary non-identity equality check ( == instead of === ): disable warning
                // noinspection EqualityComparisonWithCoercionJS
                [...selector.options].filter(elem => elem.value == formIdValue).forEach(elem => elem.remove());
                selector.value = "";
            }
            clearForm();
        })
        .fail((jqXHR, textstatus, error) => {
            if ('responseJSON' in jqXHR && typeof jqXHR.responseJSON === "object") {
                displayResponseError(jqXHR.responseJSON);
            }
        });
}

document.getElementById("view-instance-button").onclick = loadUser;
document.getElementById("clear-button").onclick = clearForm;
document.getElementById("create-button").onclick = createUser;
document.getElementById("update-button").onclick = updateUser;
document.getElementById("delete-button").onclick = deleteUser;
$("#user-form").on("change", ":input", updateClearButtonState);